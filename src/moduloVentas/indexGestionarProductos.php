<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelGestionarProductos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/productos.php');

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /moduloSeguridad/getUsuario.php');
  exit();
}

$rol = $_SESSION['rol'];

// Si el rol no es "vendedor" o "jefeVentas", redirigir al panel principal
if ($rol != "vendedor" && $rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

$objProductos = new Productos();
$productos = $objProductos->getProductosBD();

$objPanelGestionarProductos = new panelGestionarProductos();
$objPanelGestionarProductos->panelGestionarProductosShow($productos);

