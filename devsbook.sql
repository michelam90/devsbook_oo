-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Maio-2024 às 16:21
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `devsbook`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `postcomments`
--

CREATE TABLE `postcomments` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `postcomments`
--

INSERT INTO `postcomments` (`id`, `id_post`, `id_user`, `created_at`, `body`) VALUES
(1, 7, 4, '2024-05-03 03:47:45', 'Fake teste'),
(2, 7, 4, '2024-05-03 03:48:07', 'Interessante, continue assim!'),
(3, 7, 6, '2024-05-03 04:00:59', 'Bacana, bora lá!'),
(4, 8, 4, '2024-05-04 21:54:57', 'És verdad!!'),
(5, 8, 4, '2024-05-04 21:58:01', 'Seguimos em frente...'),
(6, 5, 4, '2024-05-04 22:00:34', 'Top!'),
(7, 22, 4, '2024-05-11 20:38:45', 'Boa tarde! '),
(8, 26, 4, '2024-05-11 22:51:28', 'Legal!'),
(9, 27, 6, '2024-05-11 22:52:37', 'Carrão! '),
(10, 27, 4, '2024-05-12 15:35:24', 'Obrigado! '),
(11, 27, 7, '2024-05-12 15:56:30', 'Parabéns! '),
(12, 30, 4, '2024-05-12 16:05:21', 'Boa tarde! '),
(13, 27, 8, '2024-05-12 16:08:58', 'Muito legal!');

-- --------------------------------------------------------

--
-- Estrutura da tabela `postlikes`
--

CREATE TABLE `postlikes` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `postlikes`
--

INSERT INTO `postlikes` (`id`, `id_post`, `id_user`, `created_at`) VALUES
(1, 7, 6, '2024-05-01 22:35:42'),
(8, 4, 4, '2024-05-01 18:26:39'),
(9, 6, 6, '2024-05-01 18:28:07'),
(10, 4, 6, '2024-05-01 18:28:14'),
(12, 8, 4, '2024-05-04 16:14:36'),
(15, 7, 4, '2024-05-04 17:00:23'),
(20, 1, 4, '2024-05-10 21:37:49'),
(22, 23, 4, '2024-05-11 15:31:50'),
(28, 20, 4, '2024-05-11 15:37:41'),
(30, 22, 4, '2024-05-11 15:38:39'),
(31, 6, 4, '2024-05-11 17:37:58'),
(32, 24, 6, '2024-05-11 17:46:54'),
(33, 26, 4, '2024-05-11 17:51:30'),
(34, 26, 6, '2024-05-11 17:52:29'),
(35, 27, 6, '2024-05-11 17:52:30'),
(36, 27, 4, '2024-05-12 10:35:19'),
(37, 5, 4, '2024-05-12 10:36:04'),
(38, 30, 4, '2024-05-12 11:05:16'),
(39, 28, 4, '2024-05-12 11:05:54'),
(40, 27, 8, '2024-05-12 11:09:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`id`, `id_user`, `type`, `created_at`, `body`) VALUES
(1, 4, 'text', '2024-04-14 18:51:20', 'Pensando em coisas grandes!'),
(2, 4, 'text', '2024-04-14 18:52:04', 'Quem pensa realmente enriquece? Como?'),
(4, 6, 'text', '2024-04-14 18:54:25', 'O que está rolando de legal hoje pessoal?'),
(5, 4, 'text', '2024-04-16 01:44:28', 'Testando exibição dinâmica do feed com.... ???/\r\nUma pulada de linha!\r\n\r\n\r\nDuas puladas de linha!'),
(6, 4, 'text', '2024-04-21 23:10:44', 'Testando post de dentro do meu perfil!'),
(7, 4, 'text', '2024-04-22 02:56:29', 'Testando post na pagina principal!'),
(8, 6, 'text', '2024-05-01 23:28:56', 'Tá ficando legal essa nova funcionalidade de likes!'),
(20, 4, 'text', '2024-05-10 04:01:45', 'Mais um post para esse dia! \r\n\r\n\r\nBoa tarde!'),
(21, 6, 'text', '2024-05-11 20:16:41', 'Bom dia!'),
(22, 6, 'text', '2024-05-11 20:16:46', 'Boa tarde!'),
(23, 6, 'text', '2024-05-11 20:16:52', 'Boa noite!'),
(26, 4, 'photos', '2024-05-11 22:51:09', 'e703b8c3ef86b2b37fb0769d4df6f93e.jpg'),
(27, 4, 'photos', '2024-05-11 22:52:13', '2333badd1348cd92528642b621938257.jpg'),
(28, 4, 'text', '2024-05-12 15:36:18', 'Tudo bem por aí?'),
(30, 4, 'text', '2024-05-12 16:05:13', 'Bom dia!');

-- --------------------------------------------------------

--
-- Estrutura da tabela `userrelations`
--

CREATE TABLE `userrelations` (
  `id` int(11) NOT NULL,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `userrelations`
--

INSERT INTO `userrelations` (`id`, `user_from`, `user_to`, `date`) VALUES
(3, 4, 5, '0000-00-00 00:00:00'),
(7, 6, 4, '0000-00-00 00:00:00'),
(9, 7, 4, '0000-00-00 00:00:00'),
(10, 4, 7, '0000-00-00 00:00:00'),
(11, 4, 6, '0000-00-00 00:00:00'),
(12, 8, 4, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `city` varchar(100) NOT NULL,
  `work` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `cover` varchar(100) NOT NULL,
  `token` varchar(200) NOT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `birthdate`, `city`, `work`, `avatar`, `cover`, `token`, `data`) VALUES
(4, 'michel@gmail.com', '$2y$10$hDzawnvCFL3X/psDolnWZenQyo0/DiPTWIfhiM/0dIMU2PcXULasW', 'Michel Da Silva', '1990-04-15', 'São José', 'Empresa', '5782187d2f352e75ff160706dbce055fjpg', '258af6f5fc20e51d4eddb55d2b03fdcfjpg', 'c9afa5a6d757ace5e12a0c0bf53b4d2b', '2024-05-12 11:05:03'),
(5, 'miro@gmail.com', '$2y$10$FZZcO4fv.i7GIeq7Ys2KwOBBR7MAqaJzBPGoZcB0X02OLTcmKp9V.$2y$10$FZZcO4fv.i7GIeq7Ys2KwOBBR7MAqaJzBPGoZcB0X02OLTcmKp9V.', 'Miro  Da Silva', '2000-10-15', '', '', 'avatar.jpg', '', '5d1556fbdb9c6f70c9db57c4cb7043ae', '2024-02-25 11:00:02'),
(6, 'fabiola@gmail.com', '$2y$10$FZZcO4fv.i7GIeq7Ys2KwOBBR7MAqaJzBPGoZcB0X02OLTcmKp9V.', 'Fabiola da Silva', '1989-10-12', 'Floripa', 'Outras', 'd8722bc582cc966dc59b9690f5f690adjpg', 'cover.jpg', '3f7df49f37fbe64e8312b4732aad0186', '2024-05-11 17:42:01'),
(7, 'fulano@outlook.com', '$2y$10$D3iiGHQqnMMktzazAntrJ.3AGLd6ye8Ha0BOo76L6B3yt9EnnP/5K', 'Fulando de Tal', '1995-06-15', 'Floripa', 'Empresa X', 'f23e60f78de57f46489aa09da152de8ajpg', '9d579b724a74c80d29eb512abf17e377jpg', 'a24f47abd190d5ac2e8ddc7f9643b925', '2024-05-12 10:56:00'),
(8, 'teste@gmail.com', '$2y$10$sp8rjw1MdiXHtbc8LMV4M.zE.6cwsQcMMlUWJv6PCa2WkTmWhKYku', 'Teste ', '1994-07-15', '', '', '88f56a802f37429a578aa89d07902058jpg', 'afb57b490ef7a77b38159e6ecfc4ab2cjpg', '81ce6ecc105aad198f35de35c4a3f542', '2024-05-12 11:08:24');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `postcomments`
--
ALTER TABLE `postcomments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `postlikes`
--
ALTER TABLE `postlikes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `userrelations`
--
ALTER TABLE `userrelations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `postcomments`
--
ALTER TABLE `postcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `postlikes`
--
ALTER TABLE `postlikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `userrelations`
--
ALTER TABLE `userrelations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
