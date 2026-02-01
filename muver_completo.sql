-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2026 at 11:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muver`
--

-- --------------------------------------------------------

--
-- Table structure for table `busqueda_servicio`
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
-- Table structure for table `cotizacion`
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
-- Table structure for table `precio_combustible`
--

CREATE TABLE `precio_combustible` (
  `id_combustible` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `precio_combustible`
--

INSERT INTO `precio_combustible` (`id_combustible`, `descripcion`, `precio`, `fecha`, `estado`) VALUES
(1, 'Bencina 93 Octanos', 1150.00, '2026-02-01', 1),
(2, 'Bencina 95 Octanos', 1220.00, '2026-02-01', 1),
(3, 'Bencina 97 Octanos', 1290.00, '2026-02-01', 1),
(4, 'Diesel', 980.00, '2026-02-01', 1),
(5, 'Diesel Premium', 1050.00, '2026-02-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `rut` int(11) NOT NULL,
  `dv` char(1) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `km_x_litro` int(11) NOT NULL,
  `precio_base_km` decimal(10,2) DEFAULT 500.00,
  `precio_minimo` decimal(10,2) DEFAULT 25000.00,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `calificacion` decimal(2,1) DEFAULT 4.0,
  `total_servicios` int(11) DEFAULT 0,
  `id_region_domicilio` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `id_combustible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `rut`, `dv`, `nombre`, `direccion`, `latitud`, `longitud`, `km_x_litro`, `precio_base_km`, `precio_minimo`, `telefono`, `email`, `logo_url`, `calificacion`, `total_servicios`, `id_region_domicilio`, `estado`, `id_combustible`) VALUES
(1, 76543210, 'K', 'Mudanzas Express Santiago', 'Av. Providencia 1234, Providencia', -33.42800000, -70.61000000, 8, 450.00, 25000.00, '+56 9 1234 5678', 'contacto@mudanzasexpress.cl', NULL, 4.8, 523, 7, 1, 4),
(2, 76543211, '1', 'Fletes Rápidos Chile', 'Gran Avenida 5678, San Miguel', -33.49500000, -70.65100000, 8, 400.00, 20000.00, '+56 9 2345 6789', 'ventas@fletesrapidos.cl', NULL, 4.5, 312, 7, 1, 4),
(3, 76543212, '2', 'TransMueve SpA', 'Av. Apoquindo 4500, Las Condes', -33.41000000, -70.57500000, 7, 550.00, 30000.00, '+56 9 3456 7890', 'info@transmueve.cl', NULL, 4.9, 847, 7, 1, 4),
(4, 76543213, '3', 'Mudanzas del Sur', 'Av. Vicuña Mackenna 8900, La Florida', -33.52000000, -70.59000000, 9, 380.00, 22000.00, '+56 9 4567 8901', 'contacto@mudanzasdelsur.cl', NULL, 4.3, 198, 7, 1, 4),
(5, 76543214, '4', 'Carga Segura Ltda', 'Av. Libertador Bernardo O\'Higgins 3200, Santiago', -33.45000000, -70.66000000, 7, 480.00, 28000.00, '+56 9 5678 9012', 'ventas@cargasegura.cl', NULL, 4.7, 421, 7, 1, 4),
(6, 76543215, '5', 'Mudanzas Profesionales', 'Av. Irarrázaval 2500, Ñuñoa', -33.45300000, -70.60000000, 9, 420.00, 23000.00, '+56 9 6789 0123', 'info@mudanzaspro.cl', NULL, 4.6, 276, 7, 1, 4),
(7, 76543216, '6', 'Fletes Económicos', 'Av. Recoleta 1800, Recoleta', -33.41500000, -70.64500000, 10, 350.00, 18000.00, '+56 9 7890 1234', 'contacto@fleteseco.cl', NULL, 4.2, 156, 7, 1, 1),
(8, 76543217, '7', 'MoverChile Express', 'Av. Matta 1200, Santiago Centro', -33.46200000, -70.63800000, 8, 430.00, 24000.00, '+56 9 8901 2345', 'ventas@moverchile.cl', NULL, 4.4, 289, 7, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `proveedor_region`
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
-- Table structure for table `region`
--

CREATE TABLE `region` (
  `id_region` int(11) NOT NULL,
  `nombre_region` varchar(100) NOT NULL,
  `codigo_region` varchar(10) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id_region`, `nombre_region`, `codigo_region`, `estado`) VALUES
(1, 'Arica y Parinacota', 'XV', 1),
(2, 'Tarapacá', 'I', 1),
(3, 'Antofagasta', 'II', 1),
(4, 'Atacama', 'III', 1),
(5, 'Coquimbo', 'IV', 1),
(6, 'Valparaíso', 'V', 1),
(7, 'Metropolitana de Santiago', 'RM', 1),
(8, 'O\'Higgins', 'VI', 1),
(9, 'Maule', 'VII', 1),
(10, 'Ñuble', 'XVI', 1),
(11, 'Biobío', 'VIII', 1),
(12, 'La Araucanía', 'IX', 1),
(13, 'Los Ríos', 'XIV', 1),
(14, 'Los Lagos', 'X', 1),
(15, 'Aysén', 'XI', 1),
(16, 'Magallanes', 'XII', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_servicio`
--

CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio_base` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipo_servicio`
--

INSERT INTO `tipo_servicio` (`id_tipo_servicio`, `descripcion`, `precio_base`, `estado`) VALUES
(1, 'Mudanza Residencial', 200000, 1),
(2, 'Mudanza Comercial/Oficinas', 200000, 1),
(3, 'Flete Pequeño', 125000, 1),
(4, 'Flete Grande', 150000, 1),
(5, 'Mudanza Internacional', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `busqueda_servicio`
--
ALTER TABLE `busqueda_servicio`
  ADD PRIMARY KEY (`id_busqueda`),
  ADD KEY `id_tipo_servicio` (`id_tipo_servicio`);

--
-- Indexes for table `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `id_busqueda` (`id_busqueda`);

--
-- Indexes for table `precio_combustible`
--
ALTER TABLE `precio_combustible`
  ADD PRIMARY KEY (`id_combustible`),
  ADD KEY `idx_fecha` (`fecha`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indexes for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD KEY `id_region_domicilio` (`id_region_domicilio`);

--
-- Indexes for table `proveedor_region`
--
ALTER TABLE `proveedor_region`
  ADD PRIMARY KEY (`id_proveedor_region`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_region_origen` (`id_region_origen`),
  ADD KEY `id_region_destino` (`id_region_destino`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id_region`);

--
-- Indexes for table `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `busqueda_servicio`
--
ALTER TABLE `busqueda_servicio`
  MODIFY `id_busqueda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `precio_combustible`
--
ALTER TABLE `precio_combustible`
  MODIFY `id_combustible` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `proveedor_region`
--
ALTER TABLE `proveedor_region`
  MODIFY `id_proveedor_region` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `id_region` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  MODIFY `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `busqueda_servicio`
--
ALTER TABLE `busqueda_servicio`
  ADD CONSTRAINT `busqueda_servicio_ibfk_1` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`);

--
-- Constraints for table `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_busqueda`) REFERENCES `busqueda_servicio` (`id_busqueda`);

--
-- Constraints for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_region_domicilio`) REFERENCES `region` (`id_region`);

--
-- Constraints for table `proveedor_region`
--
ALTER TABLE `proveedor_region`
  ADD CONSTRAINT `proveedor_region_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `proveedor_region_ibfk_2` FOREIGN KEY (`id_region_origen`) REFERENCES `region` (`id_region`),
  ADD CONSTRAINT `proveedor_region_ibfk_3` FOREIGN KEY (`id_region_destino`) REFERENCES `region` (`id_region`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
