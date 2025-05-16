<div class="card kanban-card" 
     id="card-{{ $item->id }}" 
     data-id="{{ $item->id }}" 
     draggable="true">
    <div class="card-header">
        <h5 class="mb-0">{{ $item->cliente }}</h5>
    </div>
    <div class="card-body">
        <div class="mb-2">
            <strong>CPF/CNPJ:</strong> {{ $item->cpf_cnpj }}
        </div>
        <div class="mb-2">
            <strong>Contrato:</strong> {{ $item->contrato }}
        </div>
        <div class="mb-3 saldo-devedor">
            Saldo Devedor: R$ {{ number_format($item->saldo_devedor_cont, 2, ',', '.') }}
        </div>
        <div class="row">
            <div class="col-6">
                <strong>Dias atraso:</strong> {{ $item->dias_atraso_parcela }}
            </div>
            <div class="col-6">
                <strong>Produto:</strong> {{ $item->mod_produto }}
            </div>
        </div>
    </div>
    <div class="card-footer">
        <small>Atualizado: {{ $item->updated_at->format('d/m/Y H:i') }}</small>
    </div>
</div>