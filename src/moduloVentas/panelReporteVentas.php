<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class panelReporteVentas extends pantalla
{
    public function panelReporteVentasShow($datosReporte)
    {
        // Validación de sesión
        if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
            header("Location: /");
            exit();
        }

        // Cabecera
        $this->cabeceraShow("Reporte de Ventas");

        // Obtener el rol del usuario
        $rol = $_SESSION['rol'];
?>
        <!-- Contenedor principal -->
        <div style="display: flex; height: 100vh;">
            <!-- Menú lateral -->
            <?php
            $this->menuShow($rol); // Mostrar menú lateral basado en el rol
            ?>

            <!-- Contenido principal -->
            <main style="padding: 20px; flex: 1;">
                <h1 style="text-align: center; color: #00695c;">Reporte de Ventas</h1>

                <form method="POST" action="getReporteVenta.php" style="margin-bottom: 20px;">
                    <label for="desde">Desde:</label>
                    <input type="date" name="desde" id="desde" value="<?php echo isset($_POST['desde']) ? $_POST['desde'] : ''; ?>" style="margin-right: 10px; padding: 5px; border-radius: 5px;">

                    <label for="hasta">Hasta:</label>
                    <input type="date" name="hasta" id="hasta" value="<?php echo isset($_POST['hasta']) ? $_POST['hasta'] : ''; ?>" style="margin-right: 10px; padding: 5px; border-radius: 5px;">

                    <button type="submit" name="btnBuscar" style="background-color: #00695c; color: white; border: none; padding: 8px 15px; border-radius: 5px; font-weight: bold; cursor: pointer;">Buscar</button>
                    <button type="submit" name="btnGenerarReporte" style="background-color: #004d40; color: white; border: none; padding: 8px 15px; border-radius: 5px; font-weight: bold; cursor: pointer;">Generar reporte</button>
                </form>

                <!-- Tabla -->
                <table style="margin: auto; width: 90%; border-collapse: collapse; box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2); background-color: #fff;">
                    <thead>
                        <tr>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Serie y Correlativo</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Tipo</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Cliente</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Obra</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Orden de Compra</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Fecha Emisión</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Importe Total</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Costo Total</th>
                            <th style="background-color: #00695c; color: white; padding: 10px;">Ganancia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($datosReporte)) {
                            foreach ($datosReporte as $reporte) {
                                echo "<tr style='text-align: center;'>
                                        <td>{$reporte['NumeroSerieYCorrelativo']}</td>
                                        <td>{$reporte['TipoComprobante']}</td>
                                        <td>{$reporte['NombreCompletoORazonSocial']}</td>
                                        <td>{$reporte['Obra']}</td>
                                        <td>{$reporte['OrdenDeCompra']}</td>
                                        <td>{$reporte['FechaEmision']}</td>
                                        <td>{$reporte['ImporteTotal']}</td>
                                        <td>{$reporte['CostoTotal']}</td>
                                        <td>{$reporte['Ganancia']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align: center;'>No hay datos disponibles</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </main>
        </div>
<?php
        // Pie de página
        $this->pieShow();
    }
}
?>