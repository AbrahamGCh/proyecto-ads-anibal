<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/usuario.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/screenMensajeSistema.php');

class controlGestionarUsuarios
{
    public function eliminarUsuario($idusuario)
    {
        // Instanciar el modelo
        $usuarioObject = new Usuario();

        // Realizar el soft delete
        if ($usuarioObject->eliminarUsuario($idusuario)) {
            // Éxito
            $screenMensajeSistemaObject = new screenMensajeSistema();
            $screenMensajeSistemaObject->screenMensajeSistemaShow(
                "El usuario ha sido eliminado exitosamente",
                "<a href='/moduloSeguridad/indexGestionarUsuarios.php'>Regresar al panel anterior</a>"
            );
        } else {
            // Error en la lógica
            $screenMensajeSistemaObject = new screenMensajeSistema();
            $screenMensajeSistemaObject->screenMensajeSistemaShow(
                "Error al eliminar el usuario",
                "<a href='/moduloSeguridad/indexGestionarUsuarios.php'>Regresar al panel anterior</a>"
            );
        }
    }

    public function actualizarUsuario($txtNombreUsuario, $txtContrasena, $txtNombres, $txtApellidos, $txtTelefono, $txtEmail, $txtDni, $txtRespuestaSecreta, $txtRolId, $txtUsuarioId)
    {
        $UsuarioObject = new Usuario();
        $resultado = $UsuarioObject->actualizarUsuario($txtNombreUsuario, $txtContrasena, $txtNombres, $txtApellidos, $txtTelefono, $txtEmail, $txtDni, $txtRespuestaSecreta, $txtRolId, $txtUsuarioId);

        if ($resultado) {
            $screenMensajeSistemaObject = new screenMensajeSistema();
            $screenMensajeSistemaObject->screenMensajeSistemaShow(
                'Los datos del usuario han sido editados correctamente',
                "<a href='/moduloSeguridad/indexGestionarUsuarios.php'>Regresar al panel anterior</a>"
            );
        } else {
            $screenMensajeSistemaObject = new screenMensajeSistema();
            $screenMensajeSistemaObject->screenMensajeSistemaShow(
                'Error al editar el usuario',
                "<a href='/moduloSeguridad/indexGestionarUsuarios.php'>Regresar al panel anterior</a>"
            );
        }
    }

    public function agregarUsuario($txtNombreUsuario, $txtContrasena, $txtNombres, $txtApellidos, $txtTelefono, $txtEmail, $txtDni, $txtRespuestaSecreta, $txtRolId)
    {
        // Instanciar el modelo
        $usuarioObject = new Usuario();
        // Intentar agregar el usuario
        $resultado = $usuarioObject->agregarUsuario($txtNombreUsuario, $txtContrasena, $txtNombres, $txtApellidos, $txtTelefono, $txtEmail, $txtDni, $txtRespuestaSecreta, $txtRolId);
        if ($resultado) {
            // Si el usuario se agregó correctamente
            $screenMensajeSistemaObject = new screenMensajeSistema();
            $screenMensajeSistemaObject->screenMensajeSistemaShow(
                'El usuario ha sido agregado correctamente',
                "<a href='/moduloSeguridad/indexGestionarUsuarios.php'>Regresar al panel anterior</a>"
            );
        } else {
            // Error en la lógica de agregar
            $screenMensajeSistemaObject = new screenMensajeSistema();
            $screenMensajeSistemaObject->screenMensajeSistemaShow(
                'Error al agregar el usuario',
                "<a href='/moduloSeguridad/indexGestionarUsuarios.php'>Regresar al panel anterior</a>"
            );
        }
    }
}
