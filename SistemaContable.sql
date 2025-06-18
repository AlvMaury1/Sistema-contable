-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2025 a las 04:54:50
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistemacontabilidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `nro_factura` varchar(20) DEFAULT NULL,
  `nit_ci_proveedor` varchar(50) DEFAULT NULL,
  `razon_social` varchar(100) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `tipo_de_compra` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `fecha`, `nro_factura`, `nit_ci_proveedor`, `razon_social`, `monto`, `tipo_de_compra`, `id_cliente`) VALUES
(1, '2025-01-08', 'C-1001000000', '9919592', 'Proveedor Uno S.R.L.', '1000.00', 'Sin Crédito Fiscal', 7),
(2, '2025-06-12', 'C-1002', '1020304', 'Servicios Integrales Ltda.', '2500.00', 'Sin Crédito Fiscal', 7),
(3, '2025-06-19', 'C-1003', '1122334', 'Comercial Delta', '1800.00', 'Con Crédito Fiscal', 7),
(4, '2025-06-04', 'C-1004', '2233445', 'Distribuidora Gamma', '950.00', 'Con Crédito Fiscal', 7),
(5, '2025-06-19', 'C-1005', '3344556', 'Inversiones Beta', '1200.00', 'Con Crédito Fiscal', 7),
(6, '2025-06-03', 'C-1006', '4455667', 'Proveedor Dos S.A.', '2100.00', 'Con Crédito Fiscal', 7),
(7, '2025-06-13', 'C-1007', '5566778', 'Servicios XYZ Ltda.', '1750.00', 'Con Crédito Fiscal', 7),
(8, '2025-06-13', 'C-2001', '6677889', 'Proveedor Tres EIRL', '999.00', 'Con Crédito Fiscal', 6),
(9, '2025-06-13', 'C-3001', '7788990', 'Cliente Dos', '3300.00', 'Con Crédito Fiscal', 8),
(10, '2025-06-13', 'C-3002', '8899001', 'Cliente Tres', '4500.00', 'Sin Crédito Fiscal', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consolidaciones`
--

CREATE TABLE `consolidaciones` (
  `id` int(11) NOT NULL,
  `gestion` int(11) NOT NULL,
  `periodo` varchar(20) NOT NULL,
  `mes` int(11) NOT NULL,
  `total_ventas` decimal(10,2) DEFAULT NULL,
  `total_compras` decimal(10,2) DEFAULT NULL,
  `cantidad_ventas` int(11) DEFAULT NULL,
  `cantidad_compras` int(11) DEFAULT NULL,
  `fecha_consolidacion` datetime DEFAULT current_timestamp(),
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `consolidaciones`
--

INSERT INTO `consolidaciones` (`id`, `gestion`, `periodo`, `mes`, `total_ventas`, `total_compras`, `cantidad_ventas`, `cantidad_compras`, `fecha_consolidacion`, `id_cliente`) VALUES
(15, 2025, 'Junio', 6, '1223.00', '0.00', 3, 0, '2025-06-12 02:09:13', 7),
(16, 2025, 'Enero', 1, '0.00', '1000.00', 0, 1, '2025-06-12 02:11:40', 7),
(17, 2025, 'Julio', 7, '0.00', '0.00', 0, 0, '2025-06-12 02:12:45', 7),
(18, 2025, 'Febrero', 2, '0.00', '0.00', 0, 0, '2025-06-12 02:23:43', 7),
(19, 2025, 'Mayo', 5, '123.00', '0.00', 1, 0, '2025-06-12 03:53:27', 8),
(20, 0, '', 0, '16900.00', '11300.00', 6, 7, '2025-06-13 20:05:11', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proceso`
--

CREATE TABLE `proceso` (
  `codFlujo` varchar(10) DEFAULT NULL,
  `codProceso` varchar(10) DEFAULT NULL,
  `codProcesoSiguiente` varchar(10) DEFAULT NULL,
  `codRol` varchar(5) DEFAULT NULL,
  `pantalla` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `proceso`
--

INSERT INTO `proceso` (`codFlujo`, `codProceso`, `codProcesoSiguiente`, `codRol`, `pantalla`) VALUES
('f1', 'p1', 'p2', '1', 'ventas.php'),
('f1', 'p2', 'p3', '1', 'compras.php'),
('f1', 'p3', 'p1', '1', 'consolidacion.php'),
('f2', 'p1', 'p2', '2', 'mostrarventas.php'),
('f2', 'p2', 'p3', '2', 'mostrarcompras.php'),
('f2', 'p3', 'p1', '2', 'mostrarConsolidacion.php');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `codRol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`codRol`, `rol`) VALUES
(1, 'contador'),
(2, 'cliente'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `codRol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `codRol`) VALUES
(1, 'admin', '123', 3),
(6, 'juan', '123', 1),
(7, 'cliente123', '123', 2),
(8, '1', '1', 2),
(9, '2', '2', 2),
(10, '5', '5', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `nro_factura` varchar(20) DEFAULT NULL,
  `nit_ci` varchar(50) DEFAULT NULL,
  `razon_social` varchar(100) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_cliente`, `fecha`, `nro_factura`, `nit_ci`, `razon_social`, `monto`, `metodo_pago`) VALUES
(1, 7, '2025-06-12', 'F-NUEVOOooooooooo', '1234567', 'Empresa ABC S.R.L.', '1500.00', 'Tarjeta'),
(2, 7, '2025-06-10', 'F-1002', '2345678', 'Servicios XYZ Ltda.', '2500.00', 'Transferencia'),
(3, 7, '2025-05-07', 'F-1003', '3456789', 'Comercial Delta', '3200.00', 'Tarjeta'),
(4, 7, '2025-06-26', 'F-1004', '4567890', 'Inversiones Beta', '4100.00', 'Efectivo'),
(5, 7, '2023-06-11', 'F-1005', '5678901', 'Distribuidora Gamma', '5000.00', 'Efectivo'),
(6, 7, '2025-06-10', 'F-NUEVO', '6789012', 'Cliente Uno222', '600.00', 'QR'),
(7, 8, '2025-06-11', 'F-2001', '7890123', 'Cliente Dos', '700.00', 'Efectivo'),
(14, 8, '2025-06-13', 'F-2002', '9012345', 'Cliente Tres', '900.00', 'Transferencia'),
(17, 10, '2025-06-13', '55555', '55555', '555555', '55555.00', 'Efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `consolidaciones`
--
ALTER TABLE `consolidaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`codRol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `codRol` (`codRol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ventas_usuario` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `consolidaciones`
--
ALTER TABLE `consolidaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`codRol`) REFERENCES `rol` (`codRol`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_usuario` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
