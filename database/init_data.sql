-- =====================================================
-- Script de inicialización de datos para Muver
-- Ejecutar en MariaDB/MySQL después de crear las tablas
-- =====================================================

-- Insertar tipos de servicio
INSERT INTO `tipo_servicio` (`id_tipo_servicio`, `descripcion`, `estado`) VALUES
(1, 'Mudanza Residencial', 1),
(2, 'Mudanza Comercial/Oficinas', 1),
(3, 'Flete Pequeño', 1),
(4, 'Flete Grande', 1),
(5, 'Mudanza Internacional', 1);

-- Insertar regiones de Chile
INSERT INTO `region` (`id_region`, `nombre_region`, `codigo_region`, `estado`) VALUES
(1, 'Arica y Parinacota', 'XV', 1),
(2, 'Tarapacá', 'I', 1),
(3, 'Antofagasta', 'II', 1),
(4, 'Atacama', 'III', 1),
(5, 'Coquimbo', 'IV', 1),
(6, 'Valparaíso', 'V', 1),
(7, 'Metropolitana de Santiago', 'RM', 1),
(8, 'O''Higgins', 'VI', 1),
(9, 'Maule', 'VII', 1),
(10, 'Ñuble', 'XVI', 1),
(11, 'Biobío', 'VIII', 1),
(12, 'La Araucanía', 'IX', 1),
(13, 'Los Ríos', 'XIV', 1),
(14, 'Los Lagos', 'X', 1),
(15, 'Aysén', 'XI', 1),
(16, 'Magallanes', 'XII', 1);
