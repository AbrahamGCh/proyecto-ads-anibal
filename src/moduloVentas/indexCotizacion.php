<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/panelCotizacion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/cotizaciones.php');

session_start();

//validacion si esta autenticado
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

$cotizaciones = null;
$numerocotizacion = $_GET['numerocotizacion'] ?? null;
$fechadesde = $_GET['desde'] ?? null;
$fechahasta = $_GET['hasta'] ?? null;

$cotizacionesObj = new Cotizaciones();
$cotizaciones = $cotizacionesObj->getCotizaciones($numerocotizacion, $fechadesde, $fechahasta);

$panelCotizacionObject = new panelCotizacion();
$panelCotizacionObject->panelCotizacionShow($cotizaciones);
