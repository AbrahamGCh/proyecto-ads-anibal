<?php
include_once("../moduloVentas/formRegistrarProveedores.php");

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /');
  exit();
}

// Validación de roles
$rol = $_SESSION['rol'];
if ($rol != "vendedor" && $rol != "jefeVentas") {
    header('Location: /moduloSeguridad/indexPanelPrincipal.php');
    exit();
}

$formRegistrarUsuarioObject = new formRegistrarProveedores();
$formRegistrarUsuarioObject->formRegistrarProveedoresShow();
