<?php
session_start();
include_once("controlReporteVentas.php");
include_once("../shared/screenMensajeSistema.php");

// Validar autenticación
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
    $mensaje = new screenMensajeSistema();
    $mensaje->screenMensajeSistemaShow(
        "Acceso Denegado",
        "No tiene permisos para acceder a esta página.",
        "<a href='../index.php'>Inicio</a>"
    );
    exit();
}

// Capturar fechas y botón presionado
$desde = isset($_POST['desde']) ? trim($_POST['desde']) : "";
$hasta = isset($_POST['hasta']) ? trim($_POST['hasta']) : "";
$control = new controlReporteVentas();

// Inicializar la variable de datos
$datosReporte = [];

// Función para validar fechas
function validarFechas($desde, $hasta) {
    if (empty($desde) || empty($hasta)) {
        return false;
    }
    $fechaDesde = DateTime::createFromFormat('Y-m-d', $desde);
    $fechaHasta = DateTime::createFromFormat('Y-m-d', $hasta);
    return $fechaDesde && $fechaHasta && $fechaDesde <= $fechaHasta;
}

// Función para descargar reporte en CSV
function descargarCSV($datosReporte) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="reporte_ventas.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Fecha', 'Cliente', 'Monto']); // Encabezados
    foreach ($datosReporte as $fila) {
        fputcsv($output, $fila);
    }
    fclose($output);
    exit();
}

// Validar botón "Buscar"
if (isset($_POST['btnBuscar'])) {
    if (empty($desde) || empty($hasta)) {
        // Mostrar error si las fechas están vacías
        $control->mostrarError("Campos incompletos", "Debe completar las fechas 'Desde' y 'Hasta'.", "indexReporteventas.php");
    } else {
        // Generar el reporte y cargar los datos
        $datosReporte = $control->generarReporte($desde, $hasta);
        include_once("panelReporteVentas.php");
        $vista = new panelReporteVentas();
        $vista->panelReporteVentasShow($datosReporte);
        exit();
    }
} 

// Validar botón "Generar Reporte" (PDF)
elseif (isset($_POST['btnGenerarReporte'])) {
    $desde = isset($_POST['desde']) ? trim($_POST['desde']) : "";
    $hasta = isset($_POST['hasta']) ? trim($_POST['hasta']) : "";

    // Validar si las fechas están vacías
    if (empty($desde) || empty($hasta)) {
        $control->mostrarError(
            "Campos incompletos",
            "Debe completar las fechas 'Desde' y 'Hasta'.",
            "indexReporteventas.php"
        );
        exit();
    } else {
        // Generar el PDF
        $control->generarPDF($desde, $hasta);
        exit();
    }
}

// Validar botón "Descargar CSV"
elseif (isset($_POST['btnDescargarCSV'])) {
    if (!validarFechas($desde, $hasta)) {
        $control->mostrarError(
            "Campos incompletos",
            "Debe completar las fechas 'Desde' y 'Hasta' correctamente.",
            "indexReporteventas.php"
        );
        exit();
    } else {
        // Generar los datos y descargar CSV
        $datosReporte = $control->generarReporte($desde, $hasta);
        descargarCSV($datosReporte);
    }
}
?>
