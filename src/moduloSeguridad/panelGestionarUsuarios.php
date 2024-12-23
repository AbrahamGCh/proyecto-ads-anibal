<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class panelGestionarUsuarios extends pantalla
{
  public function panelGestionarUsuariosShow($usuarios = null)
  {
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
      header("Location: /");
      exit();
    }

    $this->cabeceraShow("Gestionar Usuarios");

    $rol = $_SESSION['rol'];
    $login = $_SESSION['login'];
?>
    <!-- Contenedor principal -->
    <div style="display: flex; height: 100vh;">
      <!-- Menú lateral -->
      <?php
      $this->menuShow($rol);
      ?>

      <!-- Contenido principal -->
      <main style="padding: 0 2rem;">
        <h1 style="color: #00695c;">Gestionar usuarios</h1>
        <div class="filters-container">
          <a href="/moduloSeguridad/indexAgregarUsuario.php" class="btn" style="margin-bottom: 1rem;">Agregar nuevo usuario</a>
        </div>

        <div class="table-cotizaciones">
          <table id="cotaizaciones-table">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Celular</th>
                <th>-</th>
                <th>-</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($usuarios !== null) : ?>

                <?php foreach ($usuarios as $usuario) : ?>
                  <tr>
                    <td><?= $usuario['NombreUsuario']; ?></td>
                    <td><?= $usuario['Contraseña']; ?></td>
                    <td><?= $usuario['Nombres']; ?></td>
                    <td><?= $usuario['Apellidos']; ?></td>
                    <td><?= $usuario['DNI']; ?></td>
                    <td><?= $usuario['Telefono']; ?></td>
                    <td>
                      <a href="/moduloSeguridad/indexEditarUsuario.php?<?= http_build_query([
                                                                          'usuarioid' => trim($usuario['UsuarioID']),
                                                                          'nombreusuario' => trim($usuario['NombreUsuario']),
                                                                          'contrasena' => trim($usuario['Contraseña']),
                                                                          'nombres' => trim($usuario['Nombres']),
                                                                          'apellidos' => trim($usuario['Apellidos']),
                                                                          'telefono' => trim($usuario['Telefono']),
                                                                          'email' => trim($usuario['Email']),
                                                                          'dni' => trim($usuario['DNI']),
                                                                          'respuestasecreta' => trim($usuario['RespuestaSecreta']),
                                                                          'rolid' => trim($usuario['RolID'])
                                                                        ]) ?>" style="color: blue; text-decoration: underline;">
                        ✏️ Editar
                      </a>
                    </td>
                    <td>
                      <form action="/moduloSeguridad/getConfirmarEliminar.php" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['UsuarioID']); ?>">
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">
                          🗑 Eliminar
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>

              <?php else : ?>
                <tr>
                  <td colspan="8">No se encontraron solicitudes.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </main>
    </div>
<?php
    $this->pieShow();
  }
}
