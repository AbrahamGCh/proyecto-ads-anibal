<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelEditarProducto.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/proveedores.php');
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

$idProducto = $_SESSION['idProducto'];
$objProducto = new Productos();
$producto = $objProducto->obtenerDatosProducto($idProducto);

$objProveedores = new Proveedores();
$proveedores = $objProveedores->getProveedores();

$objPanelEditarProducto = new panelEditarProducto();
$objPanelEditarProducto->panelEditarProductoShow($producto, $proveedores);

