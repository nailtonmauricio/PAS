-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 05/02/2022 às 03:46
-- Versão do servidor: 10.4.20-MariaDB
-- Versão do PHP: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `project_administrative_system`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `access_level`
--

CREATE TABLE `access_level` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `position` int(11) NOT NULL,
  `situation` tinyint(1) DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `access_level`
--

INSERT INTO `access_level` (`id`, `name`, `position`, `situation`, `created`, `modified`) VALUES
(1, 'admin', 1, 1, '2022-02-01 19:20:11', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(220) NOT NULL,
  `color` varchar(10) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `path` varchar(220) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `pages`
--

INSERT INTO `pages` (`id`, `name`, `path`, `description`, `created`, `modified`) VALUES
(1, 'home', 'home', NULL, '2022-02-01 23:36:32', NULL),
(2, 'paginas', 'list/list_paginas', NULL, '2022-02-02 19:09:02', NULL),
(3, 'register page', 'register/reg_paginas', NULL, '2022-02-02 22:13:09', NULL),
(4, 'view page', 'viewer/view_paginas', NULL, '2022-02-02 22:20:00', NULL),
(5, 'edit page', 'edit/edit_paginas', NULL, '2022-02-02 22:15:26', NULL),
(6, 'proccess register pages', 'process/reg/reg_paginas', NULL, '2022-02-03 13:25:35', NULL),
(7, 'proccess edit pages', 'process/edit/edit_paginas', NULL, '2022-02-03 13:35:35', NULL),
(8, 'níveis de acesso', 'list/list_niveis_acesso', NULL, '2022-02-04 11:29:08', NULL),
(9, 'cadastrar nível de acesso', 'register/reg_niveis_acesso', NULL, '2022-02-04 19:39:06', NULL),
(10, 'view access level', 'viewer/view_niveis_acesso', NULL, '2022-02-04 19:57:35', NULL),
(11, 'edit access leve', 'edit/edit_niveis_acesso', NULL, '2022-02-04 19:56:29', NULL),
(12, 'process register access level', 'process/reg/reg_niveis_acesso', NULL, '2022-02-04 19:58:56', NULL),
(13, 'process edit access level', 'process/edit/edit_niveis_acesso', NULL, '2022-02-04 19:59:37', NULL),
(14, 'permissões', 'list/list_permissoes', NULL, '2022-02-04 12:32:07', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `page_access_level`
--

CREATE TABLE `page_access_level` (
  `id` int(11) NOT NULL,
  `al_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `access` tinyint(1) DEFAULT 0,
  `menu` tinyint(1) DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `page_access_level`
--

INSERT INTO `page_access_level` (`id`, `al_id`, `page_id`, `access`, `menu`, `created`, `modified`) VALUES
(1, 1, 1, 1, 1, '2022-02-02 16:14:27', NULL),
(2, 1, 2, 1, 1, '2022-02-02 16:33:48', NULL),
(3, 1, 3, 1, 0, '2022-02-02 22:14:01', NULL),
(4, 1, 4, 1, 0, '2022-02-02 22:16:08', NULL),
(5, 1, 5, 1, 0, '2022-02-02 22:20:56', NULL),
(6, 1, 6, 1, 0, '2022-02-02 22:24:28', NULL),
(7, 1, 7, 1, 0, '2022-02-03 13:26:13', NULL),
(8, 1, 8, 1, 1, '2022-02-04 11:30:18', NULL),
(9, 1, 9, 1, 0, '2022-02-04 12:32:07', NULL),
(10, 1, 10, 1, 0, '2022-02-04 19:39:06', NULL),
(11, 1, 11, 1, 0, '2022-02-04 19:56:29', NULL),
(12, 1, 12, 1, 0, '2022-02-04 19:57:35', NULL),
(13, 1, 13, 1, 0, '2022-02-04 19:58:56', NULL),
(14, 1, 14, 1, 0, '2022-02-04 19:59:37', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(220) NOT NULL,
  `email` varchar(220) DEFAULT NULL,
  `cell_phone` varchar(11) DEFAULT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_password` varchar(220) NOT NULL,
  `password_recover` varchar(220) DEFAULT NULL,
  `situation` tinyint(1) DEFAULT 1,
  `access_level` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `cell_phone`, `user_name`, `user_password`, `password_recover`, `situation`, `access_level`, `created`, `modified`) VALUES
(1, 'system admin', 'suporte@nmatec.com.br', '83993348144', 'root', '$2y$10$r2s9nIM3PUimirknEP19huOz0jWnMuWE8BBcyLiK061jtkOsNmSSe', NULL, 1, 1, '2022-02-01 19:18:57', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `access_level`
--
ALTER TABLE `access_level`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`);

--
-- Índices de tabela `page_access_level`
--
ALTER TABLE `page_access_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `al_id` (`al_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_level` (`access_level`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `access_level`
--
ALTER TABLE `access_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `page_access_level`
--
ALTER TABLE `page_access_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `page_access_level`
--
ALTER TABLE `page_access_level`
  ADD CONSTRAINT `page_access_level_ibfk_1` FOREIGN KEY (`al_id`) REFERENCES `access_level` (`id`),
  ADD CONSTRAINT `page_access_level_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`access_level`) REFERENCES `access_level` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
