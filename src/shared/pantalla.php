<?php
class pantalla
{
  protected function cabeceraShow($titulo, $javasctiptUrl = "")
  {
?>
    <html>

    <head>

      <!-- Import javasctipUrl -->
      <?php
      if ($javasctiptUrl != "") {
        echo "<script src='$javasctiptUrl' defer></script>";
      }
      ?>
      <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.0/css/all.css">
      <link rel="stylesheet" href="/assets/styles.css">
      <title><?php echo $titulo; ?></title>
    </head>

    <body>
      <hr>
    <?php
  }
  protected function pieShow()
  {
    ?>
      <hr>

    <?php
  }

  protected function menuShow($rol)
  {
    ?>
      <aside style="width: 200px; background-color: #00695c; color: white; padding: 10px;">
        <h3 style="text-align: center; border-bottom: 2px solid white; padding-bottom: 10px;">Menú</h3>
        <nav>
          <ul style="list-style: none; padding: 0;">
            <?php
            // Menú para "cajero"
            if ($rol == "cajero") {
            ?>
              <li><a href="../moduloSeguridad/indexPanelPrincipal.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Menú</a></li>
              <li><a href="/moduloVentas/indexFactura.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Emitir Factura</a></li>
              <li><a href="/moduloVentas/indexBoleta.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Emitir Boleta</a></li>
              <li><a href="../moduloVentas/indexReporteventas.php " style="color: white; text-decoration: none; display: block; padding: 8px;">Reporte Ventas</a></li>
            <?php
            }
            // Menú para "vendedor"
            elseif ($rol == "vendedor") {
            ?>
              <li><a href="/moduloSeguridad/indexPanelPrincipal.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Menú</a></li>
              <li><a href="/moduloVentas/indexCotizacion.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Cotizacion</a></li>
              <li>
                <form method="post" action="../moduloVentas/getProducto.php" style="margin: 0;">
                  <input type="submit" name="btnProductos" value="Productos" style="color: white; text-decoration: none; display: block; padding: 8px; background-color: #00695c; border: none; cursor: pointer; font-size: 1em;">
                </form>
              </li>
              <li><a href="/moduloVentas/indexProveedores.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Proveedores</a></li>

            <?php

            }
            // Menú para "Jefe de Ventas"
            elseif ($rol === "jefeVentas") { // Verificar igualdad estricta
            ?>
              <li><a href="/moduloSeguridad/indexPanelPrincipal.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Menu</a></li>
              <li><a href="/moduloVentas/indexCotizacion.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Cotizacion</a></li>
              <li><a href="/moduloVentas/indexFactura.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Emitir Factura</a></li>
              <li><a href="/moduloVentas/indexBoleta.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Emitir Boleta</a></li>
              <li>
                <form method="post" action="../moduloVentas/getProducto.php" style="margin: 0;">
                  <input type="submit" name="btnProductos" value="Productos" style="color: white; text-decoration: none; display: block; padding: 8px; background-color: #00695c; border: none; cursor: pointer; font-size: 1em;">
                </form>
              </li>
              <li><a href="/moduloVentas/indexProveedores.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Proveedores</a></li>
              <li><a href="../moduloVentas/indexReporteventas.php " style="color: white; text-decoration: none; display: block; padding: 8px;">Reporte Ventas</a></li>
              <li><a href="/moduloSeguridad/indexGestionarUsuarios.php" style="color: white; text-decoration: none; display: block; padding: 8px;">Usuarios</a></li>
            <?php
            }

            // Menú si el rol no es reconocido
            else {
            ?>
              <li>
                <p style="color: yellow; text-align: center;">Rol no identificado</p>
              </li>
            <?php
            }
            ?>
            <li><a href='/moduloSeguridad/cerrarSesion.php' style="color: red; text-decoration: none; display: block; padding: 8px;">Cerrar Sesión</a></li>
          </ul>
        </nav>
      </aside>

  <?php
  }
}
  ?>