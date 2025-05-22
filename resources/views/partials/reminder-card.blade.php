<div class="reminder-card card mb-2 reminder-{{ $reminder->urgency_type }}" data-reminder-id="{{ $reminder->id }}">
    <div class="card-body p-3">
        <!-- Cabeçalho com Badge de Urgência -->
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="flex-grow-1">
                <h6 class="card-title mb-1 text-primary">
                    <i class="fas fa-user mr-1"></i>{{ $reminder->importedData->cliente }}
                </h6>
                <small class="text-muted">
                    <i class="fas fa-file-contract mr-1"></i>{{ $reminder->importedData->contrato }}
                </small>
            </div>
            
            <!-- Badge de Urgência -->
            <div class="d-flex flex-column align-items-end">
                @if($reminder->urgency_type == 'vencido')
                    <span class="urgency-badge urgency-vencido mb-1">
                        <i class="fas fa-exclamation-triangle"></i> VENCIDO
                    </span>
                    <small class="time-indicator time-vencido">
                        {{ $reminder->days_diff }} {{ $reminder->days_diff == 1 ? 'dia' : 'dias' }} atrás
                    </small>
                @elseif($reminder->urgency_type == 'proximo')
                    <span class="urgency-badge urgency-proximo mb-1">
                        <i class="fas fa-clock"></i> Próximo a Vencer
                    </span>
                    <small class="time-indicator time-proximo">
                        @if($reminder->days_diff == 0)
                            Hoje
                        @elseif($reminder->days_diff == 1)
                            Amanhã
                        @else
                            Em {{ $reminder->days_diff }} dias
                        @endif
                    </small>
                @else
                    <span class="urgency-badge urgency-normal mb-1">
                        <i class="fas fa-calendar-check"></i> AGENDADO
                    </span>
                    <small class="time-indicator">
                        Em {{ $reminder->days_diff }} {{ $reminder->days_diff == 1 ? 'dia' : 'dias' }}
                    </small>
                @endif
            </div>
        </div>

        <!-- Data e Hora do Lembrete -->
        <div class="reminder-datetime mb-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-alt mr-2 text-info"></i>
                <span class="font-weight-bold">
                    {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d/m/Y') }}
                </span>
                <i class="fas fa-clock ml-3 mr-2 text-info"></i>
                <span class="font-weight-bold">
                    {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('H:i') }}
                </span>
            </div>
        </div>

        <!-- Observações do Lembrete -->
        <div class="reminder-notes mb-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-sticky-note mr-2 text-secondary mt-1"></i>
                <p class="mb-0 text-dark">{{ $reminder->notes }}</p>
            </div>
        </div>

        <!-- Informações Adicionais do Contrato -->
        <div class="contract-info mb-3">
            <div class="row text-sm">
                <div class="col-6">
                    <small class="text-muted">
                        <i class="fas fa-id-card mr-1"></i>
                        {{ formatCpfCnpj($reminder->importedData->cpf_cnpj) }}
                    </small>
                </div>
                <div class="col-6 text-right">
                    <small class="text-success font-weight-bold">
                        <i class="fas fa-money-bill-wave mr-1"></i>
                        R$ {{ number_format($reminder->importedData->saldo_devedor_cont, 2, ',', '.') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group btn-group-sm" role="group">
                <!-- Botão Ver Contrato -->
                <a href="{{ route('kanban.show', $reminder->importedData->id) }}" 
                   class="btn btn-outline-primary btn-sm"
                   title="Ver detalhes do contrato">
                    <i class="fas fa-eye"></i>
                </a>
                
                <!-- Botão Marcar como Concluído -->
                <button class="btn btn-outline-success btn-sm complete-reminder"
                        data-reminder-id="{{ $reminder->id }}"
                        data-reminder-text="{{ $reminder->notes }}"
                        data-reminder-date="{{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d/m/Y H:i') }}"
                        title="Marcar como concluído">
                    <i class="fas fa-check"></i>
                </button>
            </div>

            <!-- Botão Excluir -->
            <div class="actions">
                <form class="d-inline delete-reminder-form" 
                      method="POST" 
                      action="{{ route('reminders.destroy', $reminder) }}">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-outline-danger btn-sm"
                            title="Excluir lembrete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Indicador de Status do Contrato -->
        <div class="contract-status mt-2">
            <span class="badge badge-pill badge-{{ getStatusBadgeClass($reminder->importedData->status) }}">
                <i class="fas fa-circle mr-1"></i>{{ $reminder->importedData->status }}
            </span>
        </div>
    </div>

    <!-- Linha de tempo visual para lembretes vencidos ou próximos -->
    @if($reminder->urgency_type == 'vencido' || $reminder->urgency_type == 'proximo')
        <div class="reminder-timeline">
            <div class="timeline-indicator timeline-{{ $reminder->urgency_type }}"></div>
        </div>
    @endif
</div>