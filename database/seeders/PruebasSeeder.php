<?php

namespace Database\Seeders;

use App\Models\entrada_salida_personal;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\Models\Tool;
use App\Models\Loan;
use App\Models\ReturnModel;
use App\Models\Ficha;
use App\Models\grupos_personal;
use App\Models\register_personal;

class PruebasSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Herramientas - 30 registros
        $tools = [];
        for ($i = 0; $i < 30; $i++) {
            $tools[] = Tool::create([
                'amount' => $faker->numberBetween(10, 50),
                'product' => ucfirst($faker->words(2, true)),
                'observation' => $faker->sentence(),
            ]);
        }

        // Préstamos - 200 registros
        $loans = [];
        foreach (range(1, 200) as $i) {
            $tool = $faker->randomElement($tools);
            $quantity = $faker->numberBetween(1, $tool->amount);

            $loans[] = Loan::create([
                'tool_id' => $tool->id,
                'item' => $tool->product,
                'quantity' => $quantity,
                'loan_date' => $faker->dateTimeBetween('-6 months', 'now'),
                'requester_name' => $faker->name(),
                'requester_id' => $faker->numerify('ID-####'),
                'delivered_by' => $faker->userName(),
                'loan_status' => $faker->randomElement(['En préstamo', 'Pendiente', 'Devuelto parcialmente']),
                'returned_quantity' => 0,
            ]);
        }

        // Devoluciones - 100 registros aleatorios
        $randomLoans = $faker->randomElements($loans, 100);
        foreach ($randomLoans as $loan) {
            $returnedQty = $faker->numberBetween(1, $loan->quantity);

            ReturnModel::create([
                'loan_id' => $loan->id,
                'return_date' => $faker->dateTimeBetween($loan->loan_date, 'now'),
                'quantity_returned' => $returnedQty,
                'return_status' => $faker->randomElement(['Buen estado', 'Dañado', 'Incompleto']),
                'received_by' => $faker->userName(),
            ]);

            $loan->returned_quantity = $returnedQty;
            $loan->save();
        }

        // Grupos personal - nombres coherentes
        $grupoNombres = ['ADSO', 'Análisis', 'Desarrollo', 'Ganadería', 'Producción', 'Administración'];
        $grupos = [];
        foreach ($grupoNombres as $nombre) {
            $grupos[] = grupos_personal::create(['nombre' => $nombre]);
        }

        // Fichas - números coherentes 10 fichas
        $fichas = [];
        foreach (range(1001, 1010) as $numFicha) {
            $grupo = $faker->randomElement($grupos);
            $fichas[] = Ficha::create([
                'nombre' => "Ficha-$numFicha",
                'grupo_id' => $grupo->id,
            ]);
        }

        // Register personal - 50 aprendices con nombres comunes
        $nombresComunes = ['Juan Pérez', 'María López', 'Carlos Gómez', 'Ana Martínez', 'Luis Torres', 'Sofía Díaz', 'Miguel Herrera', 'Laura Sánchez', 'Diego Fernández', 'Elena Morales'];
        for ($i = 0; $i < 50; $i++) {
            $grupo = $faker->randomElement($grupos);
            $ficha = $faker->randomElement($fichas);
            $nombre = $faker->randomElement($nombresComunes);

            register_personal::create([
                'nombre' => $nombre,
                'numero_documento' => $faker->unique()->numerify('########'),
                'numero_telefono' => $faker->numerify('3#########'),
                'correo' => $faker->unique()->safeEmail(),
                'grupo' => $grupo->id,
                'fichas' => $ficha->id,
            ]);
        }

        // Entrada salida personal - 70 registros (o menos si hay menos personales)
        $personales = register_personal::all();
        $cantidad = min(70, $personales->count());  // Validar cantidad disponible

        foreach ($personales->random($cantidad) as $persona) {
            entrada_salida_personal::create([
                'fecha_hora_ingreso' => $faker->dateTimeBetween('-1 month', 'now'),
                'fecha_hora_salida' => $faker->boolean(80) ? $faker->dateTimeBetween('-1 month', 'now') : null,
                'visito_ultimas_48h' => $faker->boolean(30),
                'nombre' => $persona->id,
                'grupo' => $persona->grupo,
                'ficha' => $persona->fichas,
            ]);
        }
    }
}
