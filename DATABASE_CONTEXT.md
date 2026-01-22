# Contexto de Base de daTos

## PROPOSITO
Almacenar ejecuccion de busquedas de servicios, proveedores, proveedores por regiones, y cotizaciones de servicios (mudanzas, flete)

--
-- Base de datos: `muver`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `busqueda_servicio`
--

CREATE TABLE `busqueda_servicio` (
  `id_busqueda` int(11) NOT NULL,
  `fecha_busqueda` datetime NOT NULL,
  `lugar_partida` varchar(255) NOT NULL,
  `lugar_destino` varchar(255) NOT NULL,
  `region_origen` varchar(100) DEFAULT NULL,
  `region_destino` varchar(100) DEFAULT NULL,
  `distancia_km` decimal(8,2) DEFAULT NULL,
  `duracion_min` int(11) DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `dispositivo` enum('mobile','desktop','tablet') DEFAULT NULL,
  `convertido_cotizacion` tinyint(1) DEFAULT 0,
  `id_tipo_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `id_busqueda` int(11) NOT NULL,
  `fecha_cotizacion` datetime NOT NULL,
  `fecha_inicio_servicio` date NOT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `recargos` decimal(10,2) DEFAULT 0.00,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `precio_final` decimal(10,2) NOT NULL,
  `estado` enum('generada','enviada','aceptada','rechazada') DEFAULT 'generada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `rut` int(11) NOT NULL,
  `dv` char(1) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `id_region_domicilio` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_region`
--

CREATE TABLE `proveedor_region` (
  `id_proveedor_region` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_region_origen` int(11) NOT NULL,
  `id_region_destino` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `region`
--

CREATE TABLE `region` (
  `id_region` int(11) NOT NULL,
  `nombre_region` varchar(100) NOT NULL,
  `codigo_region` varchar(10) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_servicio`
--

CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `busqueda_servicio`
--
ALTER TABLE `busqueda_servicio`
  ADD PRIMARY KEY (`id_busqueda`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `id_busqueda` (`id_busqueda`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD KEY `id_region_domicilio` (`id_region_domicilio`);

--
-- Indices de la tabla `proveedor_region`
--
ALTER TABLE `proveedor_region`
  ADD PRIMARY KEY (`id_proveedor_region`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_region_origen` (`id_region_origen`),
  ADD KEY `id_region_destino` (`id_region_destino`);

--
-- Indices de la tabla `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id_region`);

--
-- Indices de la tabla `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `busqueda_servicio`
--
ALTER TABLE `busqueda_servicio`
  MODIFY `id_busqueda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor_region`
--
ALTER TABLE `proveedor_region`
  MODIFY `id_proveedor_region` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `region`
--
ALTER TABLE `region`
  MODIFY `id_region` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  MODIFY `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `busqueda_servicio`
--
ALTER TABLE `busqueda_servicio`
  ADD CONSTRAINT `busqueda_servicio_ibfk_1` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`);

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_busqueda`) REFERENCES `busqueda_servicio` (`id_busqueda`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_region_domicilio`) REFERENCES `region` (`id_region`);

--
-- Filtros para la tabla `proveedor_region`
--
ALTER TABLE `proveedor_region`
  ADD CONSTRAINT `proveedor_region_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `proveedor_region_ibfk_2` FOREIGN KEY (`id_region_origen`) REFERENCES `region` (`id_region`),
  ADD CONSTRAINT `proveedor_region_ibfk_3` FOREIGN KEY (`id_region_destino`) REFERENCES `region` (`id_region`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
