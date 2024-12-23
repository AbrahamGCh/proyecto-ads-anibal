<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloSeguridad/panelGestionarUsuarios.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/usuario.php');

session_start();

if (!isset($_SESSION['autenticado'])) {
  header('Location: /');
  exit();
}

$rol = $_SESSION['rol'];
$nombreUsuario = $_SESSION['login'];

// Si el rol no es "jefeVentas", redirigir al panel principal
if ($rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

$usuarios = null;

$usuarioObj = new Usuario();
$usuarios = $usuarioObj->getUsuarios($nombreUsuario);

$panelUsuariosObject = new panelGestionarUsuarios();
$panelUsuariosObject->panelGestionarUsuariosShow($usuarios);