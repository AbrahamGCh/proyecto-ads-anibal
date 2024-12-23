<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/facturas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/controlEmitirFactura.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/screenMensajeSistema.php');

session_start();

$mensajeError = '';

$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';

// Si el rol no es "cajero" o "jefeVentas", redirigir al panel principal
if ($rol != "cajero" && $rol != "jefeVentas") {
    header('Location: /moduloSeguridad/indexPanelPrincipal.php');
    exit();
}

// Validar función para el botón
function validarBoton($boton)
{
    return isset($boton);
}

// Validar función para los textos
function validarTexto($texto)
{
    return !empty($texto) && strlen(trim($texto)) > 0;
}

// Validar datos recibidos
$nrRucDni = isset($_POST['txtNrRucDni']) ? trim($_POST['txtNrRucDni']) : '';
$razonSocial = isset($_POST['txtRazonSocial']) ? trim($_POST['txtRazonSocial']) : '';
$direccion = isset($_POST['txtDireccion']) ? trim($_POST['txtDireccion']) : '';
$obra = isset($_POST['txtObra']) ? trim($_POST['txtObra']) : '';
$moneda = isset($_POST['txtMoneda']) ? trim($_POST['txtMoneda']) : '';
$productosArrayJson = isset($_POST['productsArray']) ? $_POST['productsArray'] : '';

$btnSiguiente = isset($_POST['btnSiguiente']) ? $_POST['btnSiguiente'] : null;

// Decodificar el array de productos
$productosArray = json_decode($productosArrayJson, true);

// Validar si se presionó el botón "Siguiente"
if (validarBoton($btnSiguiente)) {
    // Validar que se haya ingresado toda la información requerida
    if (validarTexto($nrRucDni) && validarTexto($razonSocial) && validarTexto($direccion) && validarTexto($obra) && validarTexto($moneda)) {

        // Validar si los productos son válidos
        if (!is_array($productosArray) || empty($productosArray)) {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "Error",
                "No se ha seleccionado ningún producto.",
                "<a href='../moduloVentas/indexEmitirFactura.php'>Regresar</a>"
            );
            exit();
        }

        // Verificar cantidades válidas de productos
        $erroresProductos = [];
        foreach ($productosArray as $index => $producto) {
            if (
                !isset($producto['id'], $producto['name'], $producto['price'], $producto['amount']) ||
                $producto['amount'] <= 0
            ) {
                $erroresProductos[] = "El producto en la posición " . ($index + 1) . " tiene una cantidad inválida o no se ha seleccionado.";
            }
        }

        // Mostrar errores de productos si existen
        if (!empty($erroresProductos)) {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "Error",
                "No se ingreso cantidades de productos",
                "<a href='../moduloVentas/indexEmitirFactura.php'>Regresar</a>"
            );
            exit();
        }

        // Si todo está bien, proceder con la generación de la factura
        $control = new controlEmitirFactura();
        $resultado = $control->guardarNuevaFactura(
            $nrRucDni,
            $razonSocial,
            $direccion,
            $obra,
            $moneda,
            $productosArray
        );

        if ($resultado['success']) {
            header('Location: /moduloVentas/indexFactura.php?message=' . urlencode($resultado['message']));
            exit();
        } else {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "Error",
                $resultado['message'],
                "<a href='../moduloVentas/indexEmitirFactura.php'>Regresar</a>"
            );
            exit();
        }
    } else {
        // Mostrar mensaje de error si los campos obligatorios están vacíos o no válidos
        $objMensaje = new screenMensajeSistema();
        $objMensaje->screenMensajeSistemaShow(
            "Error",
            "Ingresar campos correctamente.",
            "<a href='../moduloVentas/indexEmitirFactura.php'>Regresar</a>"
        );
        exit();
    }
}

// Si no se presionó ningún botón válido
$objMensaje = new screenMensajeSistema();
$objMensaje->screenMensajeSistemaShow(
    "Error",
    "Acceso no permitido.",
    "<a href='../index.php'>Regresar</a>"
);
exit();
