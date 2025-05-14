<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedData extends Model
{
    protected $table = 'dados_excel';
    protected $fillable = [
        'pa',
        'transic',
        'nivel_clas',
        'cpf_cnpj',
        'cliente',
        'contrato',
        'dias_atraso_parcela',
        'dias_atraso_a_fin_mes',
        'mod_produto',
        'saldo_devedor_cont',
        'saldo_devedor_cred',
        'saldo_ad_cc',
        'R'
    ];
}