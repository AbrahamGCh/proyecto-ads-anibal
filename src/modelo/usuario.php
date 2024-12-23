<?php
include_once('conexion.php');

class Usuario extends conexion
{
    // Validar si el usuario existe
    public function validarLogin($login)
    {
        $sql = "SELECT NombreUsuario FROM usuario WHERE NombreUsuario = '$login'";
        $conexion = Conexion::conectarBD();
        $respuesta = $conexion->query($sql);

        if (!$respuesta) {
            die("Error en la consulta: " . $conexion->error);
        }

        $numFilas = $respuesta->num_rows;
        Conexion::desConectarBD();

        return $numFilas === 1 ? 1 : 0;
    }

    // Validar si la contraseña es correcta
    public function validarPassword($login, $password)
    {
        $sql = "SELECT NombreUsuario FROM usuario WHERE NombreUsuario = '$login' AND Contraseña = '$password'";
        $conexion = Conexion::conectarBD();
        $respuesta = $conexion->query($sql);

        if (!$respuesta) {
            die("Error en la consulta: " . $conexion->error);
        }

        $numFilas = $respuesta->num_rows;
        Conexion::desConectarBD();

        return $numFilas === 1 ? 1 : 0;
    }

    // Validar si el usuario está activo
    public function validarEstado($login)
    {
        $sql = "SELECT EstadoUsuario FROM usuario WHERE NombreUsuario = '$login' AND EstadoUsuario = '1'";
        $conexion = Conexion::conectarBD();
        $respuesta = $conexion->query($sql);

        if (!$respuesta) {
            die("Error en la consulta: " . $conexion->error);
        }

        $numFilas = $respuesta->num_rows;
        Conexion::desConectarBD();

        // Si hay una fila, el usuario está activo
        return $numFilas === 1 ? 1 : 0;
    }

    public function verificarRol($login)
    {
        $sql = "SELECT RolID FROM usuario WHERE NombreUsuario = '$login'";
        $conexion = Conexion::conectarBD();
        $respuesta = $conexion->query($sql);

        if (!$respuesta) {
            die("Error en la consulta: " . $conexion->error);
        }

        if ($fila = $respuesta->fetch_assoc()) {
            $rol = $fila['RolID'];

            // Convertir RolID en un nombre de rol
            switch ($rol) {
                case 1:
                    $rolNombre = "jefeVentas";
                    break;
                case 2:
                    $rolNombre = "vendedor";
                    break;
                case 3:
                    $rolNombre = "cajero";
                    break;
                default:
                    $rolNombre = "desconocido";
            }

            Conexion::desConectarBD();
            return $rolNombre;
        }

        Conexion::desConectarBD();
        return null; // No se encontró el rol
    }

    public function getUsuarios($nombreUsuario)
    {
        // Consulta SQL usando un parámetro preparado
        $sql = "
        SELECT
            u.UsuarioID,
            u.NombreUsuario,
            u.Contraseña,
            u.Nombres,
            u.Apellidos,
            u.Telefono,
            u.Email,
            u.DNI,
            u.RespuestaSecreta,
            u.RolID
        FROM usuario u
        WHERE estadousuario = 1
          AND u.NombreUsuario != ?
    ";

        // Establecer conexión a la base de datos
        $conexion = Conexion::conectarBD();

        // Preparar la consulta
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        // Asociar el parámetro a la consulta
        $stmt->bind_param("s", $nombreUsuario); // "s" indica un parámetro string

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result();

        // Crear un array para almacenar los usuarios
        $usuarios = array();
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        // Cerrar la consulta y la conexión
        $stmt->close();
        Conexion::desConectarBD();

        // Retornar los usuarios obtenidos
        return $usuarios;
    }


    public function getUsuarioById($UsuarioID)
    {
        // Consulta SQL para obtener un usuario específico por ID
        $sql = "
        SELECT
            u.UsuarioID,
            u.NombreUsuario,
            u.Contraseña,
            u.Nombres,
            u.Apellidos,
            u.Telefono,
            u.Email,
            u.DNI,
            u.RespuestaSecreta,
            u.RolID
        FROM usuario u
        WHERE u.estadousuario = 1 AND u.UsuarioID = ?
        ";

        // Establecer conexión a la base de datos
        $conexion = Conexion::conectarBD();

        // Preparar la consulta SQL
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        // Asociar parámetros a la consulta preparada (evita inyecciones SQL)
        $stmt->bind_param("i", $UsuarioID); // "i" indica un parámetro entero

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result();

        // Verificar si se encontró el usuario
        if ($resultado->num_rows > 0) {
            // Obtener los datos del usuario
            $usuario = $resultado->fetch_assoc();
        } else {
            $usuario = null; // No se encontró ningún usuario con ese ID
        }

        // Cerrar la consulta y la conexión
        $stmt->close();
        Conexion::desConectarBD();

        // Retornar el usuario (o null si no existe)
        return $usuario;
    }

    public function eliminarUsuario($idusuario)
    {
        $conexion = Conexion::conectarBD();
        $sql = "UPDATE Usuario SET estadousuario = 0 WHERE usuarioid = ?";
        $stmt = $conexion->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $idusuario);
            $resultado = $stmt->execute();
            $stmt->close();
            Conexion::desConectarBD();
            return $resultado; // Devuelve true o false
        } else {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
    }

    public function actualizarUsuario($nombreUsuario, $contrasena, $nombres, $apellidos, $telefono, $email, $dni, $respuestaSecreta, $rolId, $usuarioId)
    {
        $sql = "UPDATE usuario SET 
            NombreUsuario = ?, 
            Contraseña = ?, 
            Nombres = ?, 
            Apellidos = ?, 
            Telefono = ?, 
            Email = ?, 
            DNI = ?, 
            RespuestaSecreta = ?, 
            RolID = ? 
            WHERE UsuarioID = ?";

        $conexion = Conexion::conectarBD();
        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssssssii", $nombreUsuario, $contrasena, $nombres, $apellidos, $telefono, $email, $dni, $respuestaSecreta, $rolId, $usuarioId);
            $resultado = $stmt->execute();
            $stmt->close();
            Conexion::desConectarBD();
            return $resultado;
        } else {
            die("Error al preparar la consulta: " . $conexion->error);
        }
    }

    public function agregarUsuario($nombreUsuario, $contrasena, $nombres, $apellidos, $telefono, $email, $dni, $respuestaSecreta, $rolId)
    {
        // Consulta SQL con marcadores de posición para evitar inyección SQL
        $sql = "INSERT INTO usuario (
        NombreUsuario, 
        Contraseña, 
        Nombres, 
        Apellidos, 
        Email, 
        Telefono, 
        DNI, 
        RespuestaSecreta, 
        FechaCreacion, 
        EstadoUsuario, 
        RolID
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 1, ?)";

        // Establecer conexión a la base de datos
        $conexion = Conexion::conectarBD();

        // Preparar la consulta
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        // Asociar los parámetros a la consulta
        $stmt->bind_param(
            "ssssssssi",
            $nombreUsuario,
            $contrasena,
            $nombres,
            $apellidos,
            $email,
            $telefono,
            $dni,
            $respuestaSecreta,
            $rolId
        );

        // Ejecutar la consulta
        $resultado = $stmt->execute();

        // Cerrar la consulta y la conexión
        $stmt->close();
        Conexion::desConectarBD();

        // Retornar el resultado de la ejecución
        return $resultado; // Retorna true si la inserción fue exitosa, false si falló
    }

    public function obtenerUsuarioPorLogin($login)
    {
        $conexion = Conexion::conectarBD();
        $login = $conexion->real_escape_string($login);

        $sql = "SELECT NombreUsuario FROM usuario WHERE NombreUsuario = '$login'";
        $resultado = $conexion->query($sql);

        $usuario = null;
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
        }

        Conexion::desConectarBD();
        return $usuario;
    }

    public function validarRespuestaSeguridad($login, $respuesta)
    {
        $conexion = Conexion::conectarBD();
        $login = $conexion->real_escape_string($login);
        $respuesta = $conexion->real_escape_string($respuesta);

        $sql = "SELECT RespuestaSecreta FROM usuario WHERE NombreUsuario = '$login' AND RespuestaSecreta = '$respuesta'";
        $resultado = $conexion->query($sql);

        $esValido = $resultado->num_rows > 0;

        Conexion::desConectarBD();
        return $esValido;
    }

    public function actualizarContrasena($login, $nuevaContrasena)
    {
        $conexion = Conexion::conectarBD();
        $login = $conexion->real_escape_string($login);
        $nuevaContrasena = ($nuevaContrasena);

        $sql = "UPDATE usuario SET Contraseña = '$nuevaContrasena' WHERE NombreUsuario = '$login'";
        $resultado = $conexion->query($sql);

        $exito = $resultado === true;

        Conexion::desConectarBD();
        return $exito;
    }
}
