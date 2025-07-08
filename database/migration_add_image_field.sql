-- Script de migración para asegurar que la tabla productos tenga el campo imagen_url
-- Ejecutar este script si la tabla productos no tiene el campo imagen_url

-- Verificar si el campo imagen_url existe, si no existe, agregarlo
ALTER TABLE `productos` 
ADD COLUMN IF NOT EXISTS `imagen_url` VARCHAR(255) DEFAULT NULL 
AFTER `descripcion`;

-- Asegurar que la tabla productos tenga el campo activo para soft delete
ALTER TABLE `productos` 
ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) DEFAULT 1;

-- Asegurar que la tabla categorias tenga el campo activo
ALTER TABLE `categorias` 
ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) DEFAULT 1;

-- Asegurar que la tabla proveedores tenga el campo activo
ALTER TABLE `proveedores` 
ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) DEFAULT 1;

-- Asegurar que la tabla usuarios tenga el campo activo
ALTER TABLE `usuarios` 
ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) DEFAULT 1;
