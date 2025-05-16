<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanban de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            font-weight: bold;
        }
        .saldo-devedor {
            font-size: 1.2rem;
            color: #dc3545;
            font-weight: bold;
        }
        .cliente-info {
            margin-bottom: 10px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .kanban-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="my-4 text-center">Kanban de Clientes</h1>
        
        <div class="kanban-container">
            @foreach($dados as $item)
                <div class="card">
                    <div class="card-header">
                        {{ $item->cliente ?? 'Cliente não informado' }}
                    </div>
                    <div class="card-body">
                        <div class="cliente-info">
                            <strong>CPF/CNPJ:</strong> {{ $item->cpf_cnpj ?? 'Não informado' }}<br>
                            <strong>Contrato:</strong> {{ $item->contrato ?? 'Não informado' }}
                        </div>
                        
                        <div class="mb-3">
                            <span class="saldo-devedor">
                                Saldo Devedor: R$ {{ number_format($item->saldo_devedor_cont ?? 0, 2, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Dias em atraso:</strong> {{ $item->dias_atraso_parcela ?? 0 }}
                        </div>
                        
                        <div class="mb-2">
                            <strong>Produto:</strong> {{ $item->mod_produto ?? 'Não informado' }}
                        </div>
                        
                        <div class="mb-2">
                            <strong>R:</strong> {{ $item->R ?? 'Não informado' }}
                        </div>
                        
                        <div>
                            @if(isset($item->status))
                                <span class="status-badge" style="background-color: {{ $item->status === 'ativo' ? '#28a745' : '#6c757d' }}; color: white;">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        Atualizado em: {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>