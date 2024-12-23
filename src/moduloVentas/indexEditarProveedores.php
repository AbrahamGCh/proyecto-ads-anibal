<?php
include_once("../moduloVentas/formEditarProveedor.php");    

session_start();

// Validación de autenticación
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

// Obtener parámetros desde la URL
$idProveedor = isset($_GET['id']) ? $_GET['id'] : null;
$numeroRUC = isset($_GET['numeroRUC']) ? $_GET['numeroRUC'] : null;
$razonSocial = isset($_GET['razonSocial']) ? $_GET['razonSocial'] : null;
$telefono = isset($_GET['telefono']) ? $_GET['telefono'] : null;
$email = isset($_GET['email']) ? $_GET['email'] : null;
$direccion = isset($_GET['direccion']) ? $_GET['direccion'] : null;

// Validar si los parámetros existen
if ($idProveedor === null || $razonSocial === null) {
    header('Location: /moduloVentas/indexProveedores.php');
    exit();
}

// Cargar el formulario con los datos
$formEditarProveedorObject = new formEditarProveedor();
$formEditarProveedorObject->formEditarProveedorShow($idProveedor, $numeroRUC, $razonSocial, $telefono, $email, $direccion);
?>
