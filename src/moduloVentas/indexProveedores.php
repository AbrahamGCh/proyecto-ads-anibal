<?php
include_once("../moduloVentas/panelProveedores.php");
include_once("../modelo/Eproveedor.php"); // Modelo del proveedor

session_start();

// Validación de autenticación
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
    header('Location: /'); // Redirigir al login si no está autenticado
    exit();
}

// Validación de roles
$rol = $_SESSION['rol'];
if ($rol != "vendedor" && $rol != "jefeVentas") {
    header('Location: /moduloSeguridad/indexPanelPrincipal.php');
    exit();
}

// Lógica principal: Mostrar lista de proveedores
$EproveedorObject = new Eproveedor();
$proveedores = $EproveedorObject->obtenerProveedores(); // Obtener todos los proveedores

$panelProveedoresObject = new panelProveedores();
$panelProveedoresObject->panelProveedoresShow($proveedores);
