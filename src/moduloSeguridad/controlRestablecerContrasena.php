<?php
// src/moduloSeguridad/controlRestablecerContrasena.php

include_once('../moduloSeguridad/formRestablecerContrasena.php');
include_once('../moduloSeguridad/preguntaSeguridad.php');
include_once('../moduloSeguridad/formNuevaContrasena.php');
include_once('../shared/screenMensajeSistema.php');
include_once('../modelo/usuario.php');

class controlRestablecerContrasena
{
    public function mostrarFormularioRestablecimiento()
    {
        $formulario = new formRestablecerContrasena();
        $formulario->formRestablecerContrasenaShow();
        exit();
    }

    public function validarUsuario($login)
    {
        if (empty($login)) {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","El campo 'usuario' está vacío.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }

        $usuario = new Usuario();
        $datosUsuario = $usuario->obtenerUsuarioPorLogin($login);

        if (!$datosUsuario) {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","Usuario no encontrado.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }

        $preguntaSeguridad = new preguntaSeguridad();
        $preguntaSeguridad->preguntaSeguridadShow($login, "¿Cómo se llama tu mascota?");
        exit();
    }

    public function validarRespuesta($login, $respuesta)
    {
        if (empty($respuesta)) {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","El campo 'respuesta' está vacío.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }

        $usuario = new Usuario();
        if ($usuario->validarRespuestaSeguridad($login, $respuesta)) {
            $formNuevaContrasena = new formNuevaContrasena();
            $formNuevaContrasena->formNuevaContrasenaShow($login);
            exit();
        } else {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","Respuesta incorrecta.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }
    }

    public function actualizarContrasena($login, $nuevaContrasena, $confirmarContrasena)
    {
        if (empty($nuevaContrasena) || empty($confirmarContrasena)) {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","Todos los campos son obligatorios.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }

        if ($nuevaContrasena !== $confirmarContrasena) {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","Las contraseñas no coinciden.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }

        $usuario = new Usuario();
        if ($usuario->actualizarContrasena($login, $nuevaContrasena)) {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","Contraseña actualizada con éxito.", "<a href='../index.php'>Iniciar sesión</a>");
            exit();
        } else {
            $mensaje = new screenMensajeSistema();
            $mensaje->screenMensajeSistemaShow("MensajeSistema","Error al actualizar la contraseña.", "<a href='./controlRestablecerContrasena.php'>Regresar</a>");
            exit();
        }
    }
}

// Lógica para determinar la acción según el flujo
$controlador = new controlRestablecerContrasena();

if (isset($_POST['btnValidarUsuario'])) {
    $controlador->validarUsuario($_POST['login']);
} elseif (isset($_POST['btnValidarRespuesta'])) {
    $controlador->validarRespuesta($_POST['login'], $_POST['respuesta']);
} elseif (isset($_POST['btnActualizarContrasena'])) {
    $controlador->actualizarContrasena($_POST['login'], $_POST['nuevaContrasena'], $_POST['confirmarContrasena']);
} else {
    $controlador->mostrarFormularioRestablecimiento();
}
?>
