<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloSeguridad/formEditarUsuario.php');
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

$usuarioid = $_GET['usuarioid'];
$nombreusuario = $_GET['nombreusuario'];
$contrasena = $_GET['contrasena'];
$nombres = $_GET['nombres'];
$apellidos = $_GET['apellidos'];
$telefono = $_GET['telefono'];
$email = $_GET['email'];
$dni = $_GET['dni'];
$respuestasecreta = $_GET['respuestasecreta'];
$rolid = $_GET['rolid'];

if ($usuarioid === null || $nombreusuario === null) {
  header('Location: /moduloSeguridad/indexGestionarUsuarios.php');
  exit();
}

$_SESSION['usuarioEditando'] = $nombreusuario;
$formEditarUsuarioObject = new formEditarUsuario();
$formEditarUsuarioObject->formEditarUsuarioShow($usuarioid, $nombreusuario,$contrasena,$nombres,$apellidos,$telefono,$email,$dni,$respuestasecreta,$rolid);