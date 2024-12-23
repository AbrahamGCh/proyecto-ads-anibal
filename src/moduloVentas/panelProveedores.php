<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/shared/pantalla.php'); // Clase base vista

class panelProveedores extends pantalla
{
    public function panelProveedoresShow($proveedores = null)
    {
        // Validación de sesión
        if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
            header("Location: /");
            exit();
        }

        // Cabecera
        $this->cabeceraShow("Gestión de Proveedores");

        // Obtener el rol del usuario
        $rol = $_SESSION['rol'];
?>
        <!-- Estilos para la tabla -->
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: #fff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            }

            th {
                background-color: #28a745;
                color: white;
                padding: 15px;
                text-align: center;
                font-size: 16px;
                text-transform: uppercase;
                border-bottom: 3px solid #218838;
            }

            td {
                padding: 12px 15px;
                text-align: center;
                color: #333;
                font-size: 14px;
                border-bottom: 1px solid #ddd;
            }

            tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            tr:hover {
                background-color: #d4edda;
                transform: scale(1.01);
                transition: all 0.3s ease-in-out;
            }

            /* Botones dentro de la tabla */
            a[href*="Editar"],
            button {
                display: inline-block;
                padding: 8px 15px;
                font-size: 14px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            a[href*="Editar"] {
                background-color: #007bff;
                color: white;
            }

            a[href*="Editar"]:hover {
                background-color: #0056b3;
            }

            button {
                background-color: #dc3545;
                color: white;
                border: none;
            }

            button:hover {
                background-color: #c82333;
            }

            .link-agregar {
                color: #28a745;
                /* Color verde para el texto */
                font-weight: bold;
                /* Resaltar el texto */
                text-decoration: none;
                /* Quitar el subrayado */
                border-bottom: 2px solid transparent;
                /* Efecto de borde al pasar el mouse */
                font-size: 16px;
                /* Tamaño de texto */
                transition: all 0.3s ease-in-out;
                /* Suavizar las transiciones */
            }

            .link-agregar:hover {
                color: #218838;
                /* Cambiar a un tono más oscuro de verde */
                border-bottom: 2px solid #218838;
                /* Aparece el borde inferior al pasar el mouse */
            }
        </style>

        <!-- Contenedor principal -->
        <div style="display: flex; height: 100vh;">
            <!-- Menú lateral -->
            <?php
            $this->menuShow($rol); // Mostrar menú lateral basado en el rol
            ?>

            <!-- Contenido principal -->
            <main style="padding: 20px; flex: 1;">
                <h1 style="text-align: center; color: #333;">Gestión de Proveedores</h1>

                <!-- Botón regresar al panel principal -->
                <a href="./indexAgregarProveedor.php" class="link-agregar" style="margin-left: 10px;">Agregar</a>

                <!-- Contenedor principal -->
                <div class="container">
                    <div class="scrollable">
                        <table id="proveedoresTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Número RUC</th>
                                    <th>Razón Social</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Dirección</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($proveedores !== null) : ?>
                                    <?php foreach ($proveedores as $proveedor) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($proveedor['ProveedorID']) ?></td>
                                            <td><?= htmlspecialchars($proveedor['NumeroRUC']) ?></td>
                                            <td><?= htmlspecialchars($proveedor['RazonSocial']) ?></td>
                                            <td><?= htmlspecialchars($proveedor['Telefono']) ?></td>
                                            <td><?= htmlspecialchars($proveedor['Email']) ?></td>
                                            <td><?= htmlspecialchars($proveedor['Direccion']) ?></td>
                                            <td>
                                                <form action="/moduloVentas/getProveedor.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <input type="hidden" name="idProveedor" value="<?= htmlspecialchars($proveedor['ProveedorID']) ?>">
                                                    <button type="submit">
                                                        🗑 Eliminar
                                                    </button>
                                                </form>
                                                <a href="indexEditarProveedores.php?id=<?= htmlspecialchars($proveedor['ProveedorID']) ?>&numeroRUC=<?= htmlspecialchars($proveedor['NumeroRUC']) ?>&razonSocial=<?= urlencode($proveedor['RazonSocial']) ?>&telefono=<?= htmlspecialchars($proveedor['Telefono']) ?>&email=<?= htmlspecialchars($proveedor['Email']) ?>&direccion=<?= urlencode($proveedor['Direccion']) ?>">Editar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center;">No se encontraron proveedores.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
<?php
        // Pie de página
        $this->pieShow();
    }
}
?>