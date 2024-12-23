<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloSeguridad/formAgregarUsuario.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/usuario.php');

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /');
  exit();
}

$rol = $_SESSION['rol'];

// Si el rol no es "jefeVentas", redirigir al panel principal
if ($rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

$panelAgregarUsuarioObject = new formAgregarUsuario();
$panelAgregarUsuarioObject->formAgregarUsuarioShow();