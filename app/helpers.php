<?php

if (!function_exists('formatNumberCol')) {
    function formatNumberCol($number) {
        if ($number === null) return '-';

        if (floor($number) == $number) {
            return number_format($number, 0, ',', '.');
        }

        if (round($number, 1) == $number) {
            return number_format($number, 1, ',', '.');
        }

        return number_format($number, 2, ',', '.');
    }
}
