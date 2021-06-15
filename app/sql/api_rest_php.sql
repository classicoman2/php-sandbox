-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Temps de generació: 15-06-2021 a les 11:58:36
-- Versió del servidor: 5.7.30-0ubuntu0.18.04.1-log
-- Versió de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `api_rest_php`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `cyclists`
--

CREATE TABLE `cyclists` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `birth_date` date NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `fk_team` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcament de dades per a la taula `cyclists`
--

INSERT INTO `cyclists` (`id`, `name`, `birth_date`, `height`, `weight`, `fk_team`) VALUES
(1, 'Peter Froome', '2077-06-15', 189, 76, 1),
(2, 'Toni Dumolen', '2077-06-16', 190, 72, 2);

-- --------------------------------------------------------

--
-- Estructura de la taula `cyclist_team_year`
--

CREATE TABLE `cyclist_team_year` (
  `id` int(11) NOT NULL,
  `fk_cyclist` int(11) NOT NULL,
  `fk_team` int(11) NOT NULL,
  `year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de la taula `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Bolcament de dades per a la taula `teams`
--

INSERT INTO `teams` (`id`, `name`) VALUES
(1, 'Sky'),
(2, 'Jumbo');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `cyclists`
--
ALTER TABLE `cyclists`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `cyclist_team_year`
--
ALTER TABLE `cyclist_team_year`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `cyclists`
--
ALTER TABLE `cyclists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la taula `cyclist_team_year`
--
ALTER TABLE `cyclist_team_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la taula `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
