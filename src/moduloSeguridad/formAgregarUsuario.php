<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class formAgregarUsuario extends pantalla
{
  public function formAgregarUsuarioShow()
  {
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
      header("Location: /");
      exit();
    }

    $this->cabeceraShow("Usuario", "/assets/emitirCotizacion.js");

    $rol = $_SESSION['rol'];
    $login = $_SESSION['login'];
?>
    <!-- Contenedor principal -->
    <div style="display: flex;">
      <!-- Menú lateral -->
      <?php
      $this->menuShow($rol);
      ?>

      <main style="padding: 4rem 2rem; width: 50%; margin: auto;">
        <h1>Agregar Usuario</h1>
        <form action="/moduloSeguridad/getGestionarUsuario.php" method="POST" id="usuario-form">
          <input type="hidden" name="accion" value="agregar">

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="usuario">Nombre de usuario:</label>
            <input type="text" id="usuario" name="nombreusuario" placeholder="Ingrese el nombre de usuario" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Más de 5 caracteres</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="contrasena">Contraseña:</label>
            <input type="text" id="contrasena" name="contrasena" placeholder="Ingrese la contraseña" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Obligatorio 8 caracteres</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" placeholder="Ingrese los nombres" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Más de 2 caracteres</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese los apellidos" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Más de 4 caracteres</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ingrese el teléfono" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Obligatoriamente 9 dígitos</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="email" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Debe tener formato de correo electrónico</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" placeholder="Ingrese el DNI" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Obligatoriamente 8 dígitos</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label for="respuesta-secreta">Respuesta secreta:</label>
            <input type="text" id="respuesta-secreta" name="respuestasecreta" placeholder="Ingrese la respuesta secreta" style="display: block; width: 100%; padding: 0.5rem;">
            <small style="color: #007BFF;">Más de 2 caracteres</small>
          </div>

          <div class="input-container" style="margin-bottom: 1rem;">
            <label>Rol:</label>
            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem;">
              <label>
                <input type="radio" name="rolid" value="1"> Jefe de Ventas
              </label>
              <label>
                <input type="radio" name="rolid" value="2"> Vendedor
              </label>
              <label>
                <input type="radio" name="rolid" value="3"> Cajero
              </label>
            </div>
          </div>

          <button type="submit" name="btnAgregarUsuario" style="width: 100%; font-size: 1.2rem; margin-top: 1rem; background-color: #28a745; color: white; border: none; padding: 0.75rem; border-radius: 4px;">Agregar Usuario</button>
        </form>
      </main>


    </div>
<?php
    $this->pieShow();
  }
}
