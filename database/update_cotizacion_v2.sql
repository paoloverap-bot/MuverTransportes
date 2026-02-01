-- =====================================================
-- Script para agregar precio_base a tipo_servicio,
-- crear tabla precio_combustible y agregar km_x_litro a proveedor
-- Ejecutar en MariaDB/MySQL
-- =====================================================

-- 1. Agregar precio_base a tipo_servicio
ALTER TABLE `tipo_servicio` 
ADD COLUMN `precio_base` DECIMAL(10, 2) DEFAULT 15000.00 AFTER `descripcion`;

-- Actualizar precios base por tipo de servicio
UPDATE `tipo_servicio` SET `precio_base` = 15000.00 WHERE `id_tipo_servicio` = 1; -- Mudanza Residencial
UPDATE `tipo_servicio` SET `precio_base` = 25000.00 WHERE `id_tipo_servicio` = 2; -- Mudanza Comercial/Oficinas
UPDATE `tipo_servicio` SET `precio_base` = 10000.00 WHERE `id_tipo_servicio` = 3; -- Flete Pequeño
UPDATE `tipo_servicio` SET `precio_base` = 18000.00 WHERE `id_tipo_servicio` = 4; -- Flete Grande
UPDATE `tipo_servicio` SET `precio_base` = 50000.00 WHERE `id_tipo_servicio` = 5; -- Mudanza Internacional

-- 2. Crear tabla precio_combustible
CREATE TABLE IF NOT EXISTS `precio_combustible` (
  `id_combustible` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(100) NOT NULL,
  `precio` DECIMAL(10, 2) NOT NULL,
  `fecha` DATE NOT NULL,
  `estado` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id_combustible`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar precios de combustible (valores aproximados Chile 2026)
INSERT INTO `precio_combustible` (`descripcion`, `precio`, `fecha`, `estado`) VALUES
('Bencina 93 Octanos', 1150.00, '2026-02-01', 1),
('Bencina 95 Octanos', 1220.00, '2026-02-01', 1),
('Bencina 97 Octanos', 1290.00, '2026-02-01', 1),
('Diesel', 980.00, '2026-02-01', 1),
('Diesel Premium', 1050.00, '2026-02-01', 1);

-- 3. Agregar km_x_litro a proveedor (rendimiento del camión)
ALTER TABLE `proveedor` 
ADD COLUMN `km_x_litro` DECIMAL(5, 2) DEFAULT 8.00 AFTER `total_servicios`,
ADD COLUMN `id_combustible` INT(11) DEFAULT 4 AFTER `km_x_litro`;

-- Agregar foreign key para combustible
ALTER TABLE `proveedor`
ADD CONSTRAINT `fk_proveedor_combustible` FOREIGN KEY (`id_combustible`) REFERENCES `precio_combustible` (`id_combustible`);

-- Actualizar proveedores existentes con rendimientos variados
UPDATE `proveedor` SET `km_x_litro` = 7.5, `id_combustible` = 4 WHERE `id_proveedor` = 1;  -- Diesel, 7.5 km/L
UPDATE `proveedor` SET `km_x_litro` = 8.0, `id_combustible` = 4 WHERE `id_proveedor` = 2;  -- Diesel, 8.0 km/L
UPDATE `proveedor` SET `km_x_litro` = 6.5, `id_combustible` = 4 WHERE `id_proveedor` = 3;  -- Diesel, 6.5 km/L (camión grande)
UPDATE `proveedor` SET `km_x_litro` = 9.0, `id_combustible` = 4 WHERE `id_proveedor` = 4;  -- Diesel, 9.0 km/L (furgón)
UPDATE `proveedor` SET `km_x_litro` = 7.0, `id_combustible` = 4 WHERE `id_proveedor` = 5;  -- Diesel, 7.0 km/L
UPDATE `proveedor` SET `km_x_litro` = 8.5, `id_combustible` = 4 WHERE `id_proveedor` = 6;  -- Diesel, 8.5 km/L
UPDATE `proveedor` SET `km_x_litro` = 10.0, `id_combustible` = 1 WHERE `id_proveedor` = 7; -- Bencina 93, 10 km/L (furgoneta)
UPDATE `proveedor` SET `km_x_litro` = 7.8, `id_combustible` = 4 WHERE `id_proveedor` = 8;  -- Diesel, 7.8 km/L

