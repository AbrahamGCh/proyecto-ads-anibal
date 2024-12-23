<?php
include_once('conexion.php');

class Productos
{
  public function getProductos()
  {
    $sql = "SELECT p.ProductoID AS ID, p.Descripcion AS NombreProducto, pd.UnidadMedida AS Unidad, p.ValorUnitarioVenta AS PrecioVenta, pd.ValorUnitarioCompra AS PrecioCompra FROM producto p INNER JOIN productodetalles pd ON p.ProductoID = pd.ProductoID;";

    $conditions = array();

    if (count($conditions) > 0) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $conexion = Conexion::conectarBD();
    $respuesta = $conexion->query($sql);

    if (!$respuesta) {
      die("Error en la consulta: " . $conexion->error);
    }

    $productos = array();

    while ($fila = $respuesta->fetch_assoc()) {
      $productos[] = $fila;
    }

    Conexion::desConectarBD();
    return $productos;
  }

  public function getProductosBD()
  {
    $sql = "SELECT p.ProductoID, p.Descripcion, pd.UnidadMedida ,p.ValorUnitarioVenta, pd.ValorUnitarioCompra, pr.RazonSocial 
                FROM productodetalles pd 
                JOIN producto p ON pd.ProductoID = p.ProductoID 
                JOIN proveedor pr ON pd.ProveedorID = pr.ProveedorID
                WHERE p.EstadoProducto = 1";

    $conexion = Conexion::conectarBD();
    $respuesta = $conexion->query($sql);

    if (!$respuesta) {
      die("Error en la consulta: " . $conexion->error);
    }

    $productos = array();

    while ($fila = $respuesta->fetch_assoc()) {
      $productos[] = $fila;
    }

    Conexion::desConectarBD();
    return $productos;
  }

  public function agregarProducto($codigo, $descripcion, $precioVenta)
  {
    $sql = "INSERT INTO producto (CodigoProducto, Descripcion, ValorUnitarioVenta, EstadoProducto) VALUES ('$codigo', '$descripcion', $precioVenta, 1);";

    $conexion = Conexion::conectarBD();

    if ($conexion->query($sql) === TRUE) {
      $idProducto = $conexion->insert_id;
      Conexion::desConectarBD();
      return $idProducto;     
    } else {
      echo "Error: " . $sql . "<br>" . $conexion->error;
      Conexion::desConectarBD();
      return false;
    }
  }

  public function obtenerDatosProducto($idProducto)
  {
    $sql = "SELECT p.ProductoID, p.CodigoProducto, p.Descripcion, pd.UnidadMedida, p.ValorUnitarioVenta, pd.ValorUnitarioCompra, pd.ProveedorID
                FROM producto p
                JOIN productodetalles pd ON p.ProductoID = pd.ProductoID
                WHERE p.ProductoID = $idProducto";

    $conexion = Conexion::conectarBD();
    $respuesta = $conexion->query($sql);

    if (!$respuesta) {
      die("Error en la consulta: " . $conexion->error);
    }

    $producto = $respuesta->fetch_assoc();

    Conexion::desConectarBD();
    return $producto;
  }

  public function actualizarProducto($idProducto, $codigo, $descripcion, $precioVenta)
  {
    $sql = "UPDATE producto p
                SET p.CodigoProducto = '$codigo', p.Descripcion = '$descripcion', p.ValorUnitarioVenta = $precioVenta
                WHERE p.ProductoID = $idProducto";

    $conexion = Conexion::conectarBD();

    if ($conexion->query($sql) === TRUE) {
      Conexion::desConectarBD();
      return true;
    } else {
      echo "Error: " . $sql . "<br>" . $conexion->error;
      Conexion::desConectarBD();
      return false;
    }
  }

  public function descartarProducto($idProducto)
  {
    $sql = "UPDATE producto p
                SET p.EstadoProducto = 0
                WHERE p.ProductoID = $idProducto";

    $conexion = Conexion::conectarBD();

    if ($conexion->query($sql) === TRUE) {
      Conexion::desConectarBD();
      return true;
    } else {
      echo "Error: " . $sql . "<br>" . $conexion->error;
      Conexion::desConectarBD();
      return false;
    }
  }

}
