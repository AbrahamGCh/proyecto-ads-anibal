<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['autenticado'])) {
    // Redireccionar al inicio si no está autenticado
    header('Location: /');
    exit();
}



// Incluir la clase que muestra el panel principal
include_once('menuPrincipalSistema.php');

// Mostrar la pantalla de bienvenida
$objBienvenida = new menuPrincipalSistema();
$objBienvenida->menuPrincipalSistemaShow("Bienvenido al sistema, " . $_SESSION['login']);
?>
