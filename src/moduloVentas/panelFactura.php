<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class panelFactura extends pantalla
{
  public function panelFacturaShow($facturas = null)
  {
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
      header("Location: /");
      exit();
    }

    $this->cabeceraShow("Facturación");

    $rol = $_SESSION['rol'];
?>
    <div style="display: flex; height: 100vh;">
      <!-- Menú lateral -->
      <?php
      $this->menuShow($rol);
      ?>

      <main style="padding: 0 2rem;">
        <h1 style="color: #00695c;">Facturas</h1>

        <div class="filters-container">
          <a href="/moduloVentas/indexEmitirFactura.php" class="btn" style="margin-bottom: 1rem;">Emitir Factura</a>
          <div class="flex">
            <h2>Filtros de Búsqueda</h2>
          </div>
          <div class="filters">
            <!-- Primer filtro de nro de factura, un input text -->
            <form action="/moduloVentas/getFactura.php" method="POST">
              <div class="input-container">
                <label for="nroFactura">Nro. Factura:</label>
                <input type="text" id="nroFactura" name="txtNroFactura">
              </div>
              <input style="margin-top: 10px;" type="submit" name="btnBuscarNroFactura" value="Buscar">
            </form>

            <!-- Segundo filtro de Fecha, dos inputs de "desde" "hasta" -->
            <form action="/moduloVentas/getFactura.php" method="POST">
              <div class="input-container">
                <label for="fechaDesde">Fecha desde:</label>
                <input type="date" id="fechaDesde" name="txtFechaDesde">
                <input type="date" id="fechaHasta" name="txtFechaHasta">
              </div>
              <input style="margin-top: 10px;" type="submit" name="btnBuscarFechas" value="Buscar">
            </form>
          </div>
        </div>

        <div class="table-cotizaciones">
          <table id="facturas-table">
            <thead>
              <tr>
                <th>Serie</th>
                <th>Nro</th>
                <th>Cliente</th>
                <th>Obra</th>
                <th>Fecha Emisión</th>
                <th>Monto</th>
                <th>-</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($facturas !== null) : ?>

                <?php foreach ($facturas as $factura) : ?>
                  <tr>
                    <td><?= $factura['NumeroSerie']; ?></td>
                    <td><?= $factura['NumeroCorrelativo']; ?></td>
                    <td><?= $factura['Cliente']; ?></td>
                    <td><?= $factura['Obra']; ?></td>
                    <td><?= $factura['FechaEmision']; ?></td>
                    <td><?= $factura['ImporteTotal']; ?></td>
                    <td>
                      <form action="/moduloVentas/getFactura.php" method="POST">
                        <input type="hidden" name="txtIDFactura" value="<?= $factura['FacturaEmitidaID']; ?>">
                        <input type="submit" name="btnGenerarPdf" value="Descargar PDF">
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>

              <?php else : ?>
                <tr>
                  <td colspan="7">No se encontraron facturas.</td>
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
