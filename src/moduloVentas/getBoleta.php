<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/controlEmitirBoleta.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/screenMensajeSistema.php');
session_start();

$mensajeError = '';

$rol = $_SESSION['rol'];

// Validar rol
if ($rol != "cajero" && $rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

// Función para validar si un botón fue presionado
function validarBoton($boton)
{
  return isset($boton);
}

// Función para validar texto (ejemplo: para número de boleta)
function validarTexto($texto)
{
  return !empty($texto) && strlen(trim($texto)) > 0;
}

// Variables para capturar los botones
$btnBuscarFechas = isset($_POST['btnBuscarFechas']) ? $_POST['btnBuscarFechas'] : null;
$btnBuscarNroBoleta = isset($_POST['btnBuscarNroBoleta']) ? $_POST['btnBuscarNroBoleta'] : null;
$btnGenerarPdf = isset($_POST['btnGenerarPdf']) ? $_POST['btnGenerarPdf'] : null;

// Lógica para Buscar por Número de Boleta
if (validarBoton($btnBuscarNroBoleta)) {
  // Validar que se haya ingresado un número de boleta
  if (validarTexto($_POST['txtNroBoleta'])) {
    // Redirigir con el número de boleta si es válido
    header('Location: /moduloVentas/indexBoleta.php?numeroBoleta=' . $_POST['txtNroBoleta']);
    exit();
  } else {
    // Mostrar mensaje de error si el campo está vacío o no válido
    $objMensaje = new screenMensajeSistema();
    $objMensaje->screenMensajeSistemaShow(
      "Error",
      "Ingresar Campos Correctamente",
      "<a href='../moduloVentas/indexBoleta.php'>Regresar</a>"
    );
    exit();
  }
}

// Lógica para Buscar por Fechas
if (validarBoton($btnBuscarFechas)) {
  // Validar que se hayan ingresado las fechas
  if (validarTexto($_POST['txtFechaDesde']) && validarTexto($_POST['txtFechaHasta'])) {
    // Redirigir con las fechas si son válidas
    header('Location: /moduloVentas/indexBoleta.php?desde=' . $_POST['txtFechaDesde'] . '&hasta=' . $_POST['txtFechaHasta']);
    exit();
  } else {
    // Mostrar mensaje de error si las fechas están incompletas
    $objMensaje = new screenMensajeSistema();
    $objMensaje->screenMensajeSistemaShow(
      "Error",
      "Ingresar Fechas Correctamente",
      "<a href='../moduloVentas/indexBoleta.php'>Regresar</a>"
    );
    exit();
  }
}

// Lógica para Generar PDF
if (validarBoton($btnGenerarPdf)) {
  // Validar que se haya ingresado el ID de boleta
  if (validarTexto($_POST['txtIDBoleta'])) {
    $control = new controlEmitirBoleta();
    $control->generarBoletasPdf($_POST['txtIDBoleta']);
    exit();
  } else {
    // Mostrar mensaje de error si el ID de boleta está vacío
    $objMensaje = new screenMensajeSistema();
    $objMensaje->screenMensajeSistemaShow(
      "Error",
      "Seleccionar ID para descarga correctamente",
      "<a href='../moduloVentas/indexBoleta.php'>Regresar</a>"
    );
    exit();
  }
}

// Si no se presionó ningún botón válido
$objMensaje = new screenMensajeSistema();
$objMensaje->screenMensajeSistemaShow(
  "Error",
  "Acceso no permitido",
  "<a href='../index.php'>Regresar</a>"
);
exit();
