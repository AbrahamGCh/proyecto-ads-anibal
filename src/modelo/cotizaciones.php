<?php
include_once('conexion.php');

class Cotizaciones
{
  public function getCotizaciones($numerocotizacion = null, $fechadesde = null, $fechahasta = null)
  {
    $conexion = Conexion::conectarBD();

    $sql = "SELECT 
              ce.CotizacionEmitidaID,
              sc.NumeroSerie,
              LPAD(ce.NumeroCorrelativo, 10, '0') AS NumeroCorrelativo,
              cl.NombreCompletoORazonSocial AS Cliente,
              ce.Obra,
              ce.FechaEmision,
              ce.ImporteTotal
            FROM 
              cotizacionemitida ce
            INNER JOIN 
              cliente cl ON ce.ClienteID = cl.ClienteID
            INNER JOIN 
              seriecomprobante sc ON ce.SerieComprobanteID = sc.SerieComprobanteID";

    $conditions = [];
    $params = [];
    $types = '';

    if ($numerocotizacion !== null && $numerocotizacion !== '') {
      $conditions[] = "ce.NumeroCorrelativo = ?";
      $params[] = $numerocotizacion;
      $types .= 'i'; // Entero
    }

    if ($fechadesde !== null && $fechadesde !== '') {
      $conditions[] = "ce.FechaEmision >= ?";
      $params[] = $fechadesde;
      $types .= 's'; // String (fecha)
    }

    if ($fechahasta !== null && $fechahasta !== '') {
      $conditions[] = "ce.FechaEmision <= ?";
      $params[] = $fechahasta;
      $types .= 's'; // String (fecha)
    }

    if (!empty($conditions)) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conexion->prepare($sql);

    if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $cotizaciones = [];
    while ($fila = $result->fetch_assoc()) {
      $cotizaciones[] = $fila;
    }

    $stmt->close();
    Conexion::desConectarBD();

    return $cotizaciones;
  }

  public function obtenerClienteIdPorDocumento($nrRucDni, $razonSocial, $direccion)
  {
    $conexion = Conexion::conectarBD();

    // Suponemos que el campo NumeroDocumentoIdentidad es único y basta con buscarlo
    $nrRucDni = $conexion->real_escape_string($nrRucDni);

    $sql = "SELECT ClienteID FROM cliente WHERE NumeroDocumentoIdentidad = '$nrRucDni' LIMIT 1";
    $respuesta = $conexion->query($sql);

    if ($respuesta && $respuesta->num_rows > 0) {
      $fila = $respuesta->fetch_assoc();
      $clienteID = $fila['ClienteID'];
    } else {
      // Si no existe, podríamos crearlo aquí
      $razonSocial = $conexion->real_escape_string($razonSocial);
      $direccion = $conexion->real_escape_string($direccion);
      $hoy = date('Y-m-d');
      // TipoDocumentoIdentidad y demás datos podrían ser asumidos o cambiados según la lógica del negocio
      $sqlInsert = "INSERT INTO cliente (TipoDocumentoIdentidad, NumeroDocumentoIdentidad, NombreCompletoORazonSocial, Telefono, Email, Direccion, FechaRegistro)
                    VALUES ('DNI', '$nrRucDni', '$razonSocial', 0, '', '$direccion', '$hoy')";
      if ($conexion->query($sqlInsert)) {
        $clienteID = $conexion->insert_id;
      } else {
        $clienteID = false;
      }
    }

    Conexion::desConectarBD();
    return $clienteID;
  }

  public function guardarNuevaCotizacion(
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

    // Obtener el último correlativo
    $sqlMax = "SELECT IFNULL(MAX(NumeroCorrelativo), 0) as maxCorr FROM cotizacionemitida WHERE SerieComprobanteID = $serieComprobanteID";
    $respuestaMax = $conexion->query($sqlMax);

    if (!$respuestaMax) {
      die("Error al obtener el último correlativo: " . $conexion->error);
    }

    $filaMax = $respuestaMax->fetch_assoc();
    $numeroCorrelativo = $filaMax['maxCorr'] + 1;

    // Insertar cotización principal
    $sqlInsert = "INSERT INTO cotizacionemitida 
    (SerieComprobanteID, NumeroCorrelativo, UsuarioID, FechaEmision, FechaVencimiento, TipoPago, FormaPago, Moneda, ClienteID, `Op.Inafecta`, `Op.Exonerada`, `Op.Gratuita`, `Op.Gravada`, TotalIGV, DescuentoGlobal, ImporteTotal, Observaciones, Obra)
    VALUES
    ($serieComprobanteID, $numeroCorrelativo, $usuarioID, '$fechaEmision', '$fechaVencimiento', '$tipoPago', '$formaPago', '$moneda', $clienteID, $opInafecta, $opExonerada, $opGratuita, $opGravada, $totalIGV, $descuentoGlobal, $importeTotal, '$observaciones', '$obra')";

    if (!$conexion->query($sqlInsert)) {
      die("Error al insertar cotización: " . $conexion->error);
    }

    $cotizacionID = $conexion->insert_id;

    if (!$cotizacionID) {
      die("No se pudo obtener el ID de la cotización recién insertada.");
    }

    // Insertar detalles
    foreach ($productosArray as $prod) {
      $productoID = (int)$prod['id'];
      $cantidad = intval($prod['amount']);
      $precioVenta = floatval($prod['price']);
      $valorUnitarioVenta = $precioVenta;
      $descuento = 0;
      $total = $precioVenta * $cantidad;

      // Llamar al método para guardar detalles
      $this->guardarDetalleCotizacion(
        $cotizacionID,
        $productoID,
        $cantidad,
        $precioVenta,
        $total,
        $valorUnitarioVenta,
        $descuento
      );
    }

    Conexion::desConectarBD();
    return $numeroCorrelativo;
  }

  public function obtenerCotizacionCompleta($cotizacionID)
  {
    $conexion = Conexion::conectarBD();
    $cotizacionID = (int)$cotizacionID;

    // Obtener información principal de la cotización y del cliente
    $sqlCot = "
        SELECT
            ce.CotizacionEmitidaID,
            ce.SerieComprobanteID,
            ce.NumeroCorrelativo,
            ce.UsuarioID,
            ce.FechaEmision,
            ce.FechaVencimiento,
            ce.TipoPago,
            ce.FormaPago,
            ce.Moneda,
            ce.ClienteID,
            cl.NombreCompletoORazonSocial AS Cliente,
            ce.`Op.Gravada` AS OpGravada,
            ce.`Op.Inafecta` AS OpInafecta,
            ce.`Op.Exonerada` AS OpExonerada,
            ce.`Op.Gratuita` AS OpGratuita,
            ce.TotalIGV,
            ce.DescuentoGlobal,
            ce.ImporteTotal,
            ce.Observaciones,
            ce.Obra
        FROM cotizacionemitida ce
        INNER JOIN cliente cl ON ce.ClienteID = cl.ClienteID
        WHERE ce.CotizacionEmitidaID = $cotizacionID
        LIMIT 1
    ";

    $resCot = $conexion->query($sqlCot);

    if (!$resCot) {
      die("Error al obtener la cotización: " . $conexion->error);
    }

    if ($resCot->num_rows == 0) {
      // No se encontró la cotización
      Conexion::desConectarBD();
      return null;
    }

    $cotizacion = $resCot->fetch_assoc();

    // Obtener los detalles de la cotización
    $sqlDet = "
        SELECT
            ced.CotizacionEmitidaDetallesID,
            ced.CotizacionEmitidaID,
            ced.ProductoDetallesID,
            ced.Cantidad,
            ced.PrecioUnitarioVenta,
            ced.ValorUnitarioVenta,
            ced.Descuento,
            ced.Total,
            p.Descripcion AS NombreProducto,
            pd.UnidadMedida,
            pd.ValorUnitarioCompra,
            p.CodigoProducto
        FROM cotizacionemitidadetalles ced
        INNER JOIN productodetalles pd ON ced.ProductoDetallesID = pd.ProductoDetallesID
        INNER JOIN producto p ON pd.ProductoID = p.ProductoID
        WHERE ced.CotizacionEmitidaID = $cotizacionID
    ";

    $resDet = $conexion->query($sqlDet);

    if (!$resDet) {
      die("Error al obtener los detalles de la cotización: " . $conexion->error);
    }

    $detalles = [];
    while ($filaDet = $resDet->fetch_assoc()) {
      $detalles[] = $filaDet;
    }

    Conexion::desConectarBD();

    // Añadir los detalles al array principal de la cotización
    $cotizacion['Detalles'] = $detalles;

    return $cotizacion;
  }
  public function obtenerCotizacionesResumen()
  {
    $conexion = Conexion::conectarBD();
    $sql = "SELECT ce.NumeroCorrelativo, cl.NombreCompletoORazonSocial AS Cliente, ce.Obra, ce.FechaEmision, ce.Moneda, ce.ImporteTotal
            FROM cotizacionemitida ce
            INNER JOIN cliente cl ON ce.ClienteID = cl.ClienteID
            ORDER BY ce.FechaEmision DESC";

    $resultado = $conexion->query($sql);

    if (!$resultado) {
      die("Error al obtener cotizaciones: " . $conexion->error);
    }

    $cotizaciones = array();
    while ($fila = $resultado->fetch_assoc()) {
      $cotizaciones[] = $fila;
    }

    Conexion::desConectarBD();
    return $cotizaciones;
  }

  public function guardarDetalleCotizacion($cotizacionID, $productoID, $cantidad, $precioUnitario, $total, $valorUnitarioVenta, $descuento)
  {
    $conexion = Conexion::conectarBD();

    // Preparar la consulta SQL para insertar el detalle
    $sqlDet = "INSERT INTO cotizacionemitidadetalles 
               (CotizacionEmitidaID, ProductoDetallesID, Cantidad, PrecioUnitarioVenta, Total, ValorUnitarioVenta, Descuento)
               VALUES 
               ($cotizacionID, $productoID, $cantidad, $precioUnitario, $total, $valorUnitarioVenta, $descuento)";

    if (!$conexion->query($sqlDet)) {
      die("Error al insertar detalle de cotización: " . $conexion->error);
    }

    Conexion::desConectarBD();
  }
}
