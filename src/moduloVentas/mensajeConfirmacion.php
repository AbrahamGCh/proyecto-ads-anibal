<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class mensajeConfirmacion extends pantalla
{
    public function mensajeConfirmacionShow($idProducto)
    {
        if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
            header("Location: /");
            exit();
        }

        $this->cabeceraShow("Gestionar Productos");

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
            <main style="display: flex; justify-content: center; align-items: center; flex: 1;">
                <form method="post" action="../moduloVentas/getProducto.php">
                    <div id="confirmationModal" class="modal">
                        <div class="modal-content">
                            <input type="hidden" name="idProducto" value="<?php echo $idProducto; ?>">
                            <h3>Mensaje de confirmación</h3>
                            <div class="warning-icon">⚠️</div>
                            <h3>¿Estás seguro de borrar este producto?</h3>
                            <div class="buttons">
                                <a href="indexGestionarProductos.php" class="btnMensaje btn-no">No</a>
                                <button type="submit" name="btnConfirmarEliminar" class="btnMensaje btn-yes">Sí</button>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
<?php
        $this->pieShow();
    }
}
?>