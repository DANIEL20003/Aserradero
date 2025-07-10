-- Migración para crear la tabla Facturas
-- Ejecutar después de crear las tablas de Pedidos

CREATE TABLE IF NOT EXISTS `Facturas` (
  `id_factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `secuencial` varchar(50) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_factura`),
  UNIQUE KEY `unique_pedido_factura` (`id_pedido`),
  UNIQUE KEY `unique_secuencial` (`secuencial`),
  KEY `idx_fecha_emision` (`fecha_emision`),
  KEY `idx_activo` (`activo`),
  CONSTRAINT `fk_facturas_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `Pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices adicionales para optimizar consultas
CREATE INDEX `idx_facturas_secuencial_activo` ON `Facturas` (`secuencial`, `activo`);
CREATE INDEX `idx_facturas_pedido_activo` ON `Facturas` (`id_pedido`, `activo`);
