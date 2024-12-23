<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Eliminación</title>
</head>
<body>
    <h2>¿Estás seguro de que deseas eliminar al usuario?</h2>

    <?php if ($usuario): ?>
        <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario['NombreUsuario']); ?></p>
        <p><strong>Nombres:</strong> <?= htmlspecialchars($usuario['Nombres']); ?></p>
        <p><strong>Apellidos:</strong> <?= htmlspecialchars($usuario['Apellidos']); ?></p>

        <form action="/moduloSeguridad/getGestionarUsuario.php" method="POST">
            <input type="hidden" name="accion" value="eliminar">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['UsuarioID']); ?>">
            <button type="submit" style="background-color: red; color: white;">Sí, eliminar</button>
            <a href="/moduloSeguridad/indexGestionarUsuarios.php" style="color: green;">Cancelar</a>
        </form>
    <?php else: ?>
        <p>Usuario no encontrado.</p>
        <a href="/moduloSeguridad/indexGestionarUsuarios.php">Regresar</a>
    <?php endif; ?>
</body>
</html>
