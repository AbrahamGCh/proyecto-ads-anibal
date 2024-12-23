<?php
include_once('./controlGestionarProductos.php');
include_once('../shared/screenMensajeSistema.php');

session_start();

$mensajeError = '';

$rol = $_SESSION['rol'] ?? null;

// Si el rol no es "vendedor" o "jefeVentas", redirigir al panel principal
if ($rol != "vendedor" && $rol != "jefeVentas" && $rol != null) {
  header('Location: /moduloSeguridad/indexPanelPrincipal.php');
  exit();
} else if ($rol == null) {
  header('Location: /moduloSeguridad/getUsuario.php');
  exit();
}

function validarBoton($boton){
    return isset($boton);
}

function verificarCamposProducto($codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor)
{
    global $mensaje;
    if (strlen($codigo) < 4 || strlen($descripcion) < 4 || $medida=="" || empty($precioVenta) || empty($precioCompra)) {
        $mensaje = 'Llenar todos los campos';
        return false;
    } else if (empty($idProveedor)) {
        $mensaje = 'Seleccione un proveedor';
        return false;
    }
    return true;
}

$btnProductos = $_POST['btnProductos'] ?? null;
$btnAgregarProducto = $_POST['btnAgregarProducto'] ?? null;
$btnEditarProducto = $_POST['btnEditarProducto'] ?? null;
$btnEliminarProducto = $_POST['btnEliminarProducto'] ?? null;
$btnGuardarProducto = $_POST['btnGuardarProducto'] ?? null;
$btnGuardar = $_POST['btnGuardar'] ?? null;
$btnConfirmarEliminar = $_POST['btnConfirmarEliminar'] ?? null;

$objControlGestionarProductos = new controlGestionarProductos();

if (validarBoton($btnProductos)) {
    header('Location: /moduloVentas/indexGestionarProductos.php');
} else if (validarBoton($btnAgregarProducto)) {
    header('Location: /moduloVentas/indexAgregarProducto.php');
} else if (validarBoton($btnEditarProducto)) {
    $idProducto = $_POST['idProducto'];
    $_SESSION['idProducto'] = $idProducto;
    header('Location: /moduloVentas/indexEditarProducto.php');
} else if (validarBoton($btnEliminarProducto)) {
    $idProducto = $_POST['idProducto'];
    $_SESSION['idProducto'] = $idProducto;
    $objControlGestionarProductos->mostrarMensajeConfirmacion();
} else if (validarBoton($btnGuardarProducto)) {
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $medida = $_POST['medida'];
    $precioVenta = $_POST['precioVenta'];
    $precioCompra = $_POST['precioCompra'];
    $idProveedor = $_POST['proveedor'];

    if (verificarCamposProducto($codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor)) {
        $objControlGestionarProductos->agregarProducto($codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor);
    } else {
        $mensajeSistema = new screenMensajeSistema();
        $mensajeSistema->screenMensajeSistemaShow($mensaje, "<a href='/moduloVentas/indexAgregarProducto.php'>Volver</a>");
    }
} else if (validarBoton($btnGuardar)) {
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $medida = $_POST['medida'];
    $precioVenta = $_POST['precioVenta'];
    $precioCompra = $_POST['precioCompra'];
    $idProveedor = $_POST['proveedor'];
    $idProducto = $_POST['idProducto'];

    if (verificarCamposProducto($codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor)) {
        $objControlGestionarProductos->editarProducto($idProducto, $codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor);
    } else {
        $mensajeSistema = new screenMensajeSistema();
        $mensajeSistema->screenMensajeSistemaShow($mensaje, "<a href='/moduloVentas/indexEditarProducto.php'>Volver</a>");
    }

} else if (validarBoton($btnConfirmarEliminar)) {
    $idProducto = $_SESSION['idProducto'];
    $objControlGestionarProductos->eliminarProducto($idProducto);
} else {
    header('Location: /moduloSeguridad/getUsuario.php');
    exit();
}