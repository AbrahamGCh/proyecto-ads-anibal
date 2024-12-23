<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/cotizaciones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class controlEmitirCotizacion
{
  public function generarPdf($cotizacion)
  {
    // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF();

    // Establecer propiedades del documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Nombre');
    $pdf->SetTitle('Reporte de Cotización');
    $pdf->SetSubject('Detalle de Cotización');
    $pdf->SetKeywords('TCPDF, PDF, cotizacion, reporte');

    // Establecer márgenes
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Establecer auto página break
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Establecer factor de escala de imagen
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Añadir una página
    $pdf->AddPage();

    // Establecer fuente
    $pdf->SetFont('helvetica', '', 12);

    // Extraer datos de la cotización
    $numeroCotizacion = $cotizacion['NumeroCorrelativo'];
    $cliente = $cotizacion['Cliente'];
    $obra = $cotizacion['Obra'];
    $fechaEmision = $cotizacion['FechaEmision'];
    $moneda = $cotizacion['Moneda'];
    $importeTotal = $cotizacion['ImporteTotal'];

    $costoSinIgv = 0;

    foreach ($cotizacion['Detalles'] as $detalle) {
      $totalLinea = $detalle['Total'];
      $costoSinIgv += $totalLinea;
    }

    // Armado del HTML para el PDF (una sola cotización)
    $html = '
        <!DOCTYPE html>
        <style>
          body {
              font-family: Arial, sans-serif;
              margin: 20px;
          }
          header {
              display: flex;
              align-items: center;
              margin-bottom: 20px;
          }
          header img {
              width: 50px;
              height: 50px;
              margin-right: 20px;
          }
          header h1, header h2 {
              margin: 0;
              padding: 0;
          }
          table {
              width: 100%;
              border-collapse: collapse;
              margin-bottom: 20px;
          }
          th, td {
              border: 1px solid #000;
              padding: 8px;
              text-align: left;
          }
          th {
              background-color: #f2f2f2;
          }
          .info p {
              margin: 0;
              font-size: 0.9rem;
          }
        </style>
        <header>
            <img src="/assets/img/logo.png" alt="Logo">
            <div>
              <h1>Edificando sobre la Roca</h1>
              <h2>Detalle de Cotización</h2>
            </div>
        </header>
        
        <table>
            <thead>
                <tr>
                    <th><strong># Cotización</strong></th>
                    <th><strong>Cliente</strong></th>
                    <th><strong>Obra</strong></th>
                    <th><strong>Fecha Emisión</strong></th>
                    <th><strong>Moneda</strong></th>
                    <th><strong>Importe Total</strong></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>' . $numeroCotizacion . '</td>
                    <td>' . $cliente . '</td>
                    <td>' . $obra . '</td>
                    <td>' . $fechaEmision . '</td>
                    <td>' . $moneda . '</td>
                    <td>S/. ' . number_format($costoSinIgv, 2) . '</td>
                </tr>
            </tbody>
        </table>';

    // Tabla de detalles de productos
    if (isset($cotizacion['Detalles']) && is_array($cotizacion['Detalles']) && count($cotizacion['Detalles']) > 0) {
      $html .= '
        <h3>Productos</h3>
        <table>
            <thead>
                <tr>
                    <th><strong>Producto</strong></th>
                    <th><strong>Cantidad</strong></th>
                    <th><strong>Precio Unitario</strong></th>
                    <th><strong>Total</strong></th>
                </tr>
            </thead>
            <tbody>';

      foreach ($cotizacion['Detalles'] as $detalle) {
        $nombreProducto = $detalle['NombreProducto'];
        $cantidad = $detalle['Cantidad'];
        $precioUnitario = $detalle['PrecioUnitarioVenta'];
        $totalLinea = $detalle['Total'];

        $html .= '
                <tr>
                    <td>' . $nombreProducto . '</td>
                    <td>' . $cantidad . '</td>
                    <td>S/. ' . number_format($precioUnitario, 2) . '</td>
                    <td>S/. ' . number_format($totalLinea, 2) . '</td>
                </tr>';
      }

      $html .= '
            </tbody>
        </table>';
    }

    $html .= '
        <div class="info">
            <p>Fecha de generación: ' . date('Y-m-d H:i:s') . '</p>
        </div>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Reporte_de_cotizacion_' . $numeroCotizacion . '_' . date('Y-m-d_H:i:s') . '.pdf', 'I');
  }

  public function generarCotizacionesPdf($idCotizacion)
  {
    $cotizacionesModel = new Cotizaciones();
    // Obtener información completa de UNA cotización específica
    $cotizacion = $cotizacionesModel->obtenerCotizacionCompleta($idCotizacion);

    if ($cotizacion !== null) {
      $this->generarPdf($cotizacion);
    } else {
      echo "No se encontró la cotización con ID: $idCotizacion";
    }
  }

  public function guardarNuevaCotizacion(
    $nrRucDni,
    $razonSocial,
    $direccion,
    $obra,
    $moneda,
    $productosArray
  ) {
    // Crear instancia del modelo
    $cotizacionesModel = new Cotizaciones();

    // Parámetros de ejemplo
    $usuarioID = 1;
    $serieComprobanteID = 1;

    // Calcular totales
    $opGravada = 0;
    foreach ($productosArray as $prod) {
      $opGravada += floatval($prod['price']) * intval($prod['amount']);
    }

    $totalIGV = $opGravada * 0.18;
    $importeTotal = $opGravada + $totalIGV;
    $fechaHoy = date('Y-m-d');
    $tipoPago = "Al contado";
    $formaPago = "Efectivo";
    $observaciones = "Cotización generada desde el sistema";

    // Obtener o crear el ClienteID
    $clienteID = $cotizacionesModel->obtenerClienteIdPorDocumento($nrRucDni, $razonSocial, $direccion);
    if (!$clienteID) {
      return ['success' => false, 'message' => 'No se pudo determinar el ClienteID.'];
    }

    // Guardar cotización
    $numeroCorrelativo = $cotizacionesModel->guardarNuevaCotizacion(
      $serieComprobanteID,
      $usuarioID,
      $fechaHoy,
      $fechaHoy,
      $tipoPago,
      $formaPago,
      $moneda,
      $clienteID,
      $opGravada,
      0, // OpInafecta
      0, // OpExonerada
      0, // OpGratuita
      $totalIGV,
      0, // DescuentoGlobal
      $importeTotal,
      $observaciones,
      $obra,
      $productosArray
    );

    if ($numeroCorrelativo) {
      $objMensaje = new screenMensajeSistema();
      $objMensaje->screenMensajeSistemaShow(
        "Mensaje",
        "Cotizacion Guardada correctamente",
        "<a href='../moduloVentas/indexCotizacion.php'>Regresar</a>"
      );
      exit();
    } else {
      $objMensaje = new screenMensajeSistema();
      $objMensaje->screenMensajeSistemaShow(
        "Error",
        "Cotizacion No se guardo correctamente",
        "<a href='../moduloVentas/indexEmitirCotizacion.php'>Regresar</a>"
      );
    }
  }
}
