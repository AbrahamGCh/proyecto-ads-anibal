<?php
include_once('conexion.php');

class ProductoDetalles
{
    public function agregarProductoDetalle($productoID, $unidadMedida, $valorUnitarioCompra, $idProveedor)
    {
        $sql = "INSERT INTO productodetalles (ProductoID, UnidadMedida, ValorUnitarioCompra, CategoriaFiscal, ProveedorID) VALUES ('$productoID', '$unidadMedida', $valorUnitarioCompra, 'Gravado', $idProveedor);";

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

    public function editarProductoDetalle($productoID, $unidadMedida, $valorUnitarioCompra, $idProveedor)
    {
        $sql = "UPDATE productodetalles pd
                SET pd.UnidadMedida = '$unidadMedida', pd.ValorUnitarioCompra = $valorUnitarioCompra, pd.ProveedorID = $idProveedor
                WHERE pd.ProductoID = $productoID";

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
