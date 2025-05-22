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

if (!function_exists('getStatusClassName')) {
    function getStatusClassName($status)
    {
        $statusClasses = [
            'Pendente' => 'pendente',
            'Em Andamento' => 'em-andamento',
            'Concluído' => 'concluido',
            'Atrasado' => 'atrasado',
            'Lembretes' => 'lembretes',
            'Notificação' => 'notificação'
        ];

        return $statusClasses[$status] ?? 'secondary';
    }
}

if (!function_exists('getStatusBadgeClass')) {
    function getStatusBadgeClass($status)
    {
        $badgeClasses = [
            'Pendente' => 'warning',
            'Em Andamento' => 'info',
            'Concluído' => 'success',
            'Atrasado' => 'danger',
            'Notificação' => 'warning'
        ];

        return $badgeClasses[$status] ?? 'secondary';
    }
}

if (!function_exists('formatCpfCnpj')) {
    function formatCpfCnpj($cpfCnpj)
    {
        // Remove caracteres não numéricos
        $cpfCnpj = preg_replace('/[^0-9]/', '', $cpfCnpj);
        
        if (strlen($cpfCnpj) == 11) {
            // Formato CPF: 000.000.000-00
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfCnpj);
        } elseif (strlen($cpfCnpj) == 14) {
            // Formato CNPJ: 00.000.000/0000-00
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cpfCnpj);
        }
        
        return $cpfCnpj; // Retorna como estava se não for CPF nem CNPJ válido
    }
}