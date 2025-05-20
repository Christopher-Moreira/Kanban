<?php

if (!function_exists('getStatusClassName')) {
    function getStatusClassName($status) {
        switch ($status) {
            case 'Pendente': return 'pendente';
            case 'Em Andamento': return 'em-andamento';
            case 'Concluído': return 'concluido';
            case 'Atrasado': return 'atrasado';
            default: return 'secondary';
        }
    }
}

if (!function_exists('formatCpfCnpj')) {
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
}