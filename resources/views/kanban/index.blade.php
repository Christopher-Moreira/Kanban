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
                        <h5 class="mb-0">
                            @if($status == 'Lembretes')
                                <i class="fas fa-bell mr-2"></i>{{ $status }}
                            @else
                                {{ $status }}
                            @endif
                        </h5>
                        <span class="badge badge-light">
                            @if($status == 'Lembretes')
                                {{ count($allReminders) }}
                            @else
                                {{ isset($cards[$status]) ? count($cards[$status]) : 0 }}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body kanban-column-body" style="height: calc(100vh - 230px); overflow-y: auto;">
                    
                    @if($status == 'Lembretes')
                        <!-- Coluna de Lembretes -->
                        @if(count($allReminders) > 0)
                            <!-- Lembretes Vencidos -->
                            @if(count($remindersByUrgency['vencidos']) > 0)
                                <div class="reminder-category mb-3">
                                    <h6 class="text-danger mb-2">
                                        <i class="fas fa-exclamation-triangle"></i> Vencidos ({{ count($remindersByUrgency['vencidos']) }})
                                    </h6>
                                    @foreach($remindersByUrgency['vencidos'] as $reminder)
                                        @include('partials.reminder-card', ['reminder' => $reminder])
                                    @endforeach
                                </div>
                            @endif

                            <!-- Lembretes Próximos -->
                            @if(count($remindersByUrgency['proximos']) > 0)
                                <div class="reminder-category mb-3">
                                    <h6 class="text-warning mb-2">
                                        <i class="fas fa-clock"></i> Próximos ({{ count($remindersByUrgency['proximos']) }})
                                    </h6>
                                    @foreach($remindersByUrgency['proximos'] as $reminder)
                                        @include('partials.reminder-card', ['reminder' => $reminder])
                                    @endforeach
                                </div>
                            @endif

                            <!-- Lembretes Normais -->
                            @if(count($remindersByUrgency['normais']) > 0)
                                <div class="reminder-category mb-3">
                                    <h6 class="text-info mb-2">
                                        <i class="fas fa-calendar-check"></i> Agendados ({{ count($remindersByUrgency['normais']) }})
                                    </h6>
                                    @foreach($remindersByUrgency['normais'] as $reminder)
                                        @include('partials.reminder-card', ['reminder' => $reminder])
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-3"></i>
                                <p>Nenhum lembrete ativo</p>
                            </div>
                        @endif
                        
                    @else
                        <!-- Colunas Normais de Contratos -->
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

<!-- Modal de Marcar como Concluído -->
<div class="modal fade" id="completeReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="completeReminderForm" action="">
                @csrf
                @method('PATCH')
                
                <div class="modal-header">
                    <h5 class="modal-title">Marcar Lembrete como Concluído</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Deseja marcar este lembrete como concluído?</p>
                    <div class="reminder-details"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Marcar como Concluído</button>
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
    }

    .add-reminder {
        transition: all 0.3s ease;
    }

    .add-reminder:hover {
        transform: translateY(-2px);
    }

    /* Estilos para a Coluna de Lembretes */
    .reminder-category {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 15px;
    }

    .reminder-card {
        border-radius: 8px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .reminder-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Cards de Lembretes por Urgência */
    .reminder-vencido {
        border-left: 4px solid #dc3545;
        background: linear-gradient(90deg, #ffebee 0%, #ffffff 100%);
        animation: pulse-red 2s infinite;
    }

    .reminder-proximo {
        border-left: 4px solid #ffc107;
        background: linear-gradient(90deg, #fff8e1 0%, #ffffff 100%);
        animation: pulse-yellow 3s infinite;
    }

    .reminder-normal {
        border-left: 4px solid #17a2b8;
        background: linear-gradient(90deg, #e1f5fe 0%, #ffffff 100%);
    }

    @keyframes pulse-red {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    @keyframes pulse-yellow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.9; }
    }

    /* Badges de Urgência */
    .urgency-badge {
        font-size: 10px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .urgency-vencido {
        background-color: #dc3545;
        color: white;
    }

    .urgency-proximo {
        background-color: #ffc107;
        color: #212529;
    }

    .urgency-normal {
        background-color: #17a2b8;
        color: white;
    }

    /* Indicadores de Tempo */
    .time-indicator {
        font-size: 11px;
        color: #6c757d;
        font-weight: 500;
    }

    .time-vencido {
        color: #dc3545;
        font-weight: bold;
    }

    .time-proximo {
        color: #e67e22;
        font-weight: bold;
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

    .bg-lembretes {
        background-color: #6f42c1;
        color: white;
    }

    /* Demais estilos originais */
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
        background-color: #FF8C00;
        border-radius: 50%;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: -1;
        opacity: 0.7;
    }
    
    .atraso-90-120-dias {
        display: inline-block;
        background-color: #dc3545;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
    }
    
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
    
    .ap-tag {
        background-color: #6c2dc7;
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
</style>
@endsection

@section('scripts')
@section('scripts')
<script>
    $(document).ready(function() {
        // Configurações globais
        const config = {
            updateInterval: 300000, // 5 minutos para polling
            pageReloadInterval: 1800000 // 30 minutos para recarregar a página
        };

        // Inicialização do sistema
        function init() {
            setupModals();
            setupEventListeners();
            startPolling();
            checkReminderAlerts();
        }

        // Configuração de modais
        function setupModals() {
            // Modal de novo lembrete
            $('#reminderModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                $('#contract_id').val(button.data('contract-id'));
            });

            // Modal de concluir lembrete
            $(document).on('click', '.complete-reminder', function(e) {
                e.preventDefault();
                const reminderData = $(this).data();
                
                $('#completeReminderForm').attr('action', `/reminders/${reminderData.reminderId}/complete`);
                $('.reminder-details').html(`
                    <div class="alert alert-info">
                        <strong>Data:</strong> ${reminderData.reminderDate}<br>
                        <strong>Observação:</strong> ${reminderData.reminderText}
                    </div>
                `);
                
                $('#completeReminderModal').modal('show');
            });
        }

        // Configuração de listeners de eventos
        function setupEventListeners() {
            // Submissão de formulário de lembrete
            $('#reminderModal form').submit(handleReminderSubmit);
            
            // Submissão de conclusão de lembrete
            $('#completeReminderForm').submit(handleCompleteReminder);
            
            // Exclusão de lembrete
            $(document).on('submit', '.delete-reminder-form', handleDeleteReminder);
        }

        // Manipulador de submit de lembrete
        async function handleReminderSubmit(e) {
            e.preventDefault();
            const form = $(this);
            const formData = form.serialize();

            try {
                const response = await $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: formData,
                    beforeSend: () => toggleFormLoading(form, true)
                });

                $('#reminderModal').modal('hide');
                form.trigger("reset");
                showNotification('Sucesso!', 'Lembrete adicionado com sucesso', 'success');
                updateUIAfterChange(response.contract_id);
                
            } catch (error) {
                handleAjaxError(error, 'Erro ao salvar lembrete');
            } finally {
                toggleFormLoading(form, false);
            }
        }

        // Manipulador de conclusão de lembrete
        async function handleCompleteReminder(e) {
            e.preventDefault();
            const form = $(this);

            try {
                await $.ajax({
                    type: "PATCH",
                    url: form.attr('action'),
                    data: { is_completed: true }
                });

                $('#completeReminderModal').modal('hide');
                showNotification('Sucesso!', 'Lembrete marcado como concluído', 'success');
                updateUIAfterChange();
                
            } catch (error) {
                handleAjaxError(error, 'Erro ao concluir lembrete');
            }
        }

        // Manipulador de exclusão de lembrete
        async function handleDeleteReminder(e) {
            e.preventDefault();
            const form = $(this);

            if (!confirm('Tem certeza que deseja excluir este lembrete?')) return;

            try {
                await $.ajax({
                    type: "DELETE",
                    url: form.attr('action')
                });

                form.closest('.reminder-item, .reminder-card').fadeOut(300, () => {
                    $(this).remove();
                    showNotification('Sucesso!', 'Lembrete removido', 'success');
                    updateUIAfterChange();
                });
                
            } catch (error) {
                handleAjaxError(error, 'Erro ao excluir lembrete');
            }
        }

        // Atualização da UI após mudanças
        function updateUIAfterChange(contractId = null) {
            if (contractId) {
                const $section = $(`.kanban-card[data-id="${contractId}"] .reminders-section`);
                $section.find('.text-muted').remove();
            }
            updateBadgeCounters();
        }

        // Atualização de contadores
        function updateBadgeCounters() {
            $('.kanban-column').each(function() {
                const status = $(this).data('status');
                const count = status === 'Lembretes' 
                    ? $('.reminder-card', this).length 
                    : $('.kanban-card', this).length;
                
                $('.badge', this).text(count);
            });
        }

        // Polling para atualizações
        function startPolling() {
            setInterval(checkForUpdates, config.updateInterval);
            setTimeout(() => window.location.reload(), config.pageReloadInterval);
        }

        // Verificação de atualizações
        async function checkForUpdates() {
            $('.kanban-card').each(async function() {
                const cardId = $(this).data('id');
                const lastUpdate = $(this).data('last-update') || 0;

                try {
                    const response = await $.get(
                        `/reminders/check-updates/${cardId}?last_update=${lastUpdate}`
                    );

                    if (response.updated) {
                        $(this).data('last-update', response.timestamp);
                        $(this).find('.reminders-section').html(response.html);
                        updateBadgeCounters();
                    }
                } catch (error) {
                    console.error('Erro ao verificar atualizações:', error);
                }
            });
        }

        // Utilitários
        function toggleFormLoading(form, isLoading) {
            const button = form.find('button[type="submit"]');
            button.prop('disabled', isLoading)
                .html(isLoading 
                    ? '<i class="fas fa-spinner fa-spin"></i> Salvando...' 
                    : 'Salvar');
        }

        function handleAjaxError(error, defaultMsg) {
            let errorMessage = defaultMsg;
            if (error.responseJSON && error.responseJSON.errors) {
                errorMessage = Object.values(error.responseJSON.errors).join('\n');
            }
            showNotification('Erro!', errorMessage, 'danger');
        }

        function showNotification(title, message, type) {
            const $notification = $(`
                <div class="notification alert alert-${type} alert-dismissible fade show" 
                     role="alert" 
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <strong>${title}</strong> ${message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            `);

            $('body').append($notification);
            setTimeout(() => $notification.alert('close'), 5000);
        }

        function checkReminderAlerts() {
            $('.reminder-card').each(function() {
                const $card = $(this);
                if ($card.hasClass('reminder-proximo') || $card.hasClass('reminder-vencido')) {
                    $card.addClass('reminder-alert');
                }
            });
        }

        // Inicializar aplicação
        init();
    });
</script>
@endsection