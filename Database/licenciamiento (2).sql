-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2025 a las 18:28:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `licenciamiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificados`
--

CREATE TABLE `certificados` (
  `id_certificado` varchar(200) NOT NULL,
  `nombrePersona` varchar(45) NOT NULL,
  `evento` varchar(45) NOT NULL,
  `fecha_inicio_evento` date NOT NULL,
  `fecha_fin_evento` date NOT NULL,
  `Propietario` int(11) NOT NULL,
  `ruta_archivo` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `certificados`
--

INSERT INTO `certificados` (`id_certificado`, `nombrePersona`, `evento`, `fecha_inicio_evento`, `fecha_fin_evento`, `Propietario`, `ruta_archivo`) VALUES
('0V5KF7O4YN', 'Jesus Gabriel', 'Ceremonia de Graduación', '2024-06-05', '2025-06-05', 101010, 'certificado_graduacion_0V5KF7O4YN.pdf'),
('7RYX49CU5V', 'Jean Roa', 'Profesional en laravel', '2024-04-25', '2025-04-25', 252525, 'certificado_graduacion_7RYX49CU5V.pdf'),
('B85UN2J1VO', 'Leidy Milena Machado Gonzalez', 'Graduacion de lengua castellana', '2024-06-05', '2025-06-05', 252525, 'certificado_graduacion_B85UN2J1VO.pdf'),
('C8YR417H3N', 'Brandon Yulian Villanueva', 'ADSO', '2024-06-05', '2025-06-05', 252525, 'certificado_graduacion_C8YR417H3N.pdf'),
('EUNXVRMOQB', 'Laura Nicole Otalora', 'Ceremonia de Graduación', '2024-06-05', '2025-06-05', 252525, 'certificado_graduacion_EUNXVRMOQB.pdf'),
('J3QDYMP4N5', 'Juan Camilo Triana Urueña', 'Bachicher Academico', '2024-01-01', '2024-12-12', 252525, 'certificado_graduacion_J3QDYMP4N5.pdf'),
('OADK0XJ83G', 'Gabriela Devia marin', 'Ofimatic Prime', '2024-06-05', '2024-08-20', 252525, 'certificado_graduacion_OADK0XJ83G.pdf'),
('T30NLUEPSO', 'Juanes Vallejo', 'Ceremonia de Graduación de fotografia', '2025-06-05', '2026-06-05', 16161616, 'certificado_graduacion_T30NLUEPSO.pdf'),
('TC36PX4HYL', 'Manuel manco', 'Ceremonia de Graduación', '2022-02-02', '2023-02-02', 252525, 'certificado_graduacion_TC36PX4HYL.pdf'),
('X0NDAWLQJ4', 'Diana Jubbely Urueña machado', 'Graduacion de estetica y belleza', '2024-06-05', '2025-06-05', 252525, 'certificado_graduacion_X0NDAWLQJ4.pdf'),
('ZO50V38H26', 'Juanes Valejjo', 'Ceremonia de Graduación', '2004-06-05', '2007-06-05', 101010, 'certificado_graduacion_ZO50V38H26.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL,
  `nombreEmpresa` varchar(50) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `nombreEmpresa`, `direccion`, `id_estado`) VALUES
(12345678, 'SENA', 'Cra 44 A sur calle 123-35', 1),
(154442512, 'Movistar', 'Cra 44 A sur calle 123-535', 1),
(1115788496, 'Datec SAS', 'Cra 12 #200-60', 1),
(2147483647, 'MICROSOFT', 'MI casa 2003#', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'Activo'),
(2, 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `id_licencia` varchar(20) NOT NULL,
  `id_tipo_licencia` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fn` date NOT NULL,
  `UsosGastados` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencias`
--

INSERT INTO `licencias` (`id_licencia`, `id_tipo_licencia`, `valor`, `fecha_inicio`, `fecha_fn`, `UsosGastados`, `id_empresa`, `id_estado`, `created_at`) VALUES
('0E47B31957F7CA8D6B29', 1, 20000, '2004-06-04', '2025-06-04', 0, 1115788496, 2, '2025-04-25 13:48:58'),
('1E2CAB1CD99DAC503389', 1, 6000, '2024-02-04', '2025-06-30', 0, 1115788496, 2, '2025-04-25 11:54:51'),
('72205787CE31121A05FF', 0, 12000, '2024-06-05', '2025-06-05', 0, 1115788496, 2, '2025-04-25 12:58:56'),
('A10ECA8684A896B661C6', 0, 20000, '2024-06-05', '2025-06-05', 50, 1115788496, 1, '2025-04-25 14:42:07'),
('C12B9EE934CD1172647F', 0, 20000, '2024-05-05', '2026-05-05', 0, 1115788496, 2, '2025-04-25 14:35:07'),
('C2DFA01155D2F33B2691', 0, 20000, '2004-06-05', '2025-06-07', 0, 1115788496, 2, '2025-04-25 13:58:06'),
('D3335F6684D43D29680D', 0, 12000, '2005-06-05', '2027-06-05', 0, 1115788496, 2, '2025-04-25 13:56:21'),
('E2E6E892301BC2F912BE', 1, 10000, '2025-12-24', '2025-12-25', 0, 1115788496, 2, '2025-04-25 03:51:57'),
('E405C5AA90512A5ADAF7', 1, 15000, '2024-06-05', '2025-06-05', 8, 154442512, 1, '2025-04-25 03:22:54'),
('F92F02758DCADD3AE4F6', 1, 2000, '2024-06-05', '2025-04-26', 9, 2147483647, 1, '2025-04-25 14:58:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(1, 'SuperAdministrador'),
(2, 'Administrador'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipolicencia`
--

CREATE TABLE `tipolicencia` (
  `id_tipo_licencia` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Usos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipolicencia`
--

INSERT INTO `tipolicencia` (`id_tipo_licencia`, `Nombre`, `Usos`) VALUES
(0, 'Premium', 50),
(1, 'Demo', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cedula` bigint(20) NOT NULL,
  `nombreCompleto` varchar(120) NOT NULL,
  `Telefono` bigint(20) NOT NULL,
  `Correo` varchar(120) NOT NULL,
  `contrasena` varchar(500) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cedula`, `nombreCompleto`, `Telefono`, `Correo`, `contrasena`, `id_role`, `id_estado`, `id_empresa`) VALUES
(101010, 'Brandon Villanueva Serrano', 3929102, 'alejoreyvm@gmail.com', '$2y$10$sAlmGe.99txwcN6WGgM08.99A9Izprl5w3w47PFF910qWK7njYyC.', 2, 1, 154442512),
(141414, 'Juan Fernando Machado', 3141821740, 'juan@gmail.com', '$2y$10$4hccsJmd07DbvgIYhbpwl.fxMu/wI7wiOYJhk1U/AtFxBDK/T12mW', 3, 1, 1115788496),
(252525, 'Leidy Milena Machado', 3142822521, 'leidy@hotmail.com', '$2y$10$jU0R2u1i0l1w4IANX6pDoO1sqkzOMjtKp2Eofd5tZXnuBTEC0F3L.', 2, 1, 1115788496),
(999999, 'Gabilinses morenita', 3721827, 'gabi@gmail.com', '$2y$10$XJxiAaRXaieryrQb7xOVHukt49ro5AzM1qc3Q5MWLQxo5kFo4EmVC', 3, 1, 2147483647),
(16161616, 'Juanes Vallejo', 36255123, 'juanesPro@gmail.com', '$2y$10$Io6MA9T4AlsVJFA2izQpfOjiSAZK6WBgeAOzWcLXRScssmk.tsehG', 2, 1, 2147483647),
(1107978187, 'alejandro Velandia Machado', 3142822521, 'alejoreyvm@gmail.com', '12345678', 1, 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id_certificado`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD PRIMARY KEY (`id_licencia`),
  ADD KEY `id_tipo_licencia` (`id_tipo_licencia`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipolicencia`
--
ALTER TABLE `tipolicencia`
  ADD PRIMARY KEY (`id_tipo_licencia`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD CONSTRAINT `licencias_ibfk_1` FOREIGN KEY (`id_tipo_licencia`) REFERENCES `tipolicencia` (`id_tipo_licencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `licencias_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `licencias_ibfk_3` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
