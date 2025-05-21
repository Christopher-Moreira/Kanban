-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `kanban`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_excel`
--

CREATE TABLE `dados_excel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pa` varchar(10) DEFAULT NULL,
  `transic` varchar(10) DEFAULT NULL,
  `nivel_clas` varchar(100) DEFAULT NULL,
  `cpf_cnpj` varchar(100) DEFAULT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `contrato` varchar(100) DEFAULT NULL,
  `dias_atraso_parcela` varchar(100) DEFAULT NULL,
  `dias_atraso_a_fin_mes` varchar(100) DEFAULT NULL,
  `mod_produto` varchar(100) DEFAULT NULL,
  `saldo_devedor_cont` varchar(100) DEFAULT NULL,
  `saldo_devedor_cred` varchar(100) DEFAULT NULL,
  `saldo_ad_cc` varchar(100) DEFAULT NULL,
  `R` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Estrutura para tabela `reminders`
--

CREATE TABLE `reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `imported_data_id` bigint(20) UNSIGNED NOT NULL,
  `reminder_date` datetime NOT NULL,
  `notes` text NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `dados_excel`
--
ALTER TABLE `dados_excel`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reminders_imported_data` (`imported_data_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dados_excel`
--
ALTER TABLE `dados_excel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66523;

--
-- AUTO_INCREMENT de tabela `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `fk_reminders_imported_data` FOREIGN KEY (`imported_data_id`) REFERENCES `dados_excel` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
