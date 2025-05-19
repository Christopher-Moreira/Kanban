<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicoob - Importação de Dados Financeiros</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Cores oficiais do Sicoob */
        :root {
            --sicoob-primary: #0033a0;
            --sicoob-secondary: #00a3e0;
            --sicoob-accent: #ff8200;
            --sicoob-light: #f5f7fa;
            --sicoob-dark: #2d3e50;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sicoob-container {
            padding: 2rem 0;
            min-height: 100vh;
        }

        .sicoob-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 51, 160, 0.1);
            border: none;
        }

        .sicoob-card-header {
            background-color: #7EBD01;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .sicoob-logo {
            height: 40px;
        }

        .sicoob-alert {
            border-radius: 8px;
            border-left: 4px solid var(--sicoob-primary);
        }

        .sicoob-section-title {
            color: #7EBD01;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .sicoob-instructions {
            background-color: var(--sicoob-light);
            border-radius: 8px;
            padding: 1.25rem;
            border-left: 4px solid #7EBD01;
        }

        .btn-sicoob-primary {
            background-color: var(--sicoob-primary);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-sicoob-primary:hover {
            background-color: #7EBD01;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 51, 160, 0.2);
        }

        .btn-sicoob-secondary {
            background-color: white;
            color: var(--sicoob-primary);
            border: 1px solid var(--sicoob-primary);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-sicoob-secondary:hover {
            background-color: var(--sicoob-light);
            color: #7EBD01;
            transform: translateY(-2px);
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
        }

        .file-upload-area:hover {
            border-color: #7EBD01;
            background-color: rgba(0, 163, 224, 0.05);
        }

        .form-control:focus {
            border-color: var(--sicoob-secondary);
            box-shadow: 0 0 0 0.25rem rgba(0, 163, 224, 0.25);
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .drag-active {
            border-color: var(--sicoob-secondary);
            background-color: rgba(0, 163, 224, 0.1);
        }
    </style>
</head>
<body>
<div class="container-fluid sicoob-container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card sicoob-card">
                <div class="card-header sicoob-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-import me-2"></i>Importar Dados Financeiros
                        </h4>
                        <img src= "images/sicoob.png" alt="Sicoob" class="sicoob-logo">
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success sicoob-alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger sicoob-alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($failures) && $failures->isNotEmpty())
                        <div class="alert alert-warning sicoob-alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Algumas linhas não puderam ser importadas. Verifique os erros abaixo:
                            
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="collapse" data-bs-target="#errorCollapse">
                                    Mostrar detalhes dos erros
                                </button>
                                
                                <div class="collapse mt-2" id="errorCollapse">
                                    <div class="card card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Linha</th>
                                                        <th>Campo</th>
                                                        <th>Erro</th>
                                                        <th>Valor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($failures as $failure)
                                                        <tr>
                                                            <td>{{ $failure->row() }}</td>
                                                            <td>{{ $failure->attribute() }}</td>
                                                            <td>
                                                                @foreach($failure->errors() as $error)
                                                                    {{ $error }}
                                                                @endforeach
                                                            </td>
                                                            <td>{{ $failure->values()[$failure->attribute()] ?? '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="sicoob-form">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="sicoob-section-title">
                                <i class="fas fa-info-circle me-2"></i>Instruções
                            </h5>
                            <div class="sicoob-instructions">
                                <ol>
                                    <li>O arquivo deve estar nos formatos XLSX, XLS ou CSV</li>
      
                                    <li>Verifique se o arquivo contém os cabeçalhos corretos</li>
                                    
                                </ol>
                                
                                
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="sicoob-section-title">
                                <i class="fas fa-upload me-2"></i>Selecionar Arquivo
                            </h5>
                            
                            <div class="file-upload-area" id="fileUploadArea">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="fileInput" name="file" accept=".xlsx,.xls,.csv" required>
                                    <button class="btn btn-sicoob-primary" type="button" id="clearFile">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-text">Arraste e solte o arquivo aqui ou clique para selecionar</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-sicoob-secondary me-md-2">
                                <i class="fas fa-undo me-2"></i>Limpar
                            </button>
                            <button type="submit" class="btn btn-sicoob-primary">
                                <i class="fas fa-file-import me-2"></i>Importar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos DOM
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('fileInput');
        const clearFileBtn = document.getElementById('clearFile');
        
        // Limpar seleção de arquivo
        clearFileBtn.addEventListener('click', function() {
            fileInput.value = '';
        });

        // Drag and drop functions
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            fileUploadArea.classList.add('drag-active');
        }

        function unhighlight() {
            fileUploadArea.classList.remove('drag-active');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                fileInput.files = files;
            }
        }

        // Event listeners para drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });

        fileUploadArea.addEventListener('drop', handleDrop, false);

        // Mostrar nome do arquivo selecionado
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                const fileInfo = document.createElement('div');
                fileInfo.className = 'mt-2 text-start text-success';
                fileInfo.innerHTML = `<i class="fas fa-file me-2"></i>${fileName}`;
                
                // Remove info anterior se existir
                const oldInfo = fileUploadArea.querySelector('.file-info');
                if (oldInfo) oldInfo.remove();
                
                fileInfo.classList.add('file-info');
                fileUploadArea.appendChild(fileInfo);
            }
        });
    });
</script>
</body>
</html>