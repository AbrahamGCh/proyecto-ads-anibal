<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/cotizaciones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/controlEmitirCotizacion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/screenMensajeSistema.php');


session_start();

$mensajeError = '';

$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';

// Si el rol no es "vendedor" o "jefeVentas", redirigir al panel principal
if ($rol != "vendedor" && $rol != "jefeVentas") {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
}

function validarBoton($btnbuscar)
{
  return isset($btnbuscar);


}

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


// Decodificar el array de productos y validar que incluyan cantidades
$productosArray = json_decode($productosArrayJson, true);

if (validarBoton($btnSiguiente)) {
    // Validar que se haya ingresado la información requerida
    if (validarTexto($_POST['txtNrRucDni'] && $_POST['txtRazonSocial'] && $_POST['txtDireccion'] && $_POST['txtObra'] && $_POST['txtMoneda'])) {
        
        // Validar si hay productos seleccionados
        if (empty($productosArray)) {
        // Mostrar mensaje de error si los campos obligatorios están vacíos o no válidos
        $objMensaje = new screenMensajeSistema();
        $objMensaje->screenMensajeSistemaShow(
            "Error",
            "No se ha seleccionado ningún producto.",
            "<a href='../moduloVentas/indexEmitirCotizacion.php'>Regresar</a>"
        );
        exit();
        }

        // Validar cada producto en el array
        $erroresProductos = [];
        foreach ($productosArray as $index => $producto) {
            // Verificar que las claves necesarias estén definidas y que la cantidad sea mayor a 0
            if (
                !isset($producto['id'], $producto['name'], $producto['price'], $producto['amount']) || 
                $producto['amount'] <= 0
            ) {
                $erroresProductos[] = "El producto en la posición " . ($index + 1) . " tiene una cantidad inválida o no se ha seleccionado.";
            }
        }

        // Si hay errores en los productos, mostrar el mensaje de error
        if (!empty($erroresProductos)) {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "Error",
                "No se ingreso cantidades de productos",
                "<a href='../moduloVentas/indexEmitirCotizacion.php'>Regresar</a>"
            );
            exit();
            
            foreach ($erroresProductos as $error) {
                $mensajeError .= "- $error<br>";
            }
            $mensajeError .= "<a href='../moduloVentas/indexEmitirCotizacion.php'>Regresar</a>";
            echo $mensajeError;
            exit();
        }

        // Si todo está bien, proceder con la cotización
        $control = new controlEmitirCotizacion();
        $resultado = $control->guardarNuevaCotizacion(
            $_POST['txtNrRucDni'],
            $_POST['txtRazonSocial'],
            $_POST['txtDireccion'],
            $_POST['txtObra'],
            $_POST['txtMoneda'],
            $productosArray
        );

        if ($resultado['success']) {
            header('Location: /moduloVentas/indexCotizacion.php?message=' . urlencode($resultado['message']));
            exit();
        } else {
            echo $resultado['message'];
            exit();
        }
    } else {
        // Mostrar mensaje de error si los campos obligatorios están vacíos o no válidos
        $objMensaje = new screenMensajeSistema();
        $objMensaje->screenMensajeSistemaShow(
            "Error",
            "Ingresar Campos Correctamente",
            "<a href='../moduloVentas/indexEmitirCotizacion.php'>Regresar</a>"
        );
        exit();
    }
}



// Si no se presionó ningún botón válido
$objMensaje = new screenMensajeSistema();
$objMensaje->screenMensajeSistemaShow(
  "Error",
  "Acceso no permitido",
  "<a href='../index.php'>Regresar</a>"
);
exit();