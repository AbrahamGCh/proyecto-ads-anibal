<?php
include_once('conexion.php');

class Facturas
{
  public function getFacturas($numeroFactura = null, $fechadesde = null, $fechahasta = null)
  {
    $sql = "SELECT fe.FacturaEmitidaID,
    sc.NumeroSerie,
    LPAD(fe.NumeroCorrelativo, 10, '0') AS NumeroCorrelativo,
    cl.NombreCompletoORazonSocial AS Cliente,
    fe.Obra,
    fe.FechaEmision,
    fe.ImporteTotal
  FROM facturaemitida fe
  INNER JOIN cliente cl ON fe.ClienteID = cl.ClienteID
  INNER JOIN seriecomprobante sc ON fe.SerieComprobanteID = sc.SerieComprobanteID";

    $conditions = [];

    if ($numeroFactura !== null && $numeroFactura !== '') {
      $conditions[] = "fe.NumeroCorrelativo = $numeroFactura";
    }

    if ($fechadesde !== null && $fechadesde !== '') {
      $conditions[] = "fe.FechaEmision >= '$fechadesde'";
    }

    if ($fechahasta !== null && $fechahasta !== '') {
      $conditions[] = "fe.FechaEmision <= '$fechahasta'";
    }

    if (count($conditions) > 0) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $conexion = Conexion::conectarBD();
    $respuesta = $conexion->query($sql);

    if (!$respuesta) {
      die("Error en la consulta: " . $conexion->error);
    }

    $facturas = [];

    while ($fila = $respuesta->fetch_assoc()) {
      $facturas[] = $fila;
    }

    Conexion::desConectarBD();
    return $facturas;
  }

  public function guardarNuevaFactura(
    $serieComprobanteID,
    $usuarioID,
    $fechaEmision,
    $fechaVencimiento,
    $tipoPago,
    $formaPago,
    $moneda,
    $clienteID,
    $opGravada,
    $opInafecta,
    $opExonerada,
    $opGratuita,
    $totalIGV,
    $descuentoGlobal,
    $importeTotal,
    $observaciones,
    $obra,
    $productosArray
  ) {
    $conexion = Conexion::conectarBD();

    // Obtener el número correlativo
    $sqlMax = "SELECT IFNULL(MAX(NumeroCorrelativo), 0) as maxCorr FROM facturaemitida WHERE SerieComprobanteID = $serieComprobanteID";
    $respuestaMax = $conexion->query($sqlMax);
    $filaMax = $respuestaMax->fetch_assoc();
    $numeroCorrelativo = $filaMax['maxCorr'] + 1;

    // Calcular CostoTotal basado en los productos
    $costoTotal = 0;

    foreach ($productosArray as $prod) {
      $productoID = (int)$prod['id'];
      $cantidad = intval($prod['amount']);
      $valorUnitarioCompra = floatval($prod['price']); // Asegúrate de incluir este campo en el array $productosArray
      $costoTotal += $valorUnitarioCompra * $cantidad;
    }

    // Insertar factura
    $sqlInsert = "
    INSERT INTO facturaemitida 
    (
        SerieComprobanteID,
        NumeroCorrelativo,
        UsuarioID,
        FechaEmision,
        FechaVencimiento,
        TipoPago,
        FormaPago,
        Moneda,
        ClienteID,
        `OpInafecta`,
        `OpExonerada`,
        `OpGratuita`,
        `OpGravada`,
        TotalIGV,
        DescuentoGlobal,
        ImporteTotal,
        Observaciones,
        Obra,
        EstadoDocumento,
        CostoTotal,
        Ganancia,
        OrdenDeCompra
    )
    VALUES
    (
        $serieComprobanteID,
        $numeroCorrelativo,
        $usuarioID,
        '$fechaEmision',
        '$fechaVencimiento',
        '$tipoPago',
        '$formaPago',
        '$moneda',
        $clienteID,
        $opInafecta,
        $opExonerada,
        $opGratuita,
        $opGravada,
        $totalIGV,
        $descuentoGlobal,
        $importeTotal,
        '$observaciones',
        '$obra',
        'Emitida',
        $costoTotal,
        $importeTotal - $costoTotal,
        $numeroCorrelativo
    );
    ";


    if (!$conexion->query($sqlInsert)) {
      die("Error al insertar factura: " . $conexion->error);
    }

    $facturaID = $conexion->insert_id;

    // Insertar detalles de la factura
    foreach ($productosArray as $prod) {
      $productoID = (int)$prod['id'];
      $cantidad = intval($prod['amount']);
      $precioVenta = floatval($prod['price']);
      $valorUnitarioVenta = $precioVenta;
      $valorUnitarioCompra = floatval($prod['price']); // Asegúrate de tenerlo en $productosArray
      $descuento = 0;
      $total = $precioVenta * $cantidad;

      $sqlDet = "INSERT INTO facturaemitidadetalles 
                 (FacturaEmitidaID, ProductoDetallesID, Cantidad, PrecioUnitarioVenta, ValorUnitarioVenta, Descuento, Total)
                 VALUES 
                 ($facturaID, $productoID, $cantidad, $precioVenta, $valorUnitarioVenta, $descuento, $total)";

      if (!$conexion->query($sqlDet)) {
        die("Error al insertar detalle de factura: " . $conexion->error);
      }
    }

    Conexion::desConectarBD();
    return $numeroCorrelativo;
  }


  public function obtenerClienteIdPorDocumento($nrRucDni, $razonSocial, $direccion)
  {
    $conexion = Conexion::conectarBD();

    // Escapar datos para evitar inyecciones SQL
    $nrRucDni = $conexion->real_escape_string($nrRucDni);

    // Verificar si el cliente ya existe
    $sql = "SELECT ClienteID FROM cliente WHERE NumeroDocumentoIdentidad = '$nrRucDni' LIMIT 1";
    $respuesta = $conexion->query($sql);

    if ($respuesta && $respuesta->num_rows > 0) {
      $fila = $respuesta->fetch_assoc();
      $clienteID = $fila['ClienteID'];
    } else {
      // Crear el cliente si no existe
      $razonSocial = $conexion->real_escape_string($razonSocial);
      $direccion = $conexion->real_escape_string($direccion);
      $hoy = date('Y-m-d');

      $sqlInsert = "INSERT INTO cliente (TipoDocumentoIdentidad, NumeroDocumentoIdentidad, NombreCompletoORazonSocial, Telefono, Email, Direccion, FechaRegistro)
                    VALUES ('DNI', '$nrRucDni', '$razonSocial', '0', '', '$direccion', '$hoy')";

      if ($conexion->query($sqlInsert)) {
        $clienteID = $conexion->insert_id;
      } else {
        $clienteID = false;
      }
    }

    Conexion::desConectarBD();
    return $clienteID;
  }

  public function obtenerFacturaCompleta($facturaID)
  {
    $conexion = Conexion::conectarBD();
    $facturaID = (int)$facturaID;

    // Obtener datos principales de la factura
    $sqlFactura = "
        SELECT
            fe.FacturaEmitidaID,
            fe.SerieComprobanteID,
            fe.NumeroCorrelativo,
            fe.UsuarioID,
            fe.FechaEmision,
            fe.FechaVencimiento,
            fe.TipoPago,
            fe.FormaPago,
            fe.Moneda,
            fe.ClienteID,
            cl.NombreCompletoORazonSocial AS Cliente,
            fe.OpGravada,
            fe.OpInafecta,
            fe.OpExonerada,
            fe.OpGratuita,
            fe.TotalIGV,
            fe.DescuentoGlobal,
            fe.ImporteTotal,
            fe.CostoTotal,
            fe.Observaciones,
            fe.Obra
        FROM facturaemitida fe
        INNER JOIN cliente cl ON fe.ClienteID = cl.ClienteID
        WHERE fe.FacturaEmitidaID = $facturaID
        LIMIT 1
    ";

    $resultadoFactura = $conexion->query($sqlFactura);

    if (!$resultadoFactura) {
      die("Error al obtener la factura: " . $conexion->error);
    }

    if ($resultadoFactura->num_rows == 0) {
      // No se encontró la factura
      Conexion::desConectarBD();
      return null;
    }

    $factura = $resultadoFactura->fetch_assoc();

    // Obtener detalles de la factura
    $sqlDetalles = "
        SELECT
            fd.FacturaEmitidaDetallesID,
            fd.FacturaEmitidaID,
            fd.ProductoDetallesID,
            fd.Cantidad,
            fd.PrecioUnitarioVenta,
            fd.ValorUnitarioVenta,
            fd.Descuento,
            fd.Total,
            p.Descripcion AS NombreProducto,
            pd.UnidadMedida,
            pd.ValorUnitarioCompra,
            p.CodigoProducto
        FROM facturaemitidadetalles fd
        INNER JOIN productodetalles pd ON fd.ProductoDetallesID = pd.ProductoDetallesID
        INNER JOIN producto p ON pd.ProductoID = p.ProductoID
        WHERE fd.FacturaEmitidaID = $facturaID
    ";

    $resultadoDetalles = $conexion->query($sqlDetalles);

    if (!$resultadoDetalles) {
      die("Error al obtener los detalles de la factura: " . $conexion->error);
    }

    $detalles = [];
    while ($filaDetalle = $resultadoDetalles->fetch_assoc()) {
      $detalles[] = $filaDetalle;
    }

    Conexion::desConectarBD();

    // Agregar los detalles al array de la factura
    $factura['Detalles'] = $detalles;

    return $factura;
  }
}
