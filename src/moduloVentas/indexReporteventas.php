<?php
session_start();
include_once("../moduloVentas/panelReporteVentas.php");

// Validar si el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
    header('Location: ../index.php'); // Acceso no permitido
    exit();
}



// Mostrar la vista inicial vacía
$panelReporteVentas = new panelReporteVentas();
$panelReporteVentas->panelReporteVentasShow([]);
