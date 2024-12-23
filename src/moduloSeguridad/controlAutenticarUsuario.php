<?php
session_start();
include_once('../modelo/usuario.php');
include_once('formAutenticarUsuario.php');
include_once('../shared/screenMensajeSistema.php');

class controlAutenticarUsuario
{
    public function verificarUsuario($login, $password)
    {
        $objUsuario = new usuario();

        // 1. Validar si el usuario existe
        $usuarioExiste = $objUsuario->validarLogin($login);
        if (!$usuarioExiste) {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "MensajeSistema",
                "Este Usuario <strong></strong> no existe.",
                "<a href='../index.php'>Inicio</a>"
            );
            return;
        }

        // 2. Validar la contraseña
        $passwordCorrecta = $objUsuario->validarPassword($login, $password);
        if (!$passwordCorrecta) {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "MensajeSistema",
                "Contraseña <strong></strong> Incorrecta",
                "<a href='../index.php'>Inicio</a>"
            );
            return;
        }

        // 3. Validar si el usuario está activo esta por verse 
        $estadoActivo = $objUsuario->validarEstado($login);
        if (!$estadoActivo) {
            $objMensaje = new screenMensajeSistema();
            $objMensaje->screenMensajeSistemaShow(
                "Usuario Inactivo",
                "El usuario <strong>$login</strong> está inactivo.",
                "<a href='../index.php'>Inicio</a>"
            );
            return;
        }

        // 4. Obtener y guardar el rol del usuario en la sesión
        $rol = $objUsuario->verificarRol($login);
        $_SESSION['login'] = $login;        // Guarda el login del usuario
        $_SESSION['rol'] = $rol;            // Guarda el rol del usuario
        $_SESSION['autenticado'] = "SI";    // Guarda el estado de autenticación

        // 5. Redirigir al panel principal
        header('Location: ../moduloSeguridad/indexPanelPrincipal.php');
        exit();
    }
}
