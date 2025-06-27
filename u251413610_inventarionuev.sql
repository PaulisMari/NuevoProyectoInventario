-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 19-06-2025 a las 19:54:31
-- Versión del servidor: 10.11.10-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u251413610_inventarionuev`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepedido`
--

CREATE TABLE `detallepedido` (
  `idDetalle` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `PrecioUni` int(11) NOT NULL,
  `IdPedido` int(11) NOT NULL,
  `Codigo` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `DocEmpleado` bigint(20) NOT NULL,
  `TipoDoc` int(11) DEFAULT NULL,
  `Nombre` varchar(40) DEFAULT NULL,
  `FechaNaci` date DEFAULT NULL,
  `Telefono` bigint(20) DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `Email` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`DocEmpleado`, `TipoDoc`, `Nombre`, `FechaNaci`, `Telefono`, `Direccion`, `Email`) VALUES
(2822828, 1, 'Cielo Gonzáles', '2025-06-18', 3203761044, 'Calle 56 barrio Limonar', 'isabelaperezcarrasco1@gmail.com'),
(1107977162, 1, 'Isabela Perez', '2006-05-09', 3003671088, 'Manzana 33 casa 13 santa rita', 'isabelaperezcarrasco1@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

CREATE TABLE `entrada` (
  `idEntrada` bigint(20) NOT NULL,
  `DescripcionEntrada` varchar(300) DEFAULT NULL,
  `CantidadEntrada` int(11) DEFAULT NULL,
  `FechaEntrada` datetime DEFAULT NULL,
  `PrecioUni` int(11) DEFAULT NULL,
  `Codigo` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`idEntrada`, `DescripcionEntrada`, `CantidadEntrada`, `FechaEntrada`, `PrecioUni`, `Codigo`) VALUES
(7, 'Entrada tela roja', 2, '2025-06-19 00:24:00', 10000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `idPedido` int(11) NOT NULL,
  `FechaPedido` date NOT NULL,
  `PedidoPor` bigint(20) DEFAULT NULL,
  `DocProveedor` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`idPedido`, `FechaPedido`, `PedidoPor`, `DocProveedor`) VALUES
(1, '2025-06-19', 2822828, 1069964060);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Codigo` bigint(20) NOT NULL,
  `NombreProducto` varchar(30) DEFAULT NULL,
  `Descripcion` varchar(500) DEFAULT NULL,
  `Precio` bigint(20) DEFAULT NULL,
  `CantMin` int(11) DEFAULT NULL,
  `CantMax` int(11) DEFAULT NULL,
  `CantDis` int(11) DEFAULT NULL,
  `CreadoPor` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`Codigo`, `NombreProducto`, `Descripcion`, `Precio`, `CantMin`, `CantMax`, `CantDis`, `CreadoPor`) VALUES
(1, 'Tela roja', 'Tela roja de algodon', 5000, 5, 10, 5, 1107977162),
(2, 'Tela azul', 'Tela de seda', 9000, 8, 10, 1, 1107977162);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `DocProveedor` bigint(20) NOT NULL,
  `TipoDoc` int(11) DEFAULT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Telefono` bigint(20) DEFAULT NULL,
  `Direccion` varchar(500) DEFAULT NULL,
  `Email` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`DocProveedor`, `TipoDoc`, `Nombre`, `Telefono`, `Direccion`, `Email`) VALUES
(1069964060, 1, 'Sergio Daniel Max', 3187835039, 'Yuldaima la montaña', 'sergitomax@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recovery_tokens`
--

CREATE TABLE `recovery_tokens` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiracion` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recovery_tokens`
--

INSERT INTO `recovery_tokens` (`id`, `usuario`, `token`, `expiracion`, `usado`, `creado_en`) VALUES
(1, '2822828', '7237dbf58c40e4bb8815a21a75275ad54a49bed217bd6c4b37d1d03becb01c8c', '2025-06-19 04:24:53', 0, '2025-06-19 03:24:53'),
(2, '2822828', '8d7e8256b24ee8352ea6b8c90094c0818835404633c285f6da25ac14a75cfd07', '2025-06-19 06:18:38', 1, '2025-06-19 05:18:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

CREATE TABLE `salida` (
  `idSalida` int(11) NOT NULL,
  `MotivoSalida` varchar(300) DEFAULT NULL,
  `CantidadSalida` int(11) DEFAULT NULL,
  `FechaSalida` datetime DEFAULT NULL,
  `Codigo` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `id` int(11) NOT NULL,
  `tipo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`id`, `tipo`) VALUES
(1, 'CC'),
(2, 'TI'),
(3, 'RC'),
(4, 'PPS'),
(5, 'CE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contraseña`) VALUES
(1, '2822828', '$2y$10$ClZ7B0XwTEeKdqHKZ0CKb.QyWU1NU7X86cf/RgQSQ.7thsG82JzZ6'),
(2, '1107977162', '$2y$10$JqtKPTcRp7YaDt1SmEhUJ.FsBVj14GAOQGOxthc5F14GPoh6oEmFK');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD PRIMARY KEY (`idDetalle`),
  ADD KEY `IdPedido` (`IdPedido`),
  ADD KEY `Codigo` (`Codigo`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`DocEmpleado`),
  ADD KEY `TipoDoc` (`TipoDoc`);

--
-- Indices de la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`idEntrada`),
  ADD KEY `Codigo` (`Codigo`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `DocProveedor` (`DocProveedor`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`DocProveedor`),
  ADD KEY `TipoDoc` (`TipoDoc`);

--
-- Indices de la tabla `recovery_tokens`
--
ALTER TABLE `recovery_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indices de la tabla `salida`
--
ALTER TABLE `salida`
  ADD PRIMARY KEY (`idSalida`),
  ADD KEY `Codigo` (`Codigo`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `idEntrada` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recovery_tokens`
--
ALTER TABLE `recovery_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `idSalida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD CONSTRAINT `detallepedido_ibfk_1` FOREIGN KEY (`IdPedido`) REFERENCES `pedido` (`idPedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallepedido_ibfk_2` FOREIGN KEY (`Codigo`) REFERENCES `producto` (`Codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_encargado_tipodoc` FOREIGN KEY (`TipoDoc`) REFERENCES `tipodocumento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`Codigo`) REFERENCES `producto` (`Codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`DocProveedor`) REFERENCES `proveedor` (`DocProveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_2` FOREIGN KEY (`TipoDoc`) REFERENCES `tipodocumento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `salida`
--
ALTER TABLE `salida`
  ADD CONSTRAINT `salida_ibfk_1` FOREIGN KEY (`Codigo`) REFERENCES `producto` (`Codigo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
