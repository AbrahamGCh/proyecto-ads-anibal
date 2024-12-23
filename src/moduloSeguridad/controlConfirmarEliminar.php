<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/usuario.php');

class controlConfirmarEliminar
{
    // Obtener usuario por ID
    public function obtenerUsuarioPorId($idUsuario)
    {
        $usuarioObj = new Usuario();
        return $usuarioObj->getUsuarioById($idUsuario); // Lógica desde el modelo
    }
}
