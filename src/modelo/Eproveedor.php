<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/modelo/conexion.php');

class Eproveedor
{
    public function obtenerProveedores()
    {
        $conexion = Conexion::conectarBD(); // Conectar a la base de datos

        // Modificación: solo obtener proveedores activos
        $sql = "SELECT * FROM proveedor WHERE EstadoProveedor = 1"; // Consulta para obtener proveedores activos
        $resultado = $conexion->query($sql);

        $proveedores = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $proveedores[] = $fila;
            }
        }

        Conexion::desConectarBD(); // Cerrar conexión

        return $proveedores; // Retornar el array de proveedores
    }

    public function actualizarProveedor($id, $numeroRUC, $razonSocial, $telefono, $email, $direccion)
    {
        $conexion = Conexion::conectarBD();

        $sql = "UPDATE proveedor SET 
                NumeroRUC = ?, 
                RazonSocial = ?, 
                Telefono = ?, 
                Email = ?, 
                Direccion = ?
                WHERE ProveedorID = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssi", $numeroRUC, $razonSocial, $telefono, $email, $direccion, $id);

        if ($stmt->execute()) {
            $resultado = true; // Éxito en la consulta
        } else {
            $resultado = false; // Fallo en la consulta
        }

        $stmt->close();
        Conexion::desConectarBD();

        return $resultado;
    }

    public function verificarProveedorPorRUC($numeroRUC, $idProveedor = null)
    {
        $conexion = Conexion::conectarBD(); // Llamar al método estático de conexión

        $sql = "SELECT ProveedorID FROM proveedor WHERE NumeroRUC = '$numeroRUC'";
        if ($idProveedor !== null) {
            $sql .= " AND ProveedorID != $idProveedor";
        }

        $resultado = $conexion->query($sql);

        Conexion::desConectarBD(); // Cerrar conexión

        return $resultado->num_rows > 0;
    }

    public function eliminarProveedor($idProveedor)
    {
        $conexion = Conexion::conectarBD();
        $sql = "UPDATE proveedor SET EstadoProveedor = 0 WHERE ProveedorID = ?";
        $stmt = $conexion->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $idProveedor);
            $resultado = $stmt->execute();
            $stmt->close();
            Conexion::desConectarBD();
            return $resultado; // Devuelve true o false
        } else {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
    }
    public function guardarProveedor($numeroRUC, $razonSocial, $telefono, $email, $direccion, $fechaRegistro, $estadoProveedor)
    {
        $conexion = Conexion::conectarBD(); // Conectar a la base de datos

        $sql = "INSERT INTO proveedor (NumeroRUC, RazonSocial, Telefono, Email, Direccion, FechaRegistro, EstadoProveedor) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssi", $numeroRUC, $razonSocial, $telefono, $email, $direccion, $fechaRegistro, $estadoProveedor);

        if ($stmt->execute()) {
            $resultado = true; // Registro exitoso
        } else {
            $resultado = false; // Fallo al registrar
        }

        $stmt->close();
        Conexion::desConectarBD(); // Cerrar conexión

        return $resultado; // Devuelve true si fue exitoso, false si no
    }

    public function buscarProveedorPorRUC($numeroRUC)
    {
        $conexion = Conexion::conectarBD(); // Conectar a la base de datos

        $sql = "SELECT * FROM proveedor WHERE NumeroRUC = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $numeroRUC);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $proveedor = null;
        if ($resultado->num_rows > 0) {
            $proveedor = $resultado->fetch_assoc(); // Obtener el proveedor si existe
        }

        $stmt->close();
        Conexion::desConectarBD(); // Cerrar conexión

        return $proveedor; // Retorna el proveedor encontrado o null si no existe
    }
    public function obtenerProveedorPorID($idProveedor)
    {
        $conexion = Conexion::conectarBD();
        $sql = "SELECT * FROM proveedor WHERE ProveedorID = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idProveedor);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }
}
