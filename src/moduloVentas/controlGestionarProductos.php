<?php
include_once('../modelo/productos.php');
include_once('../modelo/proveedores.php');
include_once('../modelo/productodetalles.php');
include_once('./panelGestionarProductos.php');
include_once('./panelAgregarProducto.php');
include_once('../shared/screenMensajeSistema.php');
include_once('./mensajeConfirmacion.php');

class controlGestionarProductos
{
    public function agregarProducto($codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor)
    {
        $producto = new Productos();
        $idProducto = $producto->agregarProducto($codigo, $descripcion,  $precioVenta);
        $productoDetalles = new ProductoDetalles();
        $productoDetalles->agregarProductoDetalle($idProducto, $medida, $precioCompra, $idProveedor);
        header('Location: /moduloVentas/indexGestionarProductos.php');
        exit();
    }

    public function editarProducto($idProducto, $codigo, $descripcion, $medida, $precioVenta, $precioCompra, $idProveedor)
    {
        $idProducto = $_SESSION['idProducto'];
        $producto = new Productos();
        $producto->actualizarProducto($idProducto, $codigo, $descripcion, $precioVenta);
        $productoDetalles = new ProductoDetalles();
        $productoDetalles->editarProductoDetalle($idProducto, $medida, $precioCompra, $idProveedor);
        header('Location: /moduloVentas/indexGestionarProductos.php');
        exit();
    }

    public function mostrarMensajeConfirmacion(){
        $idProducto = $_SESSION['idProducto'];
        $mensajeConfirmacion = new mensajeConfirmacion();
        $mensajeConfirmacion->mensajeConfirmacionShow($idProducto);
    }

    public function eliminarProducto($idProducto)
    {
        $producto = new Productos();
        $producto->descartarProducto($idProducto);
        header('Location: /moduloVentas/indexGestionarProductos.php');
        exit();
    }
}
