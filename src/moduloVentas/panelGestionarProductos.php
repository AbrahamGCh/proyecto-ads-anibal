<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class panelGestionarProductos extends pantalla
{
    public function panelGestionarProductosShow($productos = null)
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
            <main style="padding: 0 2rem;">
                <h1 style="color: #00695c;">Gestión de productos</h1>

                <form method="post" action="../moduloVentas/getProducto.php">
                    <button type="submit" name="btnAgregarProducto" class="btn agregar"><i class="fa-solid fa-plus" style="color: #FFF;"></i>Agregar producto</button>
                </form>

                <div class="table-cotizaciones">
                    <table id="productos-table">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Nombre</th>
                                <th>Medida</th>
                                <th>Venta</th>
                                <th>Compra</th>
                                <th>Proveedor</th>
                                <th colspan="2" class="centered-colspan">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($productos !== null) : ?>

                                <?php foreach ($productos as $producto) : ?>
                                    <tr>
                                        <td><?= $producto['ProductoID']; ?></td>
                                        <td><?= $producto['Descripcion']; ?></td>
                                        <td><?= $producto['UnidadMedida']; ?></td>
                                        <td><?= $producto['ValorUnitarioVenta']; ?></td>
                                        <td><?= $producto['ValorUnitarioCompra']; ?></td>
                                        <td><?= $producto['RazonSocial']; ?></td>
                                        <form method="post" action="../moduloVentas/getProducto.php">
                                            <input type="hidden" name="idProducto" value="<?= $producto['ProductoID']; ?>">
                                            <td>
                                                <button type="submit" name="btnEditarProducto" class="btn editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>Editar
                                                </button>
                                            </td>
                                            <td>
                                                <button type="submit" name="btnEliminarProducto" class="btn eliminar">
                                                    <i class="fa-solid fa-trash"></i>Eliminar
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>

                            <?php else : ?>
                                <tr>
                                    <td colspan="6">No se encontraron productos.</td>
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
?>