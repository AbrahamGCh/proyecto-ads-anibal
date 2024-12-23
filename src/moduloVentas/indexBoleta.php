<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelBoleta.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/boletas.php');

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /');
  exit();
}

$rol = $_SESSION['rol'];

// Si el rol no es "vendedor" o "jefeVentas", redirigir al panel principal
if ($rol != "cajero" && $rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

$boletas = null;
$numeroboleta = $_GET['numeroBoleta'] ?? null;
$fechadesde = $_GET['desde'] ?? null;
$fechahasta = $_GET['hasta'] ?? null;

$boletasObj = new Boletas();
$boletas = $boletasObj->getBoletas($numeroboleta, $fechadesde, $fechahasta);

$panelBoletaObject = new panelBoleta();
$panelBoletaObject->panelBoletaShow($boletas);
