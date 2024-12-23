<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/facturas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class controlEmitirFactura
{
  public function generarPdf($factura)
  {
    $pdf = new TCPDF();

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Nombre');
    $pdf->SetTitle('Factura');
    $pdf->SetSubject('Detalle de Factura');
    $pdf->SetKeywords('TCPDF, PDF, factura');

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $numeroFactura = $factura['NumeroCorrelativo'];
    $cliente = $factura['Cliente'];
    $obra = $factura['Obra'];
    $fechaEmision = $factura['FechaEmision'];
    $moneda = $factura['Moneda'];
    $importeTotal = $factura['ImporteTotal'];
    $costoTotal = $factura['CostoTotal'];

    $html = "
      <h1>Factura N° $numeroFactura</h1>
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

    foreach ($factura['Detalles'] as $detalle) {
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
    $pdf->Output("Factura_$numeroFactura.pdf", 'I');
  }

  public function generarFacturasPdf($idFactura)
  {
    $facturasModel = new Facturas();
    $factura = $facturasModel->obtenerFacturaCompleta($idFactura);

    if ($factura !== null) {
      $this->generarPdf($factura);
    } else {
      echo "No se encontró la factura con ID: $idFactura";
    }
  }

  public function guardarNuevaFactura(
    $nrRucDni,
    $razonSocial,
    $direccion,
    $obra,
    $moneda,
    $productosArray
  ) {
    $facturasModel = new Facturas();

    $usuarioID = 1;
    $serieComprobanteID = 1;

    $opGravada = 0;
    foreach ($productosArray as $prod) {
      $opGravada += floatval($prod['price']) * intval($prod['amount']);
    }

    $totalIGV = $opGravada * 0.18;
    $importeTotal = $opGravada + $totalIGV;
    $fechaHoy = date('Y-m-d');
    $tipoPago = "Al contado";
    $formaPago = "Efectivo";
    $observaciones = "Factura generada desde el sistema";

    $clienteID = $facturasModel->obtenerClienteIdPorDocumento($nrRucDni, $razonSocial, $direccion);
    if (!$clienteID) {
      return ['success' => false, 'message' => 'No se pudo determinar el ClienteID.'];
    }

    $numeroCorrelativo = $facturasModel->guardarNuevaFactura(
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
        "Factura Guardada correctamente",
        "<a href='../moduloVentas/indexFactura.php'>Regresar</a>"
      );
      exit();
    } else {
      $objMensaje = new screenMensajeSistema();
      $objMensaje->screenMensajeSistemaShow(
        "Error",
        "Factura No se guardo correctamente",
        "<a href='../moduloVentas/indexFactura.php'>Regresar</a>"
      );
    }
  }
}
