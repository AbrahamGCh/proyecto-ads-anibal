<?php
include_once("../shared/pantalla.php");

class menuPrincipalSistema extends pantalla
{
  public function menuPrincipalSistemaShow()
  {
    // Validar si el usuario está autenticado
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
      header("Location: ../index.php");
      exit();
    }

    $this->cabeceraShow("Menú Principal del Sistema");

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
      <main style="flex: 1; padding: 20px; text-align: center;">
        <h1>Rol: <?= $rol ?></h1>
        <h1 style="color: #00695c;">Bienvenido, <?php echo htmlspecialchars($login); ?></h1>
        <img src="../assets/imagen/logo.png" alt="Logo" style="width: 600px; margin-top: 20px;">
      </main>
    </div>
<?php
    $this->pieShow();
  }
}
?>