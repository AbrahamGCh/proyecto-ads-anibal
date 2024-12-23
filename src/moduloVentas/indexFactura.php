<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelFactura.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/facturas.php');

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /');
  exit();
}

$rol = $_SESSION['rol'];

if ($rol != "cajero" && $rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

$facturas = null;
$numeroFactura = $_GET['numeroFactura'] ?? null;
$fechadesde = $_GET['desde'] ?? null;
$fechahasta = $_GET['hasta'] ?? null;

$facturasObj = new Facturas();
$facturas = $facturasObj->getFacturas($numeroFactura, $fechadesde, $fechahasta);

$panelFacturaObject = new panelFactura();
$panelFacturaObject->panelFacturaShow($facturas);
