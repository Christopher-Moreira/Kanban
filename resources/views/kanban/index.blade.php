@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 page-header">
            <h2 class="text-center mb-0">
                <i class="fas fa-columns mr-2"></i>Kanban de Contratos
            </h2>
        </div>
    </div>

    <div class="kanban-board d-flex flex-nowrap overflow-auto">
        @foreach($statusColumns as $status)
        <div class="kanban-column mx-2" data-status="{{ $status }}">
            <div class="card">
                <div class="card-header bg-{{ getStatusClassName($status) }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $status }}</h5>
                        <span class="badge badge-light">{{ isset($cards[$status]) ? count($cards[$status]) : 0 }}</span>
                    </div>
                </div>
                <div class="card-body kanban-column-body" style="height: calc(100vh - 230px); overflow-y: auto;">
                    @if(isset($cards[$status]))
                        @foreach($cards[$status] as $card)
                        <div class="kanban-card card mb-3" data-id="{{ $card->id }}">
                            <div class="card-body">
                                <h5 class="card-title text-success">{{ $card->cliente }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fas fa-file-contract mr-1"></i>{{ $card->contrato }}
                                </h6>
                                <div class="kanban-card-details">
                                    <p class="mb-1">
                                        <i class="fas fa-id-card mr-1"></i>
                                        <strong>CPF/CNPJ:</strong> {{ formatCpfCnpj($card->cpf_cnpj) }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-calendar-times mr-1"></i>
                                        <strong>Notificação:</strong> 
                                        <span class="{{ $card->dias_atraso_parcela > 30 ? 'text-danger' : 'text-warning' }}">
                                            {{ $card->dias_atraso_parcela }} dias
                                        </span>
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        <strong>Saldo Devedor:</strong> 
                                        <span class="text-primary">R$ {{ number_format($card->saldo_devedor_cont, 2, ',', '.') }}</span>
                                    </p>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <a href="{{route('kanban.show', $card->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye mr-1"></i>Detalhes
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-exchange-alt mr-1"></i>Mover
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @foreach($statusColumns as $moveStatus)
                                                @if($moveStatus != $status)
                                                <a class="dropdown-item move-card" href="#" 
                                                   data-card-id="{{ $card->id }}" 
                                                   data-status="{{ $moveStatus }}">
                                                    Para {{ $moveStatus }}
                                                </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                            <p>Nenhum contrato nesta coluna</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
    .kanban-board {
        min-height: calc(100vh - 230px);
    }
    
    .kanban-column {
        min-width: 300px;
        width: 300px;
    }
    
    .kanban-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid var(--sicoob-green);
    }
    
    .kanban-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Status específicos para cores Sicoob */
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
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Estiliza os cartões com base no status
        $('.kanban-card').each(function() {
            var status = $(this).closest('.kanban-column').data('status');
            if(status === 'Pendente') {
                $(this).css('border-left-color', 'var(--sicoob-yellow)');
            } else if(status === 'Em Andamento') {
                $(this).css('border-left-color', 'var(--sicoob-green)');
            } else if(status === 'Concluído') {
                $(this).css('border-left-color', '#28a745');
            } else if(status === 'Atrasado') {
                $(this).css('border-left-color', '#dc3545');
            }
        });
        
        $('.move-card').on('click', function(e) {
            e.preventDefault();
            
            var cardId = $(this).data('card-id');
            var newStatus = $(this).data('status');
            
            // Mostra um indicador de carregamento
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Movendo...');
            
            $.ajax({
                url: '{{route("kanban.update") }}',
                method: 'POST',
                data: {
                    _token: '{{csrf_token() }}',
                    card_id: cardId,
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Notificação de sucesso
                        showNotification('Sucesso!', 'Cartão movido com sucesso.', 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(error) {
                    console.error('Erro ao mover cartão:', error);
                    showNotification('Erro', 'Erro ao mover o cartão. Tente novamente.', 'danger');
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