<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/Eproveedor.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/screenMensajeSistema.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloVentas/formRegistrarProveedores.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/moduloSeguridad/menuPrincipalSistema.php');

class controlProveedores
{
    private $screenMensajeSistema;

    public function __construct()
    {
        $this->screenMensajeSistema = new screenMensajeSistema();
    }

    public function actualizarProveedor($id, $numeroRUC, $razonSocial, $telefono, $email, $direccion)
    {
        $modeloProveedor = new Eproveedor();

        // Verificar si el proveedor ya existe con el mismo RUC
        if ($modeloProveedor->verificarProveedorPorRUC($numeroRUC, $id)) {
            $this->mostrarMensajeError(
                "El número RUC ya está registrado.",
                $id,
                $numeroRUC,
                $razonSocial,
                $telefono,
                $email,
                $direccion
            );
            return;
        }

        // Actualizar proveedor
        $resultado = $modeloProveedor->actualizarProveedor($id, $numeroRUC, $razonSocial, $telefono, $email, $direccion);

        if ($resultado) {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Éxito",
                "Proveedor actualizado correctamente.",
                "<a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>"
            );
        } else {
            $this->mostrarMensajeError(
                "No se pudo actualizar el proveedor. Inténtelo nuevamente.",
                $id,
                $numeroRUC,
                $razonSocial,
                $telefono,
                $email,
                $direccion
            );
        }
    }

    public function mostrarMensajeError($mensaje, $id = null, $numeroRUC = "", $razonSocial = "", $telefono = "", $email = "", $direccion = "")
    {
        $link = $id !== null
            ? "<a href='/moduloVentas/indexEditarProveedores.php?id=$id&numeroRUC=$numeroRUC&razonSocial=$razonSocial&telefono=$telefono&email=$email&direccion=$direccion'>Volver a editar proveedor</a>"
            : "<a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>";

        $this->screenMensajeSistema->screenMensajeSistemaShow("Error", $mensaje, $link);
    }

    public function verificarProveedorPorRUC($numeroRUC, $idProveedor = null)
    {
        $modeloProveedor = new Eproveedor();
        return $modeloProveedor->verificarProveedorPorRUC($numeroRUC, $idProveedor);
    }




    public function eliminarProveedor($idProveedor)
    {
        $modeloProveedor = new Eproveedor();

        $resultado = $modeloProveedor->eliminarProveedor($idProveedor);

        if ($resultado) {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Éxito",
                "Proveedor eliminado correctamente.",
                "<a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>"
            );
        } else {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Error",
                "No se pudo eliminar el proveedor. Inténtelo nuevamente.",
                "<a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>"
            );
        }
    }

    public function confirmarEliminacion($idProveedor)
    {
        $modeloProveedor = new Eproveedor();
        $proveedor = $modeloProveedor->obtenerProveedorPorID($idProveedor); // Método que debe existir en Eproveedor

        if ($proveedor) {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Advertencia",
                "¿Estás seguro de que deseas eliminar este proveedor?",
                "<form action='/moduloVentas/getProveedor.php' method='POST'>
                <input type='hidden' name='accion' value='confirmarEliminar'>
                <input type='hidden' name='idProveedor' value='$idProveedor'>
                <button type='submit' style='background-color: red; color: white; padding: 10px; border: none; cursor: pointer;'>Sí, eliminar</button>
                <a href='/moduloVentas/indexProveedores.php' style='padding: 10px; background-color: green; color: white; text-decoration: none;'>Cancelar</a>
             </form>"
            );
        } else {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Error",
                "El proveedor no existe.",
                "<a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>"
            );
        }
    }

    public function registrarProveedor($numeroRUC, $razonSocial, $telefono, $email, $direccion, $fechaRegistro, $estadoProveedor)
    {
        $modeloProveedor = new Eproveedor();

        // Verificar si el proveedor ya existe con el mismo RUC
        if ($modeloProveedor->verificarProveedorPorRUC($numeroRUC)) {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Error",
                "Registro fallido",
                "Ya existe un proveedor registrado con el mismo RUC. <a href='/moduloVentas/indexAgregarProveedor.php'>Regresar al formulario de registro</a>"
            );
            return;
        }

        // Guardar proveedor
        $resultado = $modeloProveedor->guardarProveedor($numeroRUC, $razonSocial, $telefono, $email, $direccion, $fechaRegistro, $estadoProveedor);

        if ($resultado) {
            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Éxito",
                "Registro exitoso",
                "Proveedor registrado correctamente. <a href='/moduloVentas/indexProveedores.php'>Regresar al panel principal</a>"
            );
        } else {
            $formRegistrarProveedores = new formRegistrarProveedores();
            $formRegistrarProveedores->formRegistrarProveedoresShow();

            $this->screenMensajeSistema->screenMensajeSistemaShow(
                "Error",
                "Error",
                "Hubo un error al intentar registrar al proveedor. Inténtelo nuevamente."
            );
        }
    }
}
