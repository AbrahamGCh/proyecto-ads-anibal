<?php
include_once(__DIR__ . "/../moduloVentas/controlProveedores.php");
include_once(__DIR__ . "/../moduloVentas/panelProveedores.php");
include_once(__DIR__ . "/../moduloVentas/formRegistrarProveedores.php");
include_once(__DIR__ . "/../shared/screenMensajeSistema.php");

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

// Instanciar controlador y mensaje del sistema
$controlProveedores = new controlProveedores();
$screenMensajeSistema = new screenMensajeSistema();

// Función para validar un botón específico
function validarBoton($nombreBoton)
{
    return isset($_POST[$nombreBoton]);
}

// Función para validar campos vacíos
function validarCamposVacios($campos)
{
    foreach ($campos as $campo => $valor) {
        if (empty($valor)) {
            return "El campo $campo no puede estar vacío.";
        }
    }
    return null;
}

// Función para validar un RUC
function validarRUC($numeroRUC)
{
    return is_numeric($numeroRUC) && strlen($numeroRUC) == 11;
}

// Función para validar un teléfono
function validarTelefono($telefono)
{
    return is_numeric($telefono) && strlen($telefono) >= 7 && strlen($telefono) <= 15;
}

// Procesar solicitud del botón "Editar Proveedor"
if (validarBoton('btnEditarProveedor')) {
    $idProveedor = $_POST['idProveedor'];
    $numeroRUC = trim($_POST['numeroRUC']);
    $razonSocial = trim($_POST['razonSocial']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);

    // Validar campos
    $mensajeError = validarCamposVacios([
        'RUC' => $numeroRUC,
        'Razón Social' => $razonSocial,
        'Teléfono' => $telefono,
        'Email' => $email,
        'Dirección' => $direccion
    ]);

    if ($mensajeError !== null) {
        $controlProveedores->mostrarMensajeError($mensajeError, $idProveedor, $numeroRUC, $razonSocial, $telefono, $email, $direccion);
        exit();
    }

    // Validar campos específicos
    if (!validarRUC($numeroRUC)) {
        $controlProveedores->mostrarMensajeError("El RUC debe tener exactamente 11 dígitos.", $idProveedor, $numeroRUC, $razonSocial, $telefono, $email, $direccion);
        exit();
    }

    if (!validarTelefono($telefono)) {
        $controlProveedores->mostrarMensajeError("El teléfono debe tener entre 7 y 15 dígitos.", $idProveedor, $numeroRUC, $razonSocial, $telefono, $email, $direccion);
        exit();
    }

    // Llamar al controlador para actualizar
    $controlProveedores->actualizarProveedor($idProveedor, $numeroRUC, $razonSocial, $telefono, $email, $direccion);
    exit();
}

// Procesar solicitud del botón "Eliminar Proveedor"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion === 'eliminar') {
        $idProveedor = $_POST['idProveedor'];

        if (!empty($idProveedor) && is_numeric($idProveedor)) {
            // Mostrar mensaje de confirmación
            $controlProveedores->confirmarEliminacion($idProveedor);
            exit();
        } else {
            $controlProveedores->mostrarMensajeError("ID inválido o no proporcionado.");
            exit();
        }
    } elseif ($accion === 'confirmarEliminar') {
        $idProveedor = $_POST['idProveedor'];

        if (!empty($idProveedor) && is_numeric($idProveedor)) {
            // Proceder con la eliminación definitiva
            $controlProveedores->eliminarProveedor($idProveedor);
            exit();
        } else {
            $controlProveedores->mostrarMensajeError("ID inválido o no proporcionado.");
            exit();
        }
    }
}


// Procesar solicitud del botón "Registrar Proveedor"
if (validarBoton('btnAgregarProveedor')) {
    $numeroRUC = htmlspecialchars($_POST['numeroRUC'], ENT_QUOTES, 'UTF-8');
    $razonSocial = htmlspecialchars($_POST['razonSocial'], ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $direccion = htmlspecialchars($_POST['direccion'], ENT_QUOTES, 'UTF-8');
    $fechaRegistro = htmlspecialchars($_POST['fechaRegistro'], ENT_QUOTES, 'UTF-8');
    $estadoProveedor = filter_input(INPUT_POST, 'estadoProveedor', FILTER_VALIDATE_INT);

    // Validar los campos
    $mensajeError = validarCamposVacios([
        'RUC' => $numeroRUC,
        'Razón Social' => $razonSocial,
        'Teléfono' => $telefono,
        'Email' => $email,
        'Dirección' => $direccion,
        'Fecha de Registro' => $fechaRegistro,
        'Estado' => $estadoProveedor
    ]);

    if ($mensajeError !== null) {
        $screenMensajeSistema->screenMensajeSistemaShow("Error", "Campos inválidos", "<a href='/moduloVentas/indexAgregarProveedor.php'>Regresar al panel Registrar Proveedores</a>", $mensajeError);
        exit();
    }

    if (!validarRUC($numeroRUC)) {
        $screenMensajeSistema->screenMensajeSistemaShow("Error", "RUC inválido", "<a href='/moduloVentas/indexAgregarProveedor.php'>Regresar al panel Registrar Proveedores</a>");
        exit();
    }

    if (!validarTelefono($telefono)) {
        $screenMensajeSistema->screenMensajeSistemaShow("Error", "Teléfono inválido", "<a href='/moduloVentas/indexAgregarProveedor.php'>Regresar al panel Registrar Proveedores</a>");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $screenMensajeSistema->screenMensajeSistemaShow("Error", "Email inválido", "<a href='/moduloVentas/indexAgregarProveedor.php'>Regresar al panel Registrar Proveedores</a>");
        exit();
    }

    $controlProveedores->registrarProveedor($numeroRUC, $razonSocial, $telefono, $email, $direccion, $fechaRegistro, $estadoProveedor);
    exit();
}

// Acción por defecto si no se envió el botón o acción no reconocida
$screenMensajeSistema->screenMensajeSistemaShow("Error", "Acción no válida.", "<a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>");
