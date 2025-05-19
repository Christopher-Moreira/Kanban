<!-- resources/views/kanban/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center">Kanban de Contratos</h1>
        </div>
    </div>

    <div class="kanban-board d-flex flex-nowrap overflow-auto">
        @foreach($statusColumns as $status)
        <div class="kanban-column mx-2" data-status="{{ $status }}">
            <div class="card">
                <div class="card-header bg-{{ getStatusColor($status) }}">
                    <h5 class="text-white mb-0">{{ $status }}</h5>
                    <span class="badge badge-light ml-2">{{ isset($cards[$status]) ? count($cards[$status]) : 0 }}</span>
                </div>
                <div class="card-body kanban-column-body" style="height: calc(100vh - 200px); overflow-y: auto;">
                    @if(isset($cards[$status]))
                        @foreach($cards[$status] as $card)
                        <div class="kanban-card card mb-3" data-id="{{ $card->id }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $card->cliente }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $card->contrato }}</h6>
                                <div class="kanban-card-details">
                                    <p class="mb-1"><strong>CPF/CNPJ:</strong> {{ formatCpfCnpj($card->cpf_cnpj) }}</p>
                                    <p class="mb-1"><strong>Atraso:</strong> {{ $card->dias_atraso_parcela }} dias</p>
                                    <p class="mb-1"><strong>Saldo Devedor:</strong> R$ {{ number_format($card->saldo_devedor_cont, 2, ',', '.') }}</p>
                                </div>
                                <div class="mt-3">
                                    <a href="{{route('kanban.show', $card->id) }}" class="btn btn-sm btn-primary">Detalhes</a>
                                    <div class="dropdown d-inline ml-2">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                            Mover
                                        </button>
                                        <div class="dropdown-menu">
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
        min-height: calc(100vh - 150px);
    }
    
    .kanban-column {
        min-width: 300px;
        width: 300px;
    }
    
    .kanban-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .kanban-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.move-card').on('click', function(e) {
            e.preventDefault();
            
            var cardId = $(this).data('card-id');
            var newStatus = $(this).data('status');
            
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
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error('Erro ao mover cartão:', error);
                    alert('Erro ao mover o cartão. Tente novamente.');
                }
            });
        });
    });
</script>
@endsection

@php
function getStatusColor($status) {
    switch ($status) {
        case 'Pendente':
            return 'warning';
        case 'Em Andamento':
            return 'primary';
        case 'Concluído':
            return 'success';
        case 'Atrasado':
            return 'danger';
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