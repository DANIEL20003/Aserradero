-- Migración para agregar campos de montos a la tabla Facturas
-- Ejecutar después de crear la tabla Facturas básica

ALTER TABLE `Facturas` 
ADD COLUMN `subtotal` DECIMAL(10,2) NULL AFTER `fecha_emision`,
ADD COLUMN `iva_porcentaje` DECIMAL(5,2) NULL AFTER `subtotal`,
ADD COLUMN `iva_monto` DECIMAL(10,2) NULL AFTER `iva_porcentaje`,
ADD COLUMN `total` DECIMAL(10,2) NULL AFTER `iva_monto`;

-- Agregar índices para optimizar consultas por montos
CREATE INDEX `idx_facturas_total` ON `Facturas` (`total`);
CREATE INDEX `idx_facturas_subtotal` ON `Facturas` (`subtotal`);

-- Comentarios para documentar los campos
ALTER TABLE `Facturas` 
MODIFY COLUMN `subtotal` DECIMAL(10,2) NULL COMMENT 'Subtotal sin IVA',
MODIFY COLUMN `iva_porcentaje` DECIMAL(5,2) NULL COMMENT 'Porcentaje de IVA aplicado',
MODIFY COLUMN `iva_monto` DECIMAL(10,2) NULL COMMENT 'Monto del IVA',
MODIFY COLUMN `total` DECIMAL(10,2) NULL COMMENT 'Total final con IVA';
