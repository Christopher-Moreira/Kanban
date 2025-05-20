<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Contrato</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .contract-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .contract-item:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .contract-total {
            font-size: 1.2em;
            font-weight: bold;
            color: #1F6B2A;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #1F6B2A;
        }
        
        .bg-sicoob {
            background-color: #1F6B2A;
        }

        .info-section {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sicoob-heading {
            color: #1F6B2A;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .sicoob-divider {
            border-top: 2px solid #FFD100;
            margin: 1rem 0;
        }

        .financial-section { border-left: 4px solid #1F6B2A; }
        .delay-section { border-left: 4px solid #FFD100; }
        .action-section { border-left: 4px solid #6c757d; }
        .history-section { border-left: 4px solid #17a2b8; }
        .schedule-section { border-left: 4px solid #dc3545; }

        .badge-status {
            padding: 0.5em 1em;
            font-size: 0.9rem;
        }
        .badge-pendente { background-color: #FFD100; color: #1F6B2A; }
        .badge-em-andamento { background-color: #1F6B2A; color: white; }
        .badge-concluido { background-color: #28a745; color: white; }
        .badge-atrasado { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <a href="#" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar para o Kanban
                </a>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-sicoob text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-contract mr-2"></i>Detalhes do Contrato
                        </h4>
                        <span class="badge badge-status badge-atrasado">Atrasado</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Seção Cliente -->
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-user mr-2"></i>Informações do Cliente
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <div class="info-item mb-3">
                                        <strong>Cliente:</strong> 
                                        <span class="text-success">João da Silva Ltda</span>
                                    </div>
                                    <div class="info-item mb-3">
                                        <strong>CPF/CNPJ:</strong> 
                                        <span class="text-primary">12.345.678/0001-99</span>
                                    </div>
                                    <div class="info-item mb-3">
                                        <strong>Anotações do Contato:</strong>
                                        <textarea class="form-control mt-2" rows="3" 
                                            placeholder="Cliente preferiu ser contactado após as 14h"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Seção Contrato  -->
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-file-signature mr-2"></i>Informações do Contrato    <- Vincular ao ID contrato each Contrato
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <div class="info-item mb-3">
                                        <strong>Contrato:</strong> 
                                        <span>#2023-04567</span>
                                    </div>
                                    <div class="info-item mb-3">
                                        <strong>Produto:</strong> 
                                        <span>Crédito Consignado</span>
                                    </div>
                                    <div class="info-item mb-3">
                                        <strong>Valor do Contrato:</strong>
                                        <span class="text-info font-weight-bold">R$ 385.350,00</span>
                                    </div>
                                    
                                    <!-- Seção de Parcelas -->
                                    <div class="parcelas-container mt-4">
                                        <h6 class="sicoob-heading">
                                            <i class="fas fa-calendar-alt mr-2"></i>Parcelas do Contrato
                                        </h6>
                                        <div id="parcelasList"></div>
                                        <div id="parcelasTotal" class="contract-total text-end"></div>
                                    </div>
                                    
                                    <div class="info-item mb-3">
                                        <strong>Parcela em Atraso:</strong>
                                        <span class="text-danger font-weight-bold">R$ 2.450,00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção Financeira -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="info-section financial-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-money-bill-wave mr-2"></i>Informações Financeiras
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <div class="info-item mb-3">
                                        <strong>Saldo Devedor Contábil:</strong> 
                                        <span class="text-success font-weight-bold">R$ 150.000,00</span>
                                    </div>
                                    <div class="info-item mb-3">
                                        <strong>Saldo Devedor Crédito:</strong> 
                                        <span class="text-primary">R$ 145.250,00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Seção Atrasos -->
                            <div class="col-md-6">
                                <div class="info-section delay-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-calendar-times mr-2"></i>Informações de Atraso
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <div class="info-item mb-3">
                                        <strong>Dias de Atraso Parcela:</strong> 
                                        <span class="text-danger font-weight-bold">
                                            45 dias
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção Agendamento -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="info-section schedule-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-clock mr-2"></i>Agendar Despertador
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <div class="form-group">
                                        <label>Data/Hora do Lembrete:</label>
                                        <input type="datetime-local" 
                                               class="form-control"
                                               value="2023-11-15T14:30">
                                    </div>
                                    <button class="btn btn-warning mt-2" onclick="showAlert()">
                                        <i class="fas fa-bell mr-2"></i>Configurar Lembrete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Seção Ações -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="info-section action-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-tasks mr-2"></i>Ações
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <form id="statusForm" class="d-flex align-items-center flex-wrap">
                                        <div class="form-group mr-3 flex-grow-1" style="max-width: 300px;">
                                            <label for="status">Atualizar Status:</label>
                                            <select class="form-control" id="status">
                                                <option>Pendente</option>
                                                <option selected>Atrasado</option>
                                                <option>Em Andamento</option>
                                                <option>Concluído</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-4" onclick="updateStatus()">
                                            <i class="fas fa-save mr-2"></i>Atualizar Status
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Seção Histórico -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="info-section history-section">
                                    <h5 class="sicoob-heading">
                                        <i class="fas fa-history mr-2"></i>Histórico de Ações
                                    </h5>
                                    <hr class="sicoob-divider">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Usuário</th>
                                                    <th>Ação</th>
                                                    <th>Status Anterior</th>
                                                    <th>Status Atual</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>15/10/2023 14:30</td>
                                                    <td>Joana Silva</td>
                                                    <td>Atualização de status</td>
                                                    <td>Pendente</td>
                                                    <td>Atrasado</td>
                                                </tr>
                                                <tr>
                                                    <td>10/10/2023 09:15</td>
                                                    <td>Carlos Andrade</td>
                                                    <td>Criação do contrato</td>
                                                    <td>-</td>
                                                    <td>Pendente</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Dados das parcelas
        const parcelas = [
            { numero: 'Parcela 1', valor: 61337.5 },
            { numero: 'Parcela 2', valor: 80337.5 },
            { numero: 'Parcela 3', valor: 121337.5 },
            { numero: 'Parcela 4', valor: 311337.5 }
        ];

        // Função para exibir as parcelas
        function exibirParcelas() {
            const total = parcelas.reduce((acc, curr) => acc + curr.valor, 0);
            const totalFormatado = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(total);

            const parcelasList = document.getElementById('parcelasList');
            parcelasList.innerHTML = parcelas.map(ct => `
                <div class="contract-item">
                    <span>${ct.numero}</span>
                    <span>${ct.valor.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})}</span>
                </div>
            `).join('');

            document.getElementById('parcelasTotal').textContent = `Total: ${totalFormatado}`;
        }

        // Executa ao carregar a página
        window.onload = exibirParcelas;

        function showAlert() {
            const datetime = document.querySelector('input[type="datetime-local"]').value;
            alert(`Lembrete configurado para: ${new Date(datetime).toLocaleString()}`);
        }

        function updateStatus() {
            const newStatus = document.getElementById('status').value;
            const statusBadge = document.querySelector('.badge-status');
            
            // Atualiza o badge
            statusBadge.className = `badge badge-status badge-${newStatus.toLowerCase().replace(' ', '')}`;
            statusBadge.textContent = newStatus;
            
            alert(`Status atualizado para: ${newStatus}`);
        }
    </script>
</body>
</html>