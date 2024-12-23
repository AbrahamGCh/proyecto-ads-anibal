<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelEmitirCotizacion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/productos.php');

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /');
  exit();
}

$rol = $_SESSION['rol'];

// Si el rol no es "vendedor" o "jefeVentas", redirigir al panel principal
if ($rol != "vendedor" && $rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

$productosObj = new Productos();
$productos = $productosObj->getProductos();

$panelCotizacionObject = new panelEmitirCotizacion();
$panelCotizacionObject->panelEmitirCotizacionShow($productos);
