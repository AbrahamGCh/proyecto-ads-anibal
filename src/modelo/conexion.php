<?php
class Conexion
{
    private static $conexion;

    // Método estático para conectar
    public static function conectarBD()
    {
        $server = "db";
        $user = "dev";
        $pass = "123";
        $db = "adsrocas";

        self::$conexion = new mysqli($server, $user, $pass, $db);

        if (self::$conexion->connect_errno) {
            die("Error al conectar a la base de datos: " . self::$conexion->connect_error);
        }

        return self::$conexion;
    }

    // Método estático para desconectar
    public static function desConectarBD()
    {
        if (self::$conexion) {
            self::$conexion->close();
            self::$conexion = null;
        }
    }
}
?>
