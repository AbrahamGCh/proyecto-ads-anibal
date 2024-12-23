<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/usuario.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/screenMensajeSistema.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloSeguridad/controlConfirmarEliminar.php'); // Controlador

// Validar autenticación
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== "SI") {
    header('Location: /');
    exit();
}

// Validar la solicitud y el ID del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $idUsuario = $_POST['id'];

    // Delegar la lógica al controlador
    $controlador = new controlConfirmarEliminar();
    $usuario = $controlador->obtenerUsuarioPorId($idUsuario);
} else {
    header('Location: /moduloSeguridad/indexGestionarUsuarios.php');
    exit();
}

// Mostrar la vista
include_once('vistaConfirmarEliminar.php');
