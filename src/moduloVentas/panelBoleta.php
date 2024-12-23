<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class panelBoleta extends pantalla
{
  public function panelBoletaShow($boletas = null)
  {
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
      header("Location: /");
      exit();
    }

    $this->cabeceraShow("Boletas");

    $rol = $_SESSION['rol'];
?>
    <div style="display: flex; height: 100vh;">
      <!-- Menú lateral -->
      <?php
      $this->menuShow($rol);
      ?>

      <main style="padding: 0 2rem;">
        <h1 style="color: #00695c;">Boletas</h1>

        <div class="filters-container">
          <a href="/moduloVentas/indexEmitirBoleta.php" class="btn" style="margin-bottom: 1rem;">Emitir Boleta</a>
          <div class="flex">
            <h2>Filtros de Búsqueda</h2>
          </div>
          <div class="filters">
            <!-- Primer filtro de nro de boleta, un input text -->
            <form action="/moduloVentas/getBoleta.php" method="POST">
              <div class="input-container">
                <label for="nroBoleta">Nro. Boleta:</label>
                <input type="text" id="nroBoleta" name="txtNroBoleta">
              </div>
              <input style="margin-top: 10px;" type="submit" name="btnBuscarNroBoleta" value="Buscar">
            </form>

            <!-- Segundo filtro de Fecha, dos inputs de "desde" "hasta" -->
            <form action="/moduloVentas/getBoleta.php" method="POST">
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
          <table id="boletas-table">
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
              <?php if ($boletas !== null) : ?>

                <?php foreach ($boletas as $boleta) : ?>
                  <tr>
                    <td><?= $boleta['NumeroSerie']; ?></td>
                    <td><?= $boleta['NumeroCorrelativo']; ?></td>
                    <td><?= $boleta['Cliente']; ?></td>
                    <td><?= $boleta['Obra']; ?></td>
                    <td><?= $boleta['FechaEmision']; ?></td>
                    <td><?= $boleta['ImporteTotal']; ?></td>
                    <td>
                      <form action="/moduloVentas/getBoleta.php" method="POST">
                        <input type="hidden" name="txtIDBoleta" value="<?= $boleta['BoletaEmitidaID']; ?>">
                        <input type="submit" name="btnGenerarPdf" value="Descargar PDF">
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>

              <?php else : ?>
                <tr>
                  <td colspan="7">No se encontraron boletas.</td>
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