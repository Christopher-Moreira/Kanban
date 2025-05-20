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
                                <!-- Cabeçalho do Card -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-success mb-0">{{ $card->cliente }}</h5>
                                    @if(isset($card->tipo) && $card->tipo == 'ativo problematico')
                                        <span class="ap-tag">AP</span>
                                    @endif
                                </div>
                                
                                <!-- Detalhes do Contrato -->
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <strong>Contrato:</strong>   
                                    <i class="fas fa-file-contract mr-1"></i>{{ $card->contrato }}
                                </h6>
                                
                                <!-- Informações Financeiras -->
                                <div class="kanban-card-details">
                                    <p class="mb-1">
                                        <i class="fas fa-id-card mr-1"></i>
                                        <strong>CPF/CNPJ:</strong> {{ formatCpfCnpj($card->cpf_cnpj) }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-calendar-times mr-1"></i>
                                        <strong>Notificação:</strong> 
                                        <!-- ... (existing status indicators) ... -->
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                        <strong>Saldo Devedor:</strong> 
                                        <span class="text-primary">R$ {{ number_format($card->saldo_devedor_cont, 2, ',', '.') }}</span>
                                    </p>
                                </div>

                                <!-- Seção de Lembretes -->
                                <div class="reminders-section mt-3">
                                    <h6><i class="fas fa-bell mr-2"></i>Lembretes</h6>
                                    @foreach($card->reminders as $reminder)
                                        <div class="reminder-item alert alert-info p-2 mb-2">
                                            <div class="d-flex justify-content-between">
                                                <small>{{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d/m/Y H:i') }}</small>
                                                <div class="actions">
                                                    <form class="d-inline" method="POST" action="{{ route('reminders.destroy', $reminder) }}">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <p class="mb-0">{{ $reminder->notes }}</p>
                                        </div>
                                    @endforeach
                                    
                                    <button class="btn btn-sm btn-light btn-block add-reminder" 
                                            data-toggle="modal" 
                                            data-target="#reminderModal"
                                            data-contract-id="{{ $card->id }}">
                                        <i class="fas fa-plus-circle"></i> Novo Lembrete
                                    </button>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="mt-3 d-flex justify-content-between">
                                    <a href="{{route('kanban.show', $card->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye mr-1"></i>Detalhes
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-exchange-alt mr-1"></i>Mover
                                        </button>
                                        <!-- ... (existing dropdown menu) ... -->
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

<!-- Modal de Lembretes -->
<div class="modal fade" id="reminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('reminders.store') }}">
                @csrf
                <input type="hidden" name="contract_id" id="contract_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Novo Lembrete</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Data/Hora</label>
                        <input type="datetime-local" name="reminder_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea name="notes" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
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
        position: relative;
    }
    
    .kanban-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Estilos para Lembretes */
    .reminders-section {
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    .reminder-item {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 8px;
        font-size: 0.9em;
    }

    .reminder-item:hover {
        background-color: #e9ecef;
    }w

    .add-reminder {
        transition: all 0.3s ease;
    }

    .add-reminder:hover {
        transform: translateY(-2px);
    }
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
        position: relative;
    }
    
    .kanban-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Estilo para 31 dias de atraso - círculo laranja */
    .atraso-31-dias {
        display: inline-block;
        background-color: transparent;
        color: #000;
        font-weight: bold;
        position: relative;
        z-index: 1;
    }
    
    .atraso-31-dias:after {
        content: '';
        position: absolute;
        width: 26px;
        height: 26px;
        background-color: #FF8C00; /* Laranja */
        border-radius: 50%;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: -1;
        opacity: 0.7;
    }
    
    /* Estilo para 90-120 dias de atraso - fundo vermelho */
    .atraso-90-120-dias {
        display: inline-block;
        background-color: #dc3545; /* Vermelho */
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
    }
    
    /* Estilo para 90 dias exatos */
    .atraso-90-dias {
        font-weight: bold;
        color: #dc3545;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .atraso-alert {
        animation: rotate 1s infinite;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(10deg); }
        75% { transform: rotate(-10deg); }
        100% { transform: rotate(0deg); }
    }
    
    /* Pop-up para 90 dias de atraso */
    .atraso-popup {
        position: absolute;
        top: -10px;
        right: -10px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10;
        animation: pulse 1.5s infinite;
    }
    
    .atraso-popup:after {
        content: '!';
        font-weight: bold;
    }
    
    .atraso-popup .atraso-popup-content {
        display: none;
        position: absolute;
        top: 25px;
        right: 0;
        background-color: #dc3545;
        padding: 10px;
        border-radius: 5px;
        width: 200px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 20;
    }
    
    .atraso-popup:hover .atraso-popup-content {
        display: block;
    }
    
    /* Estilo para tag AP (Ativo Problemático) */
    .ap-tag {
        background-color: #6c2dc7; /* Roxo vibrante */
        color: white;
        font-weight: bold;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 4px;
        display: inline-block;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        position: relative;
    }
    
    .ap-tag:after {
        content: 'Ativo Problemático';
        position: absolute;
        background: #6c2dc7;
        color: white;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 12px;
        top: -5px;
        right: -5px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        white-space: nowrap;
        z-index: 100;
    }
    
    .ap-tag:hover:after {
        opacity: 1;
        visibility: visible;
        transform: translateX(-100%);
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
    .bg-notificação{
        background-color:rgb(225, 104, 23);
        color: white;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Configuração inicial
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inicialização do Modal de Lembretes
        $('#reminderModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var contractId = button.data('contract-id');
            $(this).find('#contract_id').val(contractId);
        });

        // Submissão do Formulário de Lembrete (AJAX)
        $('#reminderModal form').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var formData = form.serialize();
            var url = form.attr('action');
            
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
                },
                success: function(response) {
                    $('#reminderModal').modal('hide');
                    form.trigger("reset");
                    showNotification('Sucesso!', 'Lembrete adicionado com sucesso', 'success');
                    updateRemindersList(response.reminder, response.contract_id);
                },
                error: function(xhr) {
                    let errorMessage = 'Erro ao salvar lembrete';
                    if(xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                    }
                    showNotification('Erro!', errorMessage, 'danger');
                },
                complete: function() {
                    form.find('button[type="submit"]').prop('disabled', false)
                        .html('Salvar');
                }
            });
        });

        // Exclusão de Lembrete (AJAX)
        $(document).on('submit', '.delete-reminder-form', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var url = form.attr('action');
            var reminderItem = form.closest('.reminder-item');

            if(confirm('Tem certeza que deseja excluir este lembrete?')) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    success: function(response) {
                        reminderItem.fadeOut(300, function() {
                            $(this).remove();
                            showNotification('Sucesso!', 'Lembrete removido', 'success');
                        });
                    },
                    error: function(xhr) {
                        showNotification('Erro!', 'Falha ao excluir lembrete', 'danger');
                    }
                });
            }
        });

        // Atualização Dinâmica da Lista de Lembretes
        function updateRemindersList(reminder, contractId) {
            var reminderHtml = `
                <div class="reminder-item alert alert-info p-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <small>${formatDateTime(reminder.reminder_date)}</small>
                        <div class="actions">
                            <form class="d-inline delete-reminder-form" 
                                  method="POST" 
                                  action="/reminders/${reminder.id}">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="mb-0">${reminder.notes}</p>
                </div>
            `;

            $(`.kanban-card[data-id="${contractId}"] .reminders-section`)
                .prepend(reminderHtml)
                .find('.text-muted').remove();
        }

        // Formatação de Data/Hora
        function formatDateTime(datetime) {
            const date = new Date(datetime);
            return date.toLocaleDateString('pt-BR') + ' ' + 
                   date.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
        }

        // Sistema de Notificação
        function showNotification(title, message, type) {
            const notificationHtml = `
                <div class="notification alert alert-${type} alert-dismissible fade show" 
                     role="alert" 
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <strong>${title}</strong> ${message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            `;

            $('body').append(notificationHtml);
            
            setTimeout(() => {
                $('.notification').alert('close');
            }, 5000);
        }

        // Atualização Automática de Lembretes (Polling)
        function checkForUpdates() {
            $('.kanban-card').each(function() {
                const cardId = $(this).data('id');
                const lastUpdate = $(this).data('last-update') || 0;

                $.get(`/reminders/check-updates/${cardId}?last_update=${lastUpdate}`, function(response) {
                    if(response.updated) {
                        $(this).data('last-update', response.timestamp);
                        // Atualizar a lista de lembretes
                        $(this).find('.reminders-section').html(response.html);
                    }
                }.bind(this));
            });

            setTimeout(checkForUpdates, 300000); // Atualiza a cada 5 minutos
        }

        // Iniciar polling
        checkForUpdates();
    });
</script>
@endsection