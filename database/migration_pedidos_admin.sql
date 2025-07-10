-- Migración para asegurar que las tablas tengan todos los campos necesarios para la gestión de pedidos

-- Tabla Pedidos: asegurar que tenga todos los campos necesarios
ALTER TABLE `Pedidos` 
ADD COLUMN IF NOT EXISTS `creado_por_admin` TINYINT(1) DEFAULT 0 AFTER `metodo_pago`;

-- Tabla Productos: asegurar que tenga el campo stock si no existe
ALTER TABLE `Productos` 
ADD COLUMN IF NOT EXISTS `stock` INT DEFAULT 0 AFTER `precio`;

-- Tabla Usuarios: asegurar que tenga el campo telefono si no existe
ALTER TABLE `Usuarios` 
ADD COLUMN IF NOT EXISTS `telefono` VARCHAR(20) DEFAULT NULL AFTER `correo`;

-- Insertar un cliente de prueba para testing (solo si no existe)
INSERT IGNORE INTO `Usuarios` (id_usuario, nombre, correo, telefono, activo) 
VALUES (999, 'Cliente de Prueba', 'cliente@prueba.com', '123-456-7890', 1);

-- Actualizar stock de productos existentes si están en 0
UPDATE `Productos` SET `stock` = 10 WHERE `stock` = 0 OR `stock` IS NULL;
