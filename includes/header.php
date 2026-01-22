<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Muver - Encuentra los mejores proveedores de mudanza y flete. Cotiza tu mudanza en minutos.">
    <title>Muver - Cotiza tu Mudanza</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #f97316;
            --bg-gradient: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .navbar-brand .brand-icon {
            color: var(--secondary-color);
        }
        
        .hero-section {
            background: var(--bg-gradient);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 50px 50px;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
        }
        
        .quote-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2.5rem;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }
        
        .quote-card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .quote-card-header h2 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .quote-card-header p {
            color: #64748b;
        }
        
        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        
        .input-group-text {
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: var(--primary-color);
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }
        
        .btn-primary-custom:active {
            transform: translateY(0);
        }
        
        .feature-badge {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            margin: 0.25rem;
        }
        
        .truck-animation {
            animation: drive 3s ease-in-out infinite;
        }
        
        @keyframes drive {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(10px); }
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
        }
        
        .step.active {
            color: var(--primary-color);
        }
        
        .step-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .step.active .step-number {
            background: var(--primary-color);
            color: white;
        }
        
        .floating-truck {
            position: absolute;
            right: 10%;
            top: 50%;
            transform: translateY(-50%);
            font-size: 8rem;
            opacity: 0.1;
        }
        
        .features-section {
            padding: 4rem 0;
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--primary-color);
        }
        
        .loading-spinner {
            display: none;
        }
        
        .btn-primary-custom.loading .loading-spinner {
            display: inline-block;
        }
        
        .btn-primary-custom.loading .btn-text {
            display: none;
        }
        
        /* Alertas personalizadas */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .alert-success-custom {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
        }
        
        .alert-danger-custom {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 2rem 0;
        }

        footer a {
            color: #94a3b8;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .quote-card {
                padding: 1.5rem;
                margin: 0 1rem;
                margin-top: -30px;
            }
            
            .floating-truck {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: transparent; position: absolute; width: 100%; z-index: 100;">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-truck brand-icon truck-animation"></i> Muver
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#como-funciona">¿Cómo funciona?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
