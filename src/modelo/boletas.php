<?php
include_once('conexion.php');

class Boletas
{
  public function getBoletas($numeroBoleta = null, $fechadesde = null, $fechahasta = null)
  {
    $sql = "SELECT be.BoletaEmitidaID,
    sc.NumeroSerie,
    LPAD(be.NumeroCorrelativo, 10, '0') AS NumeroCorrelativo,
    cl.NombreCompletoORazonSocial AS Cliente,
    be.Obra,
    be.FechaEmision,
    be.ImporteTotal
  FROM boletaemitida be
  INNER JOIN cliente cl ON be.ClienteID = cl.ClienteID
  INNER JOIN seriecomprobante sc ON be.SerieComprobanteID = sc.SerieComprobanteID";

    $conditions = [];

    if ($numeroBoleta !== null && $numeroBoleta !== '') {
      $conditions[] = "be.NumeroCorrelativo = $numeroBoleta";
    }

    if ($fechadesde !== null && $fechadesde !== '') {
      $conditions[] = "be.FechaEmision >= '$fechadesde'";
    }

    if ($fechahasta !== null && $fechahasta !== '') {
      $conditions[] = "be.FechaEmision <= '$fechahasta'";
    }

    if (count($conditions) > 0) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $conexion = Conexion::conectarBD();
    $respuesta = $conexion->query($sql);

    if (!$respuesta) {
      die("Error en la consulta: " . $conexion->error);
    }

    $boletas = [];

    while ($fila = $respuesta->fetch_assoc()) {
      $boletas[] = $fila;
    }

    Conexion::desConectarBD();
    return $boletas;
  }

  public function guardarNuevaBoleta(
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
    $sqlMax = "SELECT IFNULL(MAX(NumeroCorrelativo), 0) as maxCorr FROM boletaemitida WHERE SerieComprobanteID = $serieComprobanteID";
    $respuestaMax = $conexion->query($sqlMax);
    $filaMax = $respuestaMax->fetch_assoc();
    $numeroCorrelativo = $filaMax['maxCorr'] + 1;

    // Calcular CostoTotal basado en los productos
    $costoTotal = 0;

    foreach ($productosArray as $prod) {
      $productoID = (int)$prod['id'];
      $cantidad = intval($prod['amount']);
      $valorUnitarioCompra = floatval($prod['price']);
      $costoTotal += $valorUnitarioCompra * $cantidad;
    }

    // Insertar boleta
    $sqlInsert = "
    INSERT INTO boletaemitida 
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
      die("Error al insertar boleta: " . $conexion->error);
    }

    $boletaID = $conexion->insert_id;

    // Insertar detalles de la boleta
    foreach ($productosArray as $prod) {
      $productoID = (int)$prod['id'];
      $cantidad = intval($prod['amount']);
      $precioVenta = floatval($prod['price']);
      $valorUnitarioVenta = $precioVenta;
      $total = $precioVenta * $cantidad;

      $sqlDet = "INSERT INTO boletaemitidadetalles 
                 (BoletaEmitidaID, ProductoDetallesID, Cantidad, PrecioUnitarioVenta, ValorUnitarioVenta, Descuento, Total)
                 VALUES 
                 ($boletaID, $productoID, $cantidad, $precioVenta, $valorUnitarioVenta, 0, $total)";

      if (!$conexion->query($sqlDet)) {
        die("Error al insertar detalle de boleta: " . $conexion->error);
      }
    }

    Conexion::desConectarBD();
    return $numeroCorrelativo;
  }

  public function obtenerClienteIdPorDocumento($nrRucDni, $razonSocial, $direccion)
  {
    $conexion = Conexion::conectarBD();

    $nrRucDni = $conexion->real_escape_string($nrRucDni);

    $sql = "SELECT ClienteID FROM cliente WHERE NumeroDocumentoIdentidad = '$nrRucDni' LIMIT 1";
    $respuesta = $conexion->query($sql);

    if ($respuesta && $respuesta->num_rows > 0) {
      $fila = $respuesta->fetch_assoc();
      $clienteID = $fila['ClienteID'];
    } else {
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

  public function obtenerBoletaCompleta($boletaID)
  {
    $conexion = Conexion::conectarBD();
    $boletaID = (int)$boletaID;

    $sqlBoleta = "
        SELECT
            be.BoletaEmitidaID,
            be.SerieComprobanteID,
            be.NumeroCorrelativo,
            be.UsuarioID,
            be.FechaEmision,
            be.FechaVencimiento,
            be.TipoPago,
            be.FormaPago,
            be.Moneda,
            be.ClienteID,
            cl.NombreCompletoORazonSocial AS Cliente,
            be.OpGravada,
            be.OpInafecta,
            be.OpExonerada,
            be.OpGratuita,
            be.TotalIGV,
            be.DescuentoGlobal,
            be.ImporteTotal,
            be.CostoTotal,
            be.Observaciones,
            be.Obra
        FROM boletaemitida be
        INNER JOIN cliente cl ON be.ClienteID = cl.ClienteID
        WHERE be.BoletaEmitidaID = $boletaID
        LIMIT 1
    ";

    $resultadoBoleta = $conexion->query($sqlBoleta);

    if (!$resultadoBoleta) {
      die("Error al obtener la boleta: " . $conexion->error);
    }

    if ($resultadoBoleta->num_rows == 0) {
      Conexion::desConectarBD();
      return null;
    }

    $boleta = $resultadoBoleta->fetch_assoc();

    $sqlDetalles = "
        SELECT
            bd.BoletaEmitidaDetallesID,
            bd.BoletaEmitidaID,
            bd.ProductoDetallesID,
            bd.Cantidad,
            bd.PrecioUnitarioVenta,
            bd.ValorUnitarioVenta,
            bd.Descuento,
            bd.Total,
            p.Descripcion AS NombreProducto,
            pd.UnidadMedida,
            pd.ValorUnitarioCompra,
            p.CodigoProducto
        FROM boletaemitidadetalles bd
        INNER JOIN productodetalles pd ON bd.ProductoDetallesID = pd.ProductoDetallesID
        INNER JOIN producto p ON pd.ProductoID = p.ProductoID
        WHERE bd.BoletaEmitidaID = $boletaID
    ";

    $resultadoDetalles = $conexion->query($sqlDetalles);

    if (!$resultadoDetalles) {
      die("Error al obtener los detalles de la boleta: " . $conexion->error);
    }

    $detalles = [];
    while ($filaDetalle = $resultadoDetalles->fetch_assoc()) {
      $detalles[] = $filaDetalle;
    }

    Conexion::desConectarBD();

    $boleta['Detalles'] = $detalles;

    return $boleta;
  }
}
