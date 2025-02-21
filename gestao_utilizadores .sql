-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Fev-2025 às 13:08
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestao_utilizadores`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `code`, `expires_at`) VALUES
(10, 'imafonsosilva@gmail.com', '452834', '2024-12-11 11:59:58'),
(12, 'susanaveiga.pereira@esab.pt', '391071', '2024-12-11 12:08:21'),
(16, 'legotugrafia@gmail.com', '116898', '2025-02-04 18:01:07'),
(17, 'ahboavida@gmail.com', '995928', '2025-02-04 22:11:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `id_utilizador` int(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) DEFAULT 0,
  `categoria` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `id_utilizador`, `nome`, `descricao`, `preco`, `quantidade`, `categoria`, `imagem`) VALUES
(26, 1, 'banana', 'banana', 23.00, 5, 'banana', '67a23bc4bb47b-Wallpaper Digital exclusivo de Arcane No 2.jpg'),
(27, 1, 'dqd', 'dqdwq', 313.00, 321312, '1323', '67a244d7a85b6-export.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id_servico` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `vagas_disponiveis` int(11) DEFAULT 0,
  `categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos_utilizador`
--

CREATE TABLE `tipos_utilizador` (
  `id_tipos_utilizador` int(11) NOT NULL,
  `tipo_utilizador` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipos_utilizador`
--

INSERT INTO `tipos_utilizador` (`id_tipos_utilizador`, `tipo_utilizador`) VALUES
(0, 'administrador'),
(1, 'utilizador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizadores` int(11) NOT NULL,
  `utilizador` varchar(20) NOT NULL,
  `password` varchar(41) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_tipos_utilizador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizadores`, `utilizador`, `password`, `email`, `id_tipos_utilizador`) VALUES
(1, '', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', 'admin@brotero.pt', 0),
(2, '', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 'admin2@brotero.pt', 0),
(6, '', '*34D3B87A652E7F0D1D371C3DBF28E291705468C4', 'user', 1),
(7, '', '*12A20BE57AF67CBF230D55FD33FBAF5230CFDBC4', 'user2@brotero.pt', 1),
(64, 'a', '*565B1B47FD7BC0488435D2B707071F5EF873197B', 'legotugrafia@gmail.com', 1),
(65, 'lol@gmail.com', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', 'lol@gmail.com', 1),
(66, 'baba', '*735801FB06897C62D4EDAE2AEF5E9656155DC700', 'ahboavida@gmail.com', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produtos_utilizadores` (`id_utilizador`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id_servico`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `tipos_utilizador`
--
ALTER TABLE `tipos_utilizador`
  ADD PRIMARY KEY (`id_tipos_utilizador`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizadores`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FD_id_utilizador` (`id_tipos_utilizador`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizadores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_utilizadores` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizadores`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizadores`);

--
-- Limitadores para a tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD CONSTRAINT `FD_id_utilizador` FOREIGN KEY (`id_tipos_utilizador`) REFERENCES `tipos_utilizador` (`id_tipos_utilizador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
