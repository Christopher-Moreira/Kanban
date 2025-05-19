<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicoob - Sistema de Gestão de Contratos</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados Sicoob -->
    <style>
        :root {
            --sicoob-green: #006341;
            --sicoob-green-light: #00814A;
            --sicoob-green-dark: #004B31;
            --sicoob-yellow: #FFCD00;
            --sicoob-yellow-light: #FFE066;
            --sicoob-gray: #F2F2F2;
            --sicoob-text: #333333;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Open Sans', sans-serif;
            color: var(--sicoob-text);
        }
        
        /* Navbar Sicoob Style */
        .navbar {
            background-color: var(--sicoob-green) !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            padding: 0;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: white;
            font-weight: 600;
            padding: 0.8rem 1rem;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            background-color: var(--sicoob-green-light);
        }
        
        /* Content Styling */
        .main-content {
            padding-top: 30px;
            padding-bottom: 50px;
        }
        
        .page-header {
            background-color: var(--sicoob-yellow);
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 5px;
            color: var(--sicoob-green-dark);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            font-weight: 600;
            border-radius: 8px 8px 0 0 !important;
            padding: 0.75rem 1.25rem;
        }
        
        /* Button Styling */
        .btn-primary {
            background-color: var(--sicoob-green);
            border-color: var(--sicoob-green);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--sicoob-green-light);
            border-color: var(--sicoob-green-light);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .btn-warning {
            background-color: var(--sicoob-yellow);
            border-color: var(--sicoob-yellow);
            color: var(--sicoob-green-dark);
        }
        
        .btn-warning:hover {
            background-color: var(--sicoob-yellow-light);
            border-color: var(--sicoob-yellow-light);
            color: var(--sicoob-green-dark);
        }
        
        /* Badge Styling */
        .badge-warning {
            background-color: var(--sicoob-yellow);
            color: var(--sicoob-green-dark);
        }
        
        .badge-primary {
            background-color: var(--sicoob-green);
        }
        
        /* Kanban Board Styling */
        .kanban-board {
            min-height: calc(100vh - 200px);
        }
        
        .kanban-column {
            min-width: 300px;
            width: 300px;
        }
        
        .kanban-column-body {
            background-color: #f9f9f9;
        }
        
        .kanban-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid var(--sicoob-green);
        }
        
        .kanban-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        /* Status colors */
        .bg-pendente {
            background-color: var(--sicoob-yellow);
            color: var(--sicoob-green-dark);
        }
        
        .bg-em-andamento {
            background-color: var(--sicoob-green);
            color: white;
        }
        
        .bg-concluido {
            background-color: #28a745;
            color: white;
        }
        
        .bg-atrasado {
            background-color: #dc3545;
            color: white;
        }
        
        /* Footer Styling */
        .footer {
            background-color: var(--sicoob-green-dark);
            color: white;
            padding: 20px 0;
            margin-top: 30px;
        }
        
        .footer a {
            color: var(--sicoob-yellow);
        }
        
        /* Form elements */
        .form-control:focus {
            border-color: var(--sicoob-green);
            box-shadow: 0 0 0 0.2rem rgba(0, 99, 65, 0.25);
        }
        
        select.form-control {
            border-radius: 5px;
        }
        
        /* User dropdown */
        .user-dropdown .dropdown-toggle {
            color: white;
            padding: 0.5rem 1rem;
            font-weight: 600;
        }
        
        .user-dropdown .dropdown-toggle:hover {
            background-color: var(--sicoob-green-light);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .kanban-column {
                min-width: 100%;
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/sicoob.png" alt="Sicoob">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('kanban.index') }}">
                            <i class="fas fa-columns"></i> Kanban
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown user-dropdown">
                       
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user mr-2"></i> Perfil
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog mr-2"></i> Configurações
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sair
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid main-content">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} Sicoob - Sistema de Gestão de Contratos</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p>Cooperativa Financeira</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    
    <!-- Script para padronizar as cores dos status -->
    <script>
        // Script para converter cores padrão para cores Sicoob
        $(document).ready(function() {
            // Substituir classes de cor do Bootstrap por classes personalizadas do Sicoob
            $('.bg-warning').addClass('bg-pendente').removeClass('bg-warning');
            $('.bg-primary').addClass('bg-em-andamento').removeClass('bg-primary');
            $('.bg-success').addClass('bg-concluido').removeClass('bg-success');
            $('.bg-danger').addClass('bg-atrasado').removeClass('bg-danger');
            
            // Ajustar cores dos badges também
            $('.badge-warning').addClass('badge-pendente').removeClass('badge-warning');
            $('.badge-primary').addClass('badge-em-andamento').removeClass('badge-primary');
            $('.badge-success').addClass('badge-concluido').removeClass('badge-success');
            $('.badge-danger').addClass('badge-atrasado').removeClass('badge-danger');
        });
    </script>
    
    @yield('scripts')
</body>
</html>