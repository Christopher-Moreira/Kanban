
<!-- resources/views/kanban/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{route('kanban.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Voltar para o Kanban
            </a>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detalhes do Contrato</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informações do Cliente</h5>
                            <hr>
                            <div class="mb-3">
                                <strong>Cliente:</strong> {{ $card->cliente }}
                            </div>
                            <div class="mb-3">
                                <strong>CPF/CNPJ:</strong> {{ formatCpfCnpj($card->cpf_cnpj) }}
                            </div>
                            <div class="mb-3">
                                <strong>Status Atual:</strong> 
                                <span class="badge badge-{{ getStatusColor($card->status) }}">{{ $card->status }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Informações do Contrato</h5>
                            <hr>
                            <div class="mb-3">
                                <strong>Contrato:</strong> {{ $card->contrato }}
                            </div>
                            <div class="mb-3">
                                <strong>Produto:</strong> {{ $card->mod_produto }}
                            </div>
                            <div class="mb-3">
                                <strong>Nível Classificação:</strong> {{ $card->nivel_clas }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informações Financeiras</h5>
                            <hr>
                            <div class="mb-3">
                                <strong>Saldo Devedor Contábil:</strong> R$ {{ number_format($card->saldo_devedor_cont, 2, ',', '.') }}
                            </div>
                            <div class="mb-3">
                                <strong>Saldo Devedor Crédito:</strong> R$ {{ number_format($card->saldo_devedor_cred, 2, ',', '.') }}
                            </div>
                            <div class="mb-3">
                                <strong>Saldo AD CC:</strong> R$ {{ number_format($card->saldo_ad_cc, 2, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Informações de Atraso</h5>
                            <hr>
                            <div class="mb-3">
                                <strong>Dias de Atraso Parcela:</strong> {{ $card->dias_atraso_parcela }} dias
                            </div>
                            <div class="mb-3">
                                <strong>Dias de Atraso até Fim do Mês:</strong> {{ $card->dias_atraso_a_fin_mes }} dias
                            </div>
                            <div class="mb-3">
                                <strong>PA:</strong> {{ $card->pa }}
                            </div>
                            <div class="mb-3">
                                <strong>Transição:</strong> {{ $card->transic }}
                            </div>
                            <div class="mb-3">
                                <strong>Letra R:</strong> {{ $card->R }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Ações</h5>
                            <hr>
                            <form id="update-status-form" class="d-flex align-items-center">
                                @csrf
                                <div class="form-group mr-3">
                                    <label for="status">Atualizar Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Pendente" {{ $card->status == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="Em Andamento" {{ $card->status == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                                        <option value="Concluído" {{ $card->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                                        <option value="Atrasado" {{ $card->status == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4">Atualizar Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#update-status-form').on('submit', function(e) {
            e.preventDefault();
            
            var newStatus = $('#status').val();
            
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
                        alert('Status atualizado com sucesso!');
                        window.location.reload();
                    }
                },
                error: function(error) {
                    console.error('Erro ao atualizar status:', error);
                    alert('Erro ao atualizar o status. Tente novamente.');
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