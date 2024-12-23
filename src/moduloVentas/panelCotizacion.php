<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php');

class panelCotizacion extends pantalla
{
  public function panelCotizacionShow($cotizaciones = null)
  {
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
      header("Location: /");
      exit();
    }

    $this->cabeceraShow("Cotización");

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
        <h1 style="color: #00695c;">Cotizaciones</h1>
        <div class="filters-container">
          <a href="/moduloVentas/indexEmitirCotizacion.php" class="btn" style="margin-bottom: 1rem;">Emitir Cotización</a>
          <div class="flex">
            <h2>Filtros de Búsqueda</h2>
          </div>
          <div class="filters">
            <!-- Primer filtro de nro de cotización, un input text -->
            <form action="/moduloVentas/getCotizacion.php" method="POST">
              <div class="input-container">
                <label for="nroCotizacion">Nro. Cotización:</label>
                <input type="text" id="nroCotizacion" name="txtNroCotizacion">
              </div>
              <input style="margin-top: 10px;" type="submit" name="btnBuscarNrcotizacion" value="Buscar">
            </form>

            <!-- Segundo filtro de Fecha, dos inputs de "desde" "hasta" -->
            <form action="/moduloVentas/getCotizacion.php" method="POST">
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
          <table id="cotaizaciones-table">
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
              <?php if ($cotizaciones !== null) : ?>

                <?php foreach ($cotizaciones as $cotaizacion) : ?>
                  <tr>
                    <td><?= $cotaizacion['NumeroSerie']; ?></td>
                    <td><?= $cotaizacion['NumeroCorrelativo']; ?></td>
                    <td><?= $cotaizacion['Cliente']; ?></td>
                    <td><?= $cotaizacion['Obra']; ?></td>
                    <td><?= $cotaizacion['FechaEmision']; ?></td>
                    <td><?= $cotaizacion['ImporteTotal']; ?></td>
                    <td>
                      <form action="/moduloVentas/getCotizacion.php" method="POST">
                        <input type="hidden" name="txtIDCotizacion" value="<?= $cotaizacion['CotizacionEmitidaID']; ?>">
                        <input type="submit" name="btnGenerarPdf" value="Descargar PDF">
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>

              <?php else : ?>
                <tr>
                  <td colspan="7">No se encontraron solicitudes.</td>
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