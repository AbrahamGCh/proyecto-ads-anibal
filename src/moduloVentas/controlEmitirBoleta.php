<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/boletas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class controlEmitirBoleta
{
  public function generarPdf($boleta)
  {
    $pdf = new TCPDF();

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Nombre');
    $pdf->SetTitle('Boleta');
    $pdf->SetSubject('Detalle de Boleta');
    $pdf->SetKeywords('TCPDF, PDF, boleta');

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $numeroBoleta = $boleta['NumeroCorrelativo'];
    $cliente = $boleta['Cliente'];
    $obra = $boleta['Obra'];
    $fechaEmision = $boleta['FechaEmision'];
    $moneda = $boleta['Moneda'];
    $importeTotal = $boleta['ImporteTotal'];
    $costoTotal = $boleta['CostoTotal'];

    $html = "
      <h1>Boleta N° $numeroBoleta</h1>
      <p>Cliente: $cliente</p>
      <p>Obra: $obra</p>
      <p>Fecha de emisión: $fechaEmision</p>
      <p>Moneda: $moneda</p>
      <p>Importe total: S/. " . number_format($importeTotal, 2) . "</p>
      <p>Costo total: S/. " . number_format($costoTotal, 2) . "</p>
      <h2>Detalle</h2>
      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>";

    foreach ($boleta['Detalles'] as $detalle) {
      $html .= "
        <tr>
          <td>{$detalle['NombreProducto']}</td>
          <td>{$detalle['Cantidad']}</td>
          <td>S/. " . number_format($detalle['PrecioUnitarioVenta'], 2) . "</td>
          <td>S/. " . number_format($detalle['Total'], 2) . "</td>
        </tr>";
    }

    $html .= "
        </tbody>
      </table>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("Boleta_$numeroBoleta.pdf", 'I');
  }

  public function generarBoletasPdf($idBoleta)
  {
    $boletasModel = new Boletas();
    $boleta = $boletasModel->obtenerBoletaCompleta($idBoleta);

    if ($boleta !== null) {
      $this->generarPdf($boleta);
    } else {
      echo "No se encontró la boleta con ID: $idBoleta";
    }
  }

  public function guardarNuevaBoleta(
    $nrRucDni,
    $razonSocial,
    $direccion,
    $obra,
    $moneda,
    $productosArray
  ) {
    $boletasModel = new Boletas();

    $usuarioID = 1;
    $serieComprobanteID = 4;

    $opGravada = 0;
    foreach ($productosArray as $prod) {
      $opGravada += floatval($prod['price']) * intval($prod['amount']);
    }

    $totalIGV = $opGravada * 0.18;
    $importeTotal = $opGravada + $totalIGV;
    $fechaHoy = date('Y-m-d');
    $tipoPago = "Al contado";
    $formaPago = "Efectivo";
    $observaciones = "Boleta generada desde el sistema";

    $clienteID = $boletasModel->obtenerClienteIdPorDocumento($nrRucDni, $razonSocial, $direccion);
    if (!$clienteID) {
      return ['success' => false, 'message' => 'No se pudo determinar el ClienteID.'];
    }

    $numeroCorrelativo = $boletasModel->guardarNuevaBoleta(
      $serieComprobanteID,
      $usuarioID,
      $fechaHoy,
      $fechaHoy,
      $tipoPago,
      $formaPago,
      $moneda,
      $clienteID,
      $opGravada,
      0,
      0,
      0,
      $totalIGV,
      0,
      $importeTotal,
      $observaciones,
      $obra,
      $productosArray
    );

    if ($numeroCorrelativo) {
      $objMensaje = new screenMensajeSistema();
      $objMensaje->screenMensajeSistemaShow(
        "Mensaje",
        "Boleta Guardada correctamente",
        "<a href='../moduloVentas/indexBoleta.php'>Regresar</a>"
      );
      exit();
    } else {
      $objMensaje = new screenMensajeSistema();
      $objMensaje->screenMensajeSistemaShow(
        "Error",
        "Boleta No se guardo correctamente",
        "<a href='../moduloVentas/indexBoleta.php'>Regresar</a>"
      );
    }
  }
}
