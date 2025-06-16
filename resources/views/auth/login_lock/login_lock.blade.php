<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Restringida | Sistema</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --error: #FF4A4A;
            --error-light: #FFF0F0;
            --text: #2D3748;
            --text-light: #718096;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: linear-gradient(135deg, #F6F9FC 0%, #F1F5F9 100%);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1rem;
            line-height: 1.5;
        }
        
        .card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            text-align: center;
            animation: fadeIn 0.4s ease-out;
        }
        
        .header {
            background: var(--error-light);
            padding: 2rem;
            border-bottom: 1px solid rgba(255, 74, 74, 0.1);
        }
        
        .icon {
            width: 72px;
            height: 72px;
            background: var(--error);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .icon svg {
            width: 32px;
            height: 32px;
            fill: white;
        }
        
        h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--error);
        }
        
        .content {
            padding: 2rem;
        }
        
        p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }
        
        .contact {
            background: #F8FAFC;
            padding: 1.25rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }
        
        .contact p {
            color: var(--text);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        a {
            color: var(--error);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        a:hover {
            text-decoration: underline;
            opacity: 0.9;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                </svg>
            </div>
            <h1>Cuenta Bloqueada</h1>
        </div>
        
        <div class="content">
            <p>El acceso a tu cuenta ha sido restringido por el administrador del sistema.</p>
            
            <div class="contact">
                <p>Para resolver esta situación, contáctanos:</p>
            </div>
        </div>
    </div>
</body>
</html>