-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 20-12-2024 a las 05:49:01
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `adsrocas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletaemitida`
--

CREATE TABLE `boletaemitida` (
  `BoletaEmitidaID` int NOT NULL,
  `SerieComprobanteID` int NOT NULL,
  `NumeroCorrelativo` int NOT NULL,
  `UsuarioID` int NOT NULL,
  `FechaEmision` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `TipoPago` varchar(255) NOT NULL,
  `FormaPago` varchar(255) NOT NULL,
  `Moneda` varchar(255) NOT NULL,
  `ClienteID` int NOT NULL,
  `EstadoDocumento` varchar(255) NOT NULL,
  `OpInafecta` double NOT NULL,
  `OpExonerada` double NOT NULL,
  `OpGratuita` double NOT NULL,
  `OpGravada` double NOT NULL,
  `TotalIGV` double NOT NULL,
  `DescuentoGlobal` double NOT NULL,
  `ImporteTotal` double NOT NULL,
  `CostoTotal` double NOT NULL,
  `Ganancia` double NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `Obra` varchar(255) NOT NULL,
  `OrdenDeCompra` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `boletaemitida`
--

INSERT INTO `boletaemitida` (`BoletaEmitidaID`, `SerieComprobanteID`, `NumeroCorrelativo`, `UsuarioID`, `FechaEmision`, `FechaVencimiento`, `TipoPago`, `FormaPago`, `Moneda`, `ClienteID`, `EstadoDocumento`, `OpInafecta`, `OpExonerada`, `OpGratuita`, `OpGravada`, `TotalIGV`, `DescuentoGlobal`, `ImporteTotal`, `CostoTotal`, `Ganancia`, `Observaciones`, `Obra`, `OrdenDeCompra`) VALUES
(1, 4, 1, 2, '2024-10-20', '2024-10-20', 'Al contado', 'Efectivo', 'PEN', 1, 'Emitida', 0, 0, 0, 1680, 302.4, 0, 1982.4, 1882.4, 100, 'Venta de productos gravados', 'Los Olivos', 123),
(2, 4, 2, 2, '2024-10-21', '2024-10-21', 'Al contado', 'Efectivo', 'PEN', 2, 'Emitida', 0, 0, 0, 186, 33.5, 0, 219.5, 119.5, 100, 'Venta de productos gravados', 'San Isidro', 234),
(3, 4, 3, 2, '2024-10-22', '2024-10-22', 'Al contado', 'Efectivo', 'PEN', 3, 'Emitida', 0, 0, 0, 2220, 394.8, 0, 2614.8, 2514.8, 100, 'Venta de productos gravados', 'Miraflores', 345),
(4, 4, 4, 1, '2024-12-20', '2024-12-20', 'Al contado', 'Efectivo', 'PEN', 9, 'Emitida', 0, 0, 0, 6500, 1170, 0, 7670, 6500, 1170, 'Boleta generada desde el sistema', 'playa', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletaemitidadetalles`
--

CREATE TABLE `boletaemitidadetalles` (
  `BoletaEmitidaDetallesID` int NOT NULL,
  `BoletaEmitidaID` int NOT NULL,
  `ProductoDetallesID` int NOT NULL,
  `Cantidad` int NOT NULL,
  `PrecioUnitarioVenta` double NOT NULL,
  `ValorUnitarioVenta` double NOT NULL,
  `Descuento` double NOT NULL,
  `Total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `boletaemitidadetalles`
--

INSERT INTO `boletaemitidadetalles` (`BoletaEmitidaDetallesID`, `BoletaEmitidaID`, `ProductoDetallesID`, `Cantidad`, `PrecioUnitarioVenta`, `ValorUnitarioVenta`, `Descuento`, `Total`) VALUES
(1, 1, 1, 10, 184.08, 156, 0, 1840.8),
(2, 1, 2, 20, 7.08, 6, 0, 141.6),
(3, 2, 3, 50, 3.54, 3, 0, 177),
(4, 2, 4, 10, 4.25, 3.6, 0, 42.5),
(5, 3, 5, 5, 14.16, 12, 0, 70.8),
(6, 3, 6, 100, 25.44, 21.6, 0, 2544),
(7, 4, 1, 50, 130, 130, 0, 6500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ClienteID` int NOT NULL,
  `TipoDocumentoIdentidad` varchar(255) NOT NULL,
  `NumeroDocumentoIdentidad` varchar(255) NOT NULL,
  `NombreCompletoORazonSocial` varchar(255) NOT NULL,
  `Telefono` int NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Direccion` varchar(255) NOT NULL,
  `FechaRegistro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ClienteID`, `TipoDocumentoIdentidad`, `NumeroDocumentoIdentidad`, `NombreCompletoORazonSocial`, `Telefono`, `Email`, `Direccion`, `FechaRegistro`) VALUES
(1, 'DNI', '45678912', 'Juan Carlos Gonzales Ramos', 998765432, 'juangonzales@gmail.com', 'AV. PRIMAVERA 300, LIMA', '2024-10-21'),
(2, 'DNI', '87654321', 'María Fernanda Torres Vega', 987123456, 'mariatorres@gmail.com', 'CALLE BELEN 128, MIRAFLORES', '2024-10-20'),
(3, 'DNI', '65432198', 'José Manuel Castro Vargas', 912345678, 'josecastro@gmail.com', 'JR. PUNO 789, SAN BORJA', '2024-10-19'),
(4, 'DNI', '12345678', 'Carla Andrea Silva Quispe', 965432187, 'carla.silva@gmail.com', 'CALLE MORALES 425, LA MOLINA', '2024-10-18'),
(5, 'RUC', '20546781234', 'CONSTRUCTORA EL SOL S.A.C.', 912345123, 'contacto@constructorelsol.com', 'JR. LAS PALMERAS 530, SAN ISIDRO', '2024-10-17'),
(6, 'RUC', '20487654321', 'INGENIERÍA GLOBAL PERÚ S.A.', 921345678, 'ventas@globalperu.com', 'AV. LOS INGENIEROS 150, SAN MIGUEL', '2024-10-16'),
(7, 'RUC', '20345678123', 'PROYEC CONTRATISTAS GENERALES S.A.', 987654321, 'ventas@proyec.com', 'AV. LA MARINA 2050, PUEBLO LIBRE', '2024-10-15'),
(8, 'DNI', '27221472752', 'roca', 0, '', 'av las palmas', '2024-12-20'),
(9, 'DNI', '2722147275233', 'roca', 0, '', 'av las palmas', '2024-12-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobanterecibido`
--

CREATE TABLE `comprobanterecibido` (
  `ComprobanteRecibidoID` int NOT NULL,
  `TipoComprobanteRecibido` varchar(255) NOT NULL,
  `NumeroSerieYCorrelativo` varchar(255) NOT NULL,
  `FechaEmision` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `TipoPago` varchar(255) NOT NULL,
  `FormaPago` varchar(255) NOT NULL,
  `Moneda` varchar(255) NOT NULL,
  `ProveedorID` int NOT NULL,
  `Op.Inafecta` double NOT NULL,
  `Op.Exonerada` double NOT NULL,
  `Op.Gratuita` double NOT NULL,
  `Op.Gravada` double NOT NULL,
  `TotalIGV` double NOT NULL,
  `DescuentoGlobal` double NOT NULL,
  `ImporteTotal` double NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `Archivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `comprobanterecibido`
--

INSERT INTO `comprobanterecibido` (`ComprobanteRecibidoID`, `TipoComprobanteRecibido`, `NumeroSerieYCorrelativo`, `FechaEmision`, `FechaVencimiento`, `TipoPago`, `FormaPago`, `Moneda`, `ProveedorID`, `Op.Inafecta`, `Op.Exonerada`, `Op.Gratuita`, `Op.Gravada`, `TotalIGV`, `DescuentoGlobal`, `ImporteTotal`, `Observaciones`, `Archivo`) VALUES
(1, 'Factura', 'F001-00012345', '2024-11-18', '2024-11-18', 'Al contado', 'Efectivo', 'PEN', 1, 0, 0, 0, 1300, 234, 0, 1534, 'Factura de compra de pinturas y discos de corte', 'F001-00012345.pdf'),
(2, 'Factura', 'F002-00012346', '2024-11-18', '2024-11-18', 'Al contado', 'Efectivo', 'PEN', 1, 0, 0, 0, 225, 40.5, 0, 265.5, 'Factura de compra de lijas para metal y sellador de silicona', 'F002-00012346.pdf'),
(3, 'Factura', 'F003-00022345', '2024-11-17', '2024-11-17', 'Al contado', 'Efectivo', 'PEN', 2, 0, 0, 0, 2900, 522, 0, 3422, 'Factura de compra de cables volcánicos', 'F003-00022345.pdf'),
(4, 'Factura', 'F004-00022346', '2024-11-17', '2024-11-17', 'Al contado', 'Efectivo', 'PEN', 2, 0, 0, 0, 2775, 499.5, 0, 3274.5, 'Factura de compra de cables volcánicos y cintas métricas', 'F004-00022346.pdf'),
(5, 'Factura', 'F005-00032345', '2024-11-16', '2024-11-16', 'Al contado', 'Efectivo', 'PEN', 3, 0, 0, 0, 2250, 405, 0, 2655, 'Factura de compra de pinturas y discos de corte', 'F005-00032345.pdf'),
(6, 'Factura', 'F006-00032346', '2024-11-16', '2024-11-16', 'Al contado', 'Efectivo', 'PEN', 3, 0, 0, 0, 600, 108, 0, 708, 'Factura de compra de lijas al agua', 'F006-00032346.pdf'),
(7, 'Factura', 'F007-00042345', '2024-11-15', '2024-11-15', 'Al contado', 'Efectivo', 'PEN', 4, 0, 0, 0, 8000, 1440, 0, 9440, 'Factura de compra de amoladoras y máquinas de carga', 'F007-00042345.pdf'),
(8, 'Factura', 'F008-00042346', '2024-11-15', '2024-11-15', 'Al contado', 'Efectivo', 'PEN', 4, 0, 0, 0, 4000, 720, 0, 4720, 'Factura de compra de taladros, llantas y bombillas LED', 'F008-00042346.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobanterecibidodetalles`
--

CREATE TABLE `comprobanterecibidodetalles` (
  `ComprobanteRecibidoDetallesID` int NOT NULL,
  `ComprobanteRecibidoID` int NOT NULL,
  `ProductoDetallesID` int NOT NULL,
  `Cantidad` int NOT NULL,
  `PrecioUnitarioVenta` double NOT NULL,
  `ValorUnitarioVenta` double NOT NULL,
  `Descuento` double NOT NULL,
  `Total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `comprobanterecibidodetalles`
--

INSERT INTO `comprobanterecibidodetalles` (`ComprobanteRecibidoDetallesID`, `ComprobanteRecibidoID`, `ProductoDetallesID`, `Cantidad`, `PrecioUnitarioVenta`, `ValorUnitarioVenta`, `Descuento`, `Total`) VALUES
(1, 1, 1, 10, 153.4, 130, 0, 1534),
(2, 1, 2, 20, 5.9, 5, 0, 118),
(3, 2, 3, 50, 2.95, 2.5, 0, 147.5),
(4, 2, 5, 10, 11.8, 10, 0, 118),
(5, 3, 6, 100, 21.24, 18, 0, 2124),
(6, 3, 7, 50, 29.5, 25, 0, 1475),
(7, 4, 6, 150, 21.24, 18, 0, 3186),
(8, 4, 14, 5, 17.7, 15, 0, 88.5),
(9, 5, 8, 30, 59, 50, 0, 1770),
(10, 5, 9, 100, 8.85, 7.5, 0, 885),
(11, 6, 4, 200, 3.54, 3, 0, 708),
(12, 7, 10, 5, 377.6, 320, 0, 1888),
(13, 7, 11, 2, 5900, 5000, 0, 11800),
(14, 8, 12, 10, 413, 350, 0, 4130),
(15, 8, 13, 4, 177, 150, 0, 708),
(16, 8, 15, 20, 29.5, 25, 0, 590);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacionemitida`
--

CREATE TABLE `cotizacionemitida` (
  `CotizacionEmitidaID` int NOT NULL,
  `SerieComprobanteID` int NOT NULL,
  `NumeroCorrelativo` int NOT NULL,
  `UsuarioID` int NOT NULL,
  `FechaEmision` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `TipoPago` varchar(255) NOT NULL,
  `FormaPago` varchar(255) NOT NULL,
  `Moneda` varchar(255) NOT NULL,
  `ClienteID` int NOT NULL,
  `Op.Inafecta` double NOT NULL,
  `Op.Exonerada` double NOT NULL,
  `Op.Gratuita` double NOT NULL,
  `Op.Gravada` double NOT NULL,
  `TotalIGV` double NOT NULL,
  `DescuentoGlobal` double NOT NULL,
  `ImporteTotal` double NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `Obra` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cotizacionemitida`
--

INSERT INTO `cotizacionemitida` (`CotizacionEmitidaID`, `SerieComprobanteID`, `NumeroCorrelativo`, `UsuarioID`, `FechaEmision`, `FechaVencimiento`, `TipoPago`, `FormaPago`, `Moneda`, `ClienteID`, `Op.Inafecta`, `Op.Exonerada`, `Op.Gratuita`, `Op.Gravada`, `TotalIGV`, `DescuentoGlobal`, `ImporteTotal`, `Observaciones`, `Obra`) VALUES
(1, 10, 1, 4, '2024-12-15', '2024-12-20', 'Al contado', 'Efectivo', 'PEN', 5, 0, 0, 0, 18120, 3257.6, 0, 21377.6, 'Venta de productos gravados', 'Lomas'),
(2, 10, 2, 5, '2024-12-15', '2024-12-20', 'Al contado', 'Efectivo', 'PEN', 6, 0, 0, 0, 2700, 486, 0, 3186, 'Venta de productos gravados', 'San Isidro'),
(3, 10, 3, 8, '2024-12-15', '2024-12-20', 'Al contado', 'Efectivo', 'PEN', 7, 0, 0, 0, 1320, 237.6, 0, 1557.6, 'Venta de productos gravados', 'Lomas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacionemitidadetalles`
--

CREATE TABLE `cotizacionemitidadetalles` (
  `CotizacionEmitidaDetallesID` int NOT NULL,
  `CotizacionEmitidaID` int NOT NULL,
  `ProductoDetallesID` int NOT NULL,
  `Cantidad` int NOT NULL,
  `PrecioUnitarioVenta` double NOT NULL,
  `ValorUnitarioVenta` double NOT NULL,
  `Descuento` double NOT NULL,
  `Total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cotizacionemitidadetalles`
--

INSERT INTO `cotizacionemitidadetalles` (`CotizacionEmitidaDetallesID`, `CotizacionEmitidaID`, `ProductoDetallesID`, `Cantidad`, `PrecioUnitarioVenta`, `ValorUnitarioVenta`, `Descuento`, `Total`) VALUES
(1, 1, 10, 5, 452.32, 384, 0, 2261.6),
(2, 1, 11, 2, 7080, 6000, 0, 14160),
(3, 1, 12, 10, 495.6, 420, 0, 4956),
(4, 2, 8, 30, 70.8, 60, 0, 2124),
(5, 2, 9, 100, 10.62, 9, 0, 1062),
(6, 3, 13, 4, 212.4, 180, 0, 849.6),
(7, 3, 15, 20, 35.4, 30, 0, 708);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturaemitida`
--

CREATE TABLE `facturaemitida` (
  `FacturaEmitidaID` int NOT NULL,
  `SerieComprobanteID` int NOT NULL,
  `NumeroCorrelativo` int NOT NULL,
  `UsuarioID` int NOT NULL,
  `FechaEmision` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `TipoPago` varchar(255) NOT NULL,
  `FormaPago` varchar(255) NOT NULL,
  `Moneda` varchar(255) NOT NULL,
  `ClienteID` int NOT NULL,
  `EstadoDocumento` varchar(255) NOT NULL,
  `OpInafecta` double NOT NULL,
  `OpExonerada` double NOT NULL,
  `OpGratuita` double NOT NULL,
  `OpGravada` double NOT NULL,
  `TotalIGV` double NOT NULL,
  `DescuentoGlobal` double NOT NULL,
  `ImporteTotal` double NOT NULL,
  `CostoTotal` double NOT NULL,
  `Ganancia` double NOT NULL,
  `Observaciones` varchar(255) NOT NULL,
  `Obra` varchar(255) NOT NULL,
  `OrdenDeCompra` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `facturaemitida`
--

INSERT INTO `facturaemitida` (`FacturaEmitidaID`, `SerieComprobanteID`, `NumeroCorrelativo`, `UsuarioID`, `FechaEmision`, `FechaVencimiento`, `TipoPago`, `FormaPago`, `Moneda`, `ClienteID`, `EstadoDocumento`, `OpInafecta`, `OpExonerada`, `OpGratuita`, `OpGravada`, `TotalIGV`, `DescuentoGlobal`, `ImporteTotal`, `CostoTotal`, `Ganancia`, `Observaciones`, `Obra`, `OrdenDeCompra`) VALUES
(1, 1, 1, 1, '2024-10-21', '2024-10-21', 'Al contado', 'Efectivo', 'PEN', 5, 'Emitida', 0, 0, 0, 18120, 3257.6, 0, 21377.6, 21277.6, 100, 'Venta de productos gravados', 'Villa Maria del Triunfo', 456),
(2, 1, 2, 1, '2024-10-18', '2024-10-18', 'Al contado', 'Efectivo', 'PEN', 6, 'Emitida', 0, 0, 0, 2700, 486, 0, 3186, 3086, 100, 'Venta de productos gravados', 'Ventanilla', 567),
(3, 1, 3, 1, '2024-10-15', '2024-10-15', 'Al contado', 'Efectivo', 'PEN', 7, 'Emitida', 0, 0, 0, 1320, 237.6, 0, 1557.6, 1457.6, 100, 'Venta de productos gravados', 'Villa El Salvador', 678),
(4, 1, 4, 1, '2024-12-20', '2024-12-20', 'Al contado', 'Efectivo', 'PEN', 8, 'Emitida', 0, 0, 0, 6500, 1170, 0, 7670, 6500, 1170, 'Factura generada desde el sistema', 'untelssss', 4),
(5, 1, 5, 1, '2024-12-20', '2024-12-20', 'Al contado', 'Efectivo', 'USD', 8, 'Emitida', 0, 0, 0, 375, 67.5, 0, 442.5, 375, 67.5, 'Factura generada desde el sistema', 'untelssss', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturaemitidadetalles`
--

CREATE TABLE `facturaemitidadetalles` (
  `FacturaEmitidaDetallesID` int NOT NULL,
  `FacturaEmitidaID` int NOT NULL,
  `ProductoDetallesID` int NOT NULL,
  `Cantidad` int NOT NULL,
  `PrecioUnitarioVenta` double NOT NULL,
  `ValorUnitarioVenta` double NOT NULL,
  `Descuento` double NOT NULL,
  `Total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `facturaemitidadetalles`
--

INSERT INTO `facturaemitidadetalles` (`FacturaEmitidaDetallesID`, `FacturaEmitidaID`, `ProductoDetallesID`, `Cantidad`, `PrecioUnitarioVenta`, `ValorUnitarioVenta`, `Descuento`, `Total`) VALUES
(1, 1, 10, 5, 452.32, 384, 0, 2261.6),
(2, 1, 11, 2, 7080, 6000, 0, 14160),
(3, 1, 12, 10, 495.6, 420, 0, 4956),
(4, 2, 8, 30, 70.8, 60, 0, 2124),
(5, 2, 9, 100, 10.62, 9, 0, 1062),
(6, 3, 13, 4, 212.4, 180, 0, 849.6),
(7, 3, 15, 20, 35.4, 30, 0, 708),
(8, 4, 1, 50, 130, 130, 0, 6500),
(9, 5, 9, 50, 7.5, 7.5, 0, 375);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ProductoID` int NOT NULL,
  `CodigoProducto` varchar(255) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `ValorUnitarioVenta` double NOT NULL,
  `EstadoProducto` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ProductoID`, `CodigoProducto`, `Descripcion`, `ValorUnitarioVenta`, `EstadoProducto`) VALUES
(1, 'PNT-EXTR-WB-001', 'Pintura exterior blanca 5 galones', 130, 1),
(2, 'DSC-CORT-MET-115', 'Disco de corte para metal 115 mm', 5, 1),
(3, 'LJA-MET-60-012', 'Lija para metal grano 60', 2.5, 1),
(4, 'AMOL-4P-750W-001', 'Amoladora 4 pulgadas 750W', 320, 1),
(5, 'CARG-MACH-002', 'Máquina de carga industrial', 5000, 1),
(6, 'ALM-GALV-14-003', 'Alambre galvanizado calibre 14', 150, 1),
(7, 'CBL-VOLC-3X4-004', 'Cable volcánico 3x4 mm²', 18, 1),
(8, 'PNT-INT-WB-005', 'Pintura interior color blanco 1 galón', 50, 1),
(9, 'DSC-CORT-HOR-125', 'Disco de corte para hormigón 125 mm', 7.5, 1),
(10, 'LJA-AGUA-220-006', 'Lija al agua grano 220', 3, 1),
(11, 'CBL-VOLC-3X6-007', 'Cable volcánico 3x6 mm²', 25, 1),
(12, 'MAQ-TALADRO-008', 'Taladro industrial 800W', 350, 1),
(13, 'SELL-SILICON-009', 'Sellador de silicona para exteriores', 10, 1),
(14, 'CINT-MEDID-10M-010', 'Cinta métrica de 10 metros', 15, 1),
(15, 'BULB-INDUST-011', 'Bombilla industrial 100W LED', 25, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productodetalles`
--

CREATE TABLE `productodetalles` (
  `ProductoDetallesID` int NOT NULL,
  `ProductoID` int NOT NULL,
  `UnidadMedida` varchar(255) NOT NULL,
  `ValorUnitarioCompra` double NOT NULL,
  `CategoriaFiscal` varchar(255) NOT NULL,
  `ProveedorID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productodetalles`
--

INSERT INTO `productodetalles` (`ProductoDetallesID`, `ProductoID`, `UnidadMedida`, `ValorUnitarioCompra`, `CategoriaFiscal`, `ProveedorID`) VALUES
(1, 1, 'Unidad', 130, 'Gravado', 1),
(2, 2, 'Unidad', 5, 'Gravado', 1),
(3, 3, 'Bolsa', 2.5, 'Gravado', 1),
(4, 10, 'Bolsa', 3, 'Gravado', 1),
(5, 13, 'Unidad', 10, 'Gravado', 1),
(6, 7, 'Metro', 18, 'Gravado', 2),
(7, 11, 'Metro', 25, 'Gravado', 2),
(8, 8, 'Unidad', 50, 'Gravado', 3),
(9, 9, 'Unidad', 7.5, 'Gravado', 3),
(10, 4, 'Unidad', 320, 'Gravado', 4),
(11, 5, 'Unidad', 5000, 'Gravado', 4),
(12, 12, 'Unidad', 350, 'Gravado', 4),
(13, 6, 'Unidad', 150, 'Gravado', 4),
(14, 14, 'Unidad', 15, 'Gravado', 2),
(15, 15, 'Unidad', 25, 'Gravado', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `ProveedorID` int NOT NULL,
  `NumeroRUC` varchar(255) NOT NULL,
  `RazonSocial` varchar(255) NOT NULL,
  `Telefono` int NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Direccion` varchar(255) NOT NULL,
  `FechaRegistro` date NOT NULL,
  `EstadoProveedor` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`ProveedorID`, `NumeroRUC`, `RazonSocial`, `Telefono`, `Email`, `Direccion`, `FechaRegistro`, `EstadoProveedor`) VALUES
(1, '2001215141', 'JEANKING', 986124589, 'ventas@provlima.com', 'AV. INDUSTRIAL 980, LIMA', '2024-10-21', 1),
(2, '20415823746', 'CABLES VOLCANICOS DEL PERÚ S.A.', 935124589, 'info@cablesvolcanicos.com', 'CALLE LOS MINERALES 256, CALLAO', '2024-10-20', 1),
(3, '20354876231', 'INDUSTRIAS PINTURAS S.A.C.', 914789321, 'ventas@indpinturas.com', 'AV. PINTURAS 1050, SURCO, LIMA', '2024-10-19', 1),
(4, '20512346789', 'MAQUINARIAS Y HERRAMIENTAS PERÚ S.A.', 987654321, 'info@maquinariasperu.com', 'JR. LOS HÉROES 350, SAN MARTÍN DE PORRES, LIMA', '2024-10-18', 0),
(8, '12315151414', 'Brandiño ', 994040463, 'JeampierBarrios04@gmail.com', 'Laderas de villa', '2024-12-11', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencianotacredito`
--

CREATE TABLE `referencianotacredito` (
  `ReferenciaNotaCreditoID` int NOT NULL,
  `NumeroSerie` varchar(255) NOT NULL,
  `NumeroCorrelativo` int NOT NULL,
  `FechaDocumento` date NOT NULL,
  `MotivoNotaCredito` varchar(255) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `FacturaEmitidaID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `RolID` int NOT NULL,
  `NombreRol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`RolID`, `NombreRol`) VALUES
(1, 'jefeVentas'),
(2, 'vendedor'),
(3, 'cajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriecomprobante`
--

CREATE TABLE `seriecomprobante` (
  `SerieComprobanteID` int NOT NULL,
  `NumeroSerie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `seriecomprobante`
--

INSERT INTO `seriecomprobante` (`SerieComprobanteID`, `NumeroSerie`) VALUES
(1, 'F001'),
(2, 'F002'),
(3, 'F003'),
(4, 'B001'),
(5, 'B002'),
(6, 'B003'),
(7, 'NC01'),
(8, 'NC02'),
(9, 'NC03'),
(10, 'C001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `UsuarioID` int NOT NULL,
  `NombreUsuario` varchar(255) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Nombres` varchar(100) DEFAULT NULL,
  `Apellidos` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `DNI` varchar(15) DEFAULT NULL,
  `RespuestaSecreta` varchar(255) DEFAULT NULL,
  `FechaCreacion` date NOT NULL,
  `EstadoUsuario` int NOT NULL,
  `RolID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`UsuarioID`, `NombreUsuario`, `Contraseña`, `Nombres`, `Apellidos`, `Telefono`, `Email`, `DNI`, `RespuestaSecreta`, `FechaCreacion`, `EstadoUsuario`, `RolID`) VALUES
(1, 'Jeanking', '12345', 'Carlos', 'Roca Fernández', '987654321', 'admin@edificandolaroca.com', '12345678', 'Carlos', '2024-10-21', 1, 1),
(2, 'pepeLijas', 'pepelij4s2024', 'Pepe', 'Lijas Ramírez', '987654322', 'pepe@lijasmax.com', '12345679', 'Firulais', '2024-10-21', 0, 1),
(3, 'corteMaster', 'AGUMON2004', 'Juan', 'Corte Díaz', '987654323', 'master@cortediscos.com', '12345680', 'Cusco', '2024-10-21', 1, 1),
(4, 'lijaKing', 'kinglijas2024', 'Luis', 'Rey Martínez', '987654324', 'king@lijaspro.com', '12345681', 'Pizza', '2024-10-20', 1, 2),
(5, 'silicoman', 'si1icon321', 'Silvia', 'Conam Torres', '987654325', 'man@siliconaspro.com', '12345682', 'Arequipa', '2024-10-20', 1, 2),
(6, 'pinturaMax', 'contraseña', 'María', 'Pintura López', '987654326', 'ventas@pinturasmax.com', '12345683', 'Trujillo', '2024-10-20', 1, 3),
(7, 'cableVolc', 'TACZA_developer', 'Pedro', 'Cable Gómez', '987654327', 'info@cablesvolcanicos.com', '12345684', 'Lima', '2024-10-19', 1, 1),
(8, 'discoMetal', 'discoM3tal', 'Diego', 'Metal Vargas', '987654328', 'ventas@discosmetal.com', '12345685', 'Tacos', '2024-10-19', 0, 2),
(9, 'amoloPro', 'amoloPro2024', 'Ana', 'Molo Sánchez', '987654329', 'pro@amoladoras.com', '12345686', 'Piura', '2024-10-19', 1, 2),
(10, 'soldaBoss', 's0ldab0ss', 'Jorge', 'Solda Pérez', '987654330', 'boss@soldaduras.com', '12345687', 'San Pedro', '2024-10-18', 0, 3),
(11, 'nuevo_nombre_usuario', 'nueva_contraseña', 'nuevos_nombres', 'nuevos_apellidos', 'nuevo_telefono', 'nuevo_email', 'nuevo_dni', 'nueva_respuesta_secreta', '2024-12-11', 0, 3),
(13, 'nuevo_nombre_usuario', 'nueva_contraseña', 'nuevos_nombres', 'nuevos_apellidos', 'nuevo_telefono', 'nuevo_email', 'nuevo_dni', 'nueva_respuesta_secreta', '2024-12-14', 1, 3),
(14, 'Jimmy_saurux2004', '12345', 'Jimmy Alexander', 'Huerta Vasquez', '906272595', '2123010236@gmail.com', '72192033', 'Pinpon', '2024-12-14', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boletaemitida`
--
ALTER TABLE `boletaemitida`
  ADD PRIMARY KEY (`BoletaEmitidaID`),
  ADD UNIQUE KEY `BoletaEmitidaID` (`BoletaEmitidaID`),
  ADD KEY `fk_boletaemitida_seriecomprobante` (`SerieComprobanteID`),
  ADD KEY `fk_boletaemitida_usuario` (`UsuarioID`),
  ADD KEY `fk_boletaemitida_cliente` (`ClienteID`);

--
-- Indices de la tabla `boletaemitidadetalles`
--
ALTER TABLE `boletaemitidadetalles`
  ADD PRIMARY KEY (`BoletaEmitidaDetallesID`),
  ADD UNIQUE KEY `BoletaEmitidaDetallesID` (`BoletaEmitidaDetallesID`),
  ADD KEY `fk_boletaemitidadetalles_boletaemitida` (`BoletaEmitidaID`),
  ADD KEY `fk_boletaemitidadetalles_productodetalles` (`ProductoDetallesID`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ClienteID`),
  ADD UNIQUE KEY `ClienteID` (`ClienteID`);

--
-- Indices de la tabla `comprobanterecibido`
--
ALTER TABLE `comprobanterecibido`
  ADD PRIMARY KEY (`ComprobanteRecibidoID`),
  ADD UNIQUE KEY `ComprobanteRecibidoID` (`ComprobanteRecibidoID`),
  ADD KEY `fk_comprobanterecibido_proveedor` (`ProveedorID`);

--
-- Indices de la tabla `comprobanterecibidodetalles`
--
ALTER TABLE `comprobanterecibidodetalles`
  ADD PRIMARY KEY (`ComprobanteRecibidoDetallesID`),
  ADD UNIQUE KEY `ComprobanteRecibidoDetallesID` (`ComprobanteRecibidoDetallesID`),
  ADD KEY `fk_comprobanterecibidodetalles_comprobanterecibidoid` (`ComprobanteRecibidoID`),
  ADD KEY `fk_comprobanterecibidodetalles_productodetalles` (`ProductoDetallesID`);

--
-- Indices de la tabla `cotizacionemitida`
--
ALTER TABLE `cotizacionemitida`
  ADD PRIMARY KEY (`CotizacionEmitidaID`),
  ADD UNIQUE KEY `CotizacionEmitidaID` (`CotizacionEmitidaID`),
  ADD KEY `fk_cotizacionemitida_seriecomprobante` (`SerieComprobanteID`),
  ADD KEY `fk_cotizacionemitida_usuario` (`UsuarioID`),
  ADD KEY `fk_cotizacionemitida_cliente` (`ClienteID`);

--
-- Indices de la tabla `cotizacionemitidadetalles`
--
ALTER TABLE `cotizacionemitidadetalles`
  ADD PRIMARY KEY (`CotizacionEmitidaDetallesID`),
  ADD UNIQUE KEY `CotizacionEmitidaDetallesID` (`CotizacionEmitidaDetallesID`),
  ADD KEY `fk_cotizacionemitidadetalles_cotizacionemitida` (`CotizacionEmitidaID`),
  ADD KEY `fk_cotizacionemitidadetalles_productodetalles` (`ProductoDetallesID`);

--
-- Indices de la tabla `facturaemitida`
--
ALTER TABLE `facturaemitida`
  ADD PRIMARY KEY (`FacturaEmitidaID`),
  ADD UNIQUE KEY `FacturaEmitidaID` (`FacturaEmitidaID`),
  ADD KEY `fk_facturaekitida_seriecomprobante` (`SerieComprobanteID`),
  ADD KEY `fk_facturaekitida_usuario` (`UsuarioID`),
  ADD KEY `fk_facturaekitida_cliente` (`ClienteID`);

--
-- Indices de la tabla `facturaemitidadetalles`
--
ALTER TABLE `facturaemitidadetalles`
  ADD PRIMARY KEY (`FacturaEmitidaDetallesID`),
  ADD UNIQUE KEY `FacturaEmitidaDetallesID` (`FacturaEmitidaDetallesID`),
  ADD KEY `fk_facturaemitidadetalles_facturaemitida` (`FacturaEmitidaID`),
  ADD KEY `fk_facturaemitidadetalles_productodetalles` (`ProductoDetallesID`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ProductoID`),
  ADD UNIQUE KEY `ProductoID` (`ProductoID`);

--
-- Indices de la tabla `productodetalles`
--
ALTER TABLE `productodetalles`
  ADD PRIMARY KEY (`ProductoDetallesID`),
  ADD UNIQUE KEY `ProductoDetallesID` (`ProductoDetallesID`),
  ADD KEY `fk_productodetalles_producto` (`ProductoID`),
  ADD KEY `fk_productodetalles_proveedor` (`ProveedorID`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`ProveedorID`),
  ADD UNIQUE KEY `ProveedorID` (`ProveedorID`);

--
-- Indices de la tabla `referencianotacredito`
--
ALTER TABLE `referencianotacredito`
  ADD PRIMARY KEY (`ReferenciaNotaCreditoID`),
  ADD UNIQUE KEY `ReferenciaNotaCreditoID` (`ReferenciaNotaCreditoID`),
  ADD KEY `fk_referencianotacredito_facturaemitida` (`FacturaEmitidaID`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`RolID`);

--
-- Indices de la tabla `seriecomprobante`
--
ALTER TABLE `seriecomprobante`
  ADD PRIMARY KEY (`SerieComprobanteID`,`NumeroSerie`),
  ADD UNIQUE KEY `SerieComprobanteID` (`SerieComprobanteID`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`UsuarioID`),
  ADD UNIQUE KEY `UsuarioID` (`UsuarioID`),
  ADD KEY `fk_usuario_rol` (`RolID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boletaemitida`
--
ALTER TABLE `boletaemitida`
  MODIFY `BoletaEmitidaID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `boletaemitidadetalles`
--
ALTER TABLE `boletaemitidadetalles`
  MODIFY `BoletaEmitidaDetallesID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ClienteID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `comprobanterecibido`
--
ALTER TABLE `comprobanterecibido`
  MODIFY `ComprobanteRecibidoID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `comprobanterecibidodetalles`
--
ALTER TABLE `comprobanterecibidodetalles`
  MODIFY `ComprobanteRecibidoDetallesID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `cotizacionemitida`
--
ALTER TABLE `cotizacionemitida`
  MODIFY `CotizacionEmitidaID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cotizacionemitidadetalles`
--
ALTER TABLE `cotizacionemitidadetalles`
  MODIFY `CotizacionEmitidaDetallesID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `facturaemitida`
--
ALTER TABLE `facturaemitida`
  MODIFY `FacturaEmitidaID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `facturaemitidadetalles`
--
ALTER TABLE `facturaemitidadetalles`
  MODIFY `FacturaEmitidaDetallesID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ProductoID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `productodetalles`
--
ALTER TABLE `productodetalles`
  MODIFY `ProductoDetallesID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `ProveedorID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `referencianotacredito`
--
ALTER TABLE `referencianotacredito`
  MODIFY `ReferenciaNotaCreditoID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `RolID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `seriecomprobante`
--
ALTER TABLE `seriecomprobante`
  MODIFY `SerieComprobanteID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `UsuarioID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boletaemitida`
--
ALTER TABLE `boletaemitida`
  ADD CONSTRAINT `fk_boletaemitida_cliente` FOREIGN KEY (`ClienteID`) REFERENCES `cliente` (`ClienteID`),
  ADD CONSTRAINT `fk_boletaemitida_seriecomprobante` FOREIGN KEY (`SerieComprobanteID`) REFERENCES `seriecomprobante` (`SerieComprobanteID`),
  ADD CONSTRAINT `fk_boletaemitida_usuario` FOREIGN KEY (`UsuarioID`) REFERENCES `usuario` (`UsuarioID`);

--
-- Filtros para la tabla `boletaemitidadetalles`
--
ALTER TABLE `boletaemitidadetalles`
  ADD CONSTRAINT `fk_boletaemitidadetalles_boletaemitida` FOREIGN KEY (`BoletaEmitidaID`) REFERENCES `boletaemitida` (`BoletaEmitidaID`),
  ADD CONSTRAINT `fk_boletaemitidadetalles_productodetalles` FOREIGN KEY (`ProductoDetallesID`) REFERENCES `productodetalles` (`ProductoDetallesID`);

--
-- Filtros para la tabla `comprobanterecibido`
--
ALTER TABLE `comprobanterecibido`
  ADD CONSTRAINT `fk_comprobanterecibido_proveedor` FOREIGN KEY (`ProveedorID`) REFERENCES `proveedor` (`ProveedorID`);

--
-- Filtros para la tabla `comprobanterecibidodetalles`
--
ALTER TABLE `comprobanterecibidodetalles`
  ADD CONSTRAINT `fk_comprobanterecibidodetalles_comprobanterecibidoid` FOREIGN KEY (`ComprobanteRecibidoID`) REFERENCES `comprobanterecibido` (`ComprobanteRecibidoID`),
  ADD CONSTRAINT `fk_comprobanterecibidodetalles_productodetalles` FOREIGN KEY (`ProductoDetallesID`) REFERENCES `productodetalles` (`ProductoDetallesID`);

--
-- Filtros para la tabla `cotizacionemitida`
--
ALTER TABLE `cotizacionemitida`
  ADD CONSTRAINT `fk_cotizacionemitida_cliente` FOREIGN KEY (`ClienteID`) REFERENCES `cliente` (`ClienteID`),
  ADD CONSTRAINT `fk_cotizacionemitida_seriecomprobante` FOREIGN KEY (`SerieComprobanteID`) REFERENCES `seriecomprobante` (`SerieComprobanteID`),
  ADD CONSTRAINT `fk_cotizacionemitida_usuario` FOREIGN KEY (`UsuarioID`) REFERENCES `usuario` (`UsuarioID`);

--
-- Filtros para la tabla `cotizacionemitidadetalles`
--
ALTER TABLE `cotizacionemitidadetalles`
  ADD CONSTRAINT `fk_cotizacionemitidadetalles_cotizacionemitida` FOREIGN KEY (`CotizacionEmitidaID`) REFERENCES `cotizacionemitida` (`CotizacionEmitidaID`),
  ADD CONSTRAINT `fk_cotizacionemitidadetalles_productodetalles` FOREIGN KEY (`ProductoDetallesID`) REFERENCES `productodetalles` (`ProductoDetallesID`);

--
-- Filtros para la tabla `facturaemitida`
--
ALTER TABLE `facturaemitida`
  ADD CONSTRAINT `fk_facturaekitida_cliente` FOREIGN KEY (`ClienteID`) REFERENCES `cliente` (`ClienteID`),
  ADD CONSTRAINT `fk_facturaekitida_seriecomprobante` FOREIGN KEY (`SerieComprobanteID`) REFERENCES `seriecomprobante` (`SerieComprobanteID`),
  ADD CONSTRAINT `fk_facturaekitida_usuario` FOREIGN KEY (`UsuarioID`) REFERENCES `usuario` (`UsuarioID`);

--
-- Filtros para la tabla `facturaemitidadetalles`
--
ALTER TABLE `facturaemitidadetalles`
  ADD CONSTRAINT `fk_facturaemitidadetalles_facturaemitida` FOREIGN KEY (`FacturaEmitidaID`) REFERENCES `facturaemitida` (`FacturaEmitidaID`),
  ADD CONSTRAINT `fk_facturaemitidadetalles_productodetalles` FOREIGN KEY (`ProductoDetallesID`) REFERENCES `productodetalles` (`ProductoDetallesID`);

--
-- Filtros para la tabla `productodetalles`
--
ALTER TABLE `productodetalles`
  ADD CONSTRAINT `fk_productodetalles_producto` FOREIGN KEY (`ProductoID`) REFERENCES `producto` (`ProductoID`),
  ADD CONSTRAINT `fk_productodetalles_proveedor` FOREIGN KEY (`ProveedorID`) REFERENCES `proveedor` (`ProveedorID`),
  ADD CONSTRAINT `productodetalles_ibfk_1` FOREIGN KEY (`ProductoID`) REFERENCES `producto` (`ProductoID`),
  ADD CONSTRAINT `productodetalles_ibfk_2` FOREIGN KEY (`ProveedorID`) REFERENCES `proveedor` (`ProveedorID`);

--
-- Filtros para la tabla `referencianotacredito`
--
ALTER TABLE `referencianotacredito`
  ADD CONSTRAINT `fk_referencianotacredito_facturaemitida` FOREIGN KEY (`FacturaEmitidaID`) REFERENCES `facturaemitida` (`FacturaEmitidaID`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`RolID`) REFERENCES `rol` (`RolID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
