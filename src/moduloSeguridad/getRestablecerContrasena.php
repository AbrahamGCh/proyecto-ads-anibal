<?php
// src/moduloSeguridad/getRestablecerContrasena.php

include_once('controlRestablecerContrasena.php');

$controlador = new controlRestablecerContrasena();

if (isset($_POST['btnValidarUsuario'])) {
    $login = trim($_POST['login'] ?? '');
    $controlador->validarUsuario($login);
} elseif (isset($_POST['btnValidarRespuesta'])) {
    $login = trim($_POST['login'] ?? '');
    $respuesta = trim($_POST['respuesta'] ?? '');
    $controlador->validarRespuesta($login, $respuesta);
} elseif (isset($_POST['btnActualizarContrasena'])) {
    $login = trim($_POST['login'] ?? '');
    $nuevaContrasena = trim($_POST['nuevaContrasena'] ?? '');
    $confirmarContrasena = trim($_POST['confirmarContrasena'] ?? '');
    $controlador->actualizarContrasena($login, $nuevaContrasena, $confirmarContrasena);
} else {
    $controlador->mostrarFormularioRestablecimiento();
}
?>
