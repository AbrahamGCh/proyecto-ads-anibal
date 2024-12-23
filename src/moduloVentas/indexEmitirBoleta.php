<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelEmitirBoleta.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/productos.php');

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

$productosObj = new Productos();
$productos = $productosObj->getProductos();

$panelEmitirFacturaObject = new panelEmitirBoleta();
$panelEmitirFacturaObject->panelEmitirBoletaShow($productos);
