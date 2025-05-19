@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{route('kanban.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left mr-2"></i> Voltar para o Kanban
            </a>
            
            <div class="card shadow-sm">
                <div class="card-header bg-sicoob text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-contract mr-2"></i>Detalhes do Contrato
                    </h4>
                    <span class="badge badge-{{ getStatusClassName($card->status) }} badge-pill py-2 px-3">{{ $card->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h5 class="sicoob-heading">
                                    <i class="fas fa-user mr-2"></i>Informações do Cliente
                                </h5>
                                <hr class="sicoob-divider">
                                <div class="info-item mb-3">
                                    <strong>Cliente:</strong> 
                                    <span class="text-success">{{ $card->cliente }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>CPF/CNPJ:</strong> 
                                    <span class="text-primary">{{ formatCpfCnpj($card->cpf_cnpj) }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Status Atual:</strong> 
                                    <span class="badge badge-{{ getStatusClassName($card->status) }}">{{ $card->status }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h5 class="sicoob-heading">
                                    <i class="fas fa-file-signature mr-2"></i>Informações do Contrato
                                </h5>
                                <hr class="sicoob-divider">
                                <div class="info-item mb-3">
                                    <strong>Contrato:</strong> 
                                    <span>{{ $card->contrato }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Produto:</strong> 
                                    <span>{{ $card->mod_produto }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Nível Classificação:</strong> 
                                    <span>{{ $card->nivel_clas }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="info-section financial-section">
                                <h5 class="sicoob-heading">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Informações Financeiras
                                </h5>
                                <hr class="sicoob-divider">
                                <div class="info-item mb-3">
                                    <strong>Saldo Devedor Contábil:</strong> 
                                    <span class="text-success font-weight-bold">R$ {{ number_format($card->saldo_devedor_cont, 2, ',', '.') }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Saldo Devedor Crédito:</strong> 
                                    <span class="text-primary">R$ {{ number_format($card->saldo_devedor_cred, 2, ',', '.') }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Saldo AD CC:</strong> 
                                    <span>R$ {{ number_format($card->saldo_ad_cc, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section delay-section">
                                <h5 class="sicoob-heading">
                                    <i class="fas fa-calendar-times mr-2"></i>Informações de Atraso
                                </h5>
                                <hr class="sicoob-divider">
                                <div class="info-item mb-3">
                                    <strong>Dias de Atraso Parcela:</strong> 
                                    <span class="{{ $card->dias_atraso_parcela > 30 ? 'text-danger' : 'text-warning' }} font-weight-bold">
                                        {{ $card->dias_atraso_parcela }} dias
                                    </span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Dias de Atraso até Fim do Mês:</strong> 
                                    <span>{{ $card->dias_atraso_a_fin_mes }} dias</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>PA:</strong> 
                                    <span>{{ $card->pa }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Transição:</strong> 
                                    <span>{{ $card->transic }}</span>
                                </div>
                                <div class="info-item mb-3">
                                    <strong>Letra R:</strong> 
                                    <span>{{ $card->R }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="info-section action-section">
                                <h5 class="sicoob-heading">
                                    <i class="fas fa-tasks mr-2"></i>Ações
                                </h5>
                                <hr class="sicoob-divider">
                                <form id="update-status-form" class="d-flex align-items-center flex-wrap">
                                    @csrf
                                    <div class="form-group mr-3 flex-grow-1" style="max-width: 300px;">
                                        <label for="status">Atualizar Status:</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="Pendente" {{ $card->status == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                            <option value="Em Andamento" {{ $card->status == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                                            <option value="Concluído" {{ $card->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                                            <option value="Atrasado" {{ $card->status == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">
                                        <i class="fas fa-save mr-2"></i>Atualizar Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
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
                                            <!-- Aqui entraria o histórico de alterações do contrato -->
                                            <tr>
                                                <td>{{ date('d/m/Y H:i') }}</td>
                                                <td>Sistema</td>
                                                <td>Visualização</td>
                                                <td>{{ $card->status }}</td>
                                                <td>{{ $card->status }}</td>
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
@endsection

@section('styles')
<style>
    /* Estilos específicos para página de detalhes */
    .bg-sicoob {
        background-color: var(--sicoob-green);
    }
    
    .info-section {
        margin-bottom: 15px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .sicoob-heading {
        color: var(--sicoob-green);
        font-weight: 600;
    }
    
    .sicoob-divider {
        border-top: 2px solid var(--sicoob-yellow);
        margin-top: 10px;
        margin-bottom: 15px;
        width: 100%;
    }
    
    .info-item {
        padding: 8px 0;
    }
    
    .financial-section {
        border-left: 4px solid var(--sicoob-green);
    }
    
    .delay-section {
        border-left: 4px solid var(--sicoob-yellow);
    }
    
    .action-section {
        border-left: 4px solid #6c757d;
    }
    
    .history-section {
        border-left: 4px solid #17a2b8;
    }
    
    /* Status badges */
    .badge-pendente {
        background-color: var(--sicoob-yellow);
        color: var(--sicoob-green-dark);
    }
    
    .badge-em-andamento {
        background-color: var(--sicoob-green);
        color: white;
    }
    
    .badge-concluido {
        background-color: #28a745;
        color: white;
    }
    
    .badge-atrasado {
        background-color: #dc3545;
        color: white;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#update-status-form').on('submit', function(e) {
            e.preventDefault();
            
            var newStatus = $('#status').val();
            
            // Desabilitar o botão durante o envio
            var submitBtn = $(this).find('button[type="submit"]');
            var originalBtnText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Processando...');
            submitBtn.prop('disabled', true);
            
            $.ajax({
                url: '{{route("kanban.update") }}',
                method: 'POST',
                data: {
                    _token: '{{csrf_token() }}',
                    card_id: {{ $card->id }},
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Notificação de sucesso
                        showNotification('Sucesso!', 'Status atualizado com sucesso!', 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(error) {
                    console.error('Erro ao atualizar status:', error);
                    showNotification('Erro', 'Erro ao atualizar o status. Tente novamente.', 'danger');
                    
                    // Restaurar o botão
                    submitBtn.html(originalBtnText);
                    submitBtn.prop('disabled', false);
                }
            });
        });
        
        // Função para mostrar notificações
        function showNotification(title, message, type) {
            var notificationHtml = `
                <div class="notification alert alert-${type} alert-dismissible fade show" role="alert" 
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <strong>${title}</strong> ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            
            $('body').append(notificationHtml);
            
            // Auto-fechar após 5 segundos
            setTimeout(function() {
                $('.notification').alert('close');
            }, 5000);
        }
    });
</script>
@endsection

@php
function getStatusClassName($status) {
    switch ($status) {
        case 'Pendente':
            return 'pendente';
        case 'Em Andamento':
            return 'em-andamento';
        case 'Concluído':
            return 'concluido';
        case 'Atrasado':
            return 'atrasado';
        default:
            return 'secondary';
    }
}

function formatCpfCnpj($cpfCnpj) {
    $cpfCnpj = preg_replace('/[^0-9]/', '', $cpfCnpj);
    
    if (strlen($cpfCnpj) === 11) {
        return substr($cpfCnpj, 0, 3) . '.' . 
               substr($cpfCnpj, 3, 3) . '.' . 
               substr($cpfCnpj, 6, 3) . '-' . 
               substr($cpfCnpj, 9, 2);
    } else {
        return substr($cpfCnpj, 0, 2) . '.' . 
               substr($cpfCnpj, 2, 3) . '.' . 
               substr($cpfCnpj, 5, 3) . '/' . 
               substr($cpfCnpj, 8, 4) . '-' . 
               substr($cpfCnpj, 12, 2);
    }
}
@endphp