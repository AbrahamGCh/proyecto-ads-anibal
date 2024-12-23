<?php
include_once('conexion.php');

class Proveedores
{
  public function getProveedores()
  {
    $sql = "SELECT * from proveedor";

    $conexion = Conexion::conectarBD();
    $respuesta = $conexion->query($sql);

    if (!$respuesta) {
      die("Error en la consulta: " . $conexion->error);
    }

    $proveedores = array();

    while ($fila = $respuesta->fetch_assoc()) {
      $proveedores[] = $fila;
    }

    Conexion::desConectarBD();
    return $proveedores;
  }

}
