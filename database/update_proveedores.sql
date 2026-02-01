-- =====================================================
-- Script para agregar coordenadas a proveedores
-- Ejecutar en MariaDB/MySQL
-- =====================================================

-- Agregar columnas de coordenadas a la tabla proveedor
ALTER TABLE `proveedor` 
ADD COLUMN `latitud` DECIMAL(10, 8) NULL AFTER `direccion`,
ADD COLUMN `longitud` DECIMAL(11, 8) NULL AFTER `latitud`,
ADD COLUMN `precio_base_km` DECIMAL(10, 2) DEFAULT 500.00 AFTER `longitud`,
ADD COLUMN `precio_minimo` DECIMAL(10, 2) DEFAULT 25000.00 AFTER `precio_base_km`,
ADD COLUMN `telefono` VARCHAR(20) NULL AFTER `precio_minimo`,
ADD COLUMN `email` VARCHAR(255) NULL AFTER `telefono`,
ADD COLUMN `logo_url` VARCHAR(255) NULL AFTER `email`,
ADD COLUMN `calificacion` DECIMAL(2, 1) DEFAULT 4.0 AFTER `logo_url`,
ADD COLUMN `total_servicios` INT DEFAULT 0 AFTER `calificacion`;

-- Insertar proveedores de ejemplo con coordenadas reales de Chile
INSERT INTO `proveedor` (`rut`, `dv`, `nombre`, `direccion`, `latitud`, `longitud`, `id_region_domicilio`, `precio_base_km`, `precio_minimo`, `telefono`, `email`, `calificacion`, `total_servicios`, `estado`) VALUES
(76543210, 'K', 'Mudanzas Express Santiago', 'Av. Providencia 1234, Providencia', -33.4280, -70.6100, 7, 450.00, 25000.00, '+56 9 1234 5678', 'contacto@mudanzasexpress.cl', 4.8, 523, 1),
(76543211, '1', 'Fletes Rápidos Chile', 'Gran Avenida 5678, San Miguel', -33.4950, -70.6510, 7, 400.00, 20000.00, '+56 9 2345 6789', 'ventas@fletesrapidos.cl', 4.5, 312, 1),
(76543212, '2', 'TransMueve SpA', 'Av. Apoquindo 4500, Las Condes', -33.4100, -70.5750, 7, 550.00, 30000.00, '+56 9 3456 7890', 'info@transmueve.cl', 4.9, 847, 1),
(76543213, '3', 'Mudanzas del Sur', 'Av. Vicuña Mackenna 8900, La Florida', -33.5200, -70.5900, 7, 380.00, 22000.00, '+56 9 4567 8901', 'contacto@mudanzasdelsur.cl', 4.3, 198, 1),
(76543214, '4', 'Carga Segura Ltda', 'Av. Libertador Bernardo O''Higgins 3200, Santiago', -33.4500, -70.6600, 7, 480.00, 28000.00, '+56 9 5678 9012', 'ventas@cargasegura.cl', 4.7, 421, 1),
(76543215, '5', 'Mudanzas Profesionales', 'Av. Irarrázaval 2500, Ñuñoa', -33.4530, -70.6000, 7, 420.00, 23000.00, '+56 9 6789 0123', 'info@mudanzaspro.cl', 4.6, 276, 1),
(76543216, '6', 'Fletes Económicos', 'Av. Recoleta 1800, Recoleta', -33.4150, -70.6450, 7, 350.00, 18000.00, '+56 9 7890 1234', 'contacto@fleteseco.cl', 4.2, 156, 1),
(76543217, '7', 'MoverChile Express', 'Av. Matta 1200, Santiago Centro', -33.4620, -70.6380, 7, 430.00, 24000.00, '+56 9 8901 2345', 'ventas@moverchile.cl', 4.4, 289, 1);

