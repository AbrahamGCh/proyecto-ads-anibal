<?php
include_once(__DIR__ . "/../shared/pantalla.php");

class formEditarProveedor extends pantalla
{
    public function formEditarProveedorShow($id, $numeroRUC, $razonSocial, $telefono, $email, $direccion)
    {
        $this->cabeceraShow('Editar Proveedor'); // Título de la página
?>
        <style>
            /* General */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f8f9fa;
            }

            h1 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
            }

            .regresar-boton {
                display: inline-block;
                margin: 20px;
                text-decoration: none;
                background-color: #007bff;
                color: white;
                padding: 8px 12px;
                border-radius: 5px;
                font-weight: bold;
            }

            .regresar-boton:hover {
                background-color: #0056b3;
            }

            /* Formulario */
            form {
                background-color: #fff;
                max-width: 500px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            label {
                display: block;
                margin-bottom: 8px;
                font-weight: bold;
                color: #555;
            }

            input[type="text"],
            input[type="number"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                font-size: 14px;
            }

            input[type="text"],
            input[type="number"],
            input[type="email"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                font-size: 14px;
            }

            input[type="text"]:focus,
            input[type="number"]:focus,
            input[type="email"]:focus {
                border-color: #28a745;
                outline: none;
            }


            input[type="submit"] {
                background-color: #28a745;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
            }

            input[type="submit"]:hover {
                background-color: #218838;
            }

            div {
                margin-bottom: 15px;
            }
        </style>

        <!-- Botón regresar al panel principal -->
        <a class="regresar-boton" href="/moduloSeguridad/indexPanelPrincipal.php">Regresar al panel principal</a>

        <h1 style="margin-bottom: 20px;">Editar Proveedor: <?= htmlspecialchars($razonSocial) ?></h1>

        <!-- Formulario de edición -->
        <form action="/moduloVentas/getProveedor.php" method="POST">
            <input type="hidden" name="idProveedor" value="<?= htmlspecialchars($id) ?>">

            <div>
                <label for="numeroRUC">Número RUC:</label>
                <input type="text" id="numeroRUC" name="numeroRUC" value="<?= htmlspecialchars($numeroRUC) ?>" >
            </div>
            <div>
                <label for="razonSocial">Razón Social:</label>
                <input type="text" id="razonSocial" name="razonSocial" value="<?= htmlspecialchars($razonSocial) ?>" >
            </div>
            <div>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono) ?>" >
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" >
            </div>
            <div>
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($direccion) ?>" >
            </div>

            <input type="submit" value="Editar Proveedor" name="btnEditarProveedor">
        </form>
<?php
        $this->pieShow(); // Pie de página
    }
}
?>