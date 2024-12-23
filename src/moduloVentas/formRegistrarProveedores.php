<?php
include_once(__DIR__ . "/../shared/pantalla.php");

class formRegistrarProveedores extends pantalla
{
    public function formRegistrarProveedoresShow()
    {
        $this->cabeceraShow('Registrar Proveedor');
?>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }

            h1 {
                text-align: center;
                color: #333;
            }

            form {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            form div {
                margin-bottom: 15px;
            }

            label {
                display: block;
                font-weight: bold;
                color: #555;
                margin-bottom: 5px;
            }

            input[type="text"],
            input[type="email"],
            input[type="date"],
            select {
                width: calc(100% - 20px);
                padding: 10px;
                margin-top: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
            }

            input[type="submit"],
            .regresar-boton {
                display: inline-block;
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border: none;
                border-radius: 5px;
                font-size: 14px;
                cursor: pointer;
            }

            input[type="submit"]:hover,
            .regresar-boton:hover {
                background-color: #0056b3;
            }

            input[type="submit"]:active,
            .regresar-boton:active {
                transform: scale(0.98);
            }

            .regresar-boton {
                background-color: #6c757d;
                margin-left: 10px;
            }

            .regresar-boton:hover {
                background-color: #5a6268;
            }
        </style>

        <a class="regresar-boton" href="../moduloVentas/indexProveedores.php">Regresar al panel principal</a>
        <h1 style="margin-bottom: 20px;">Registrar Proveedor</h1>
        <form action="../moduloVentas/getProveedor.php" method="POST">
            <div>
                <label for="numeroRUC">Número RUC:</label>
                <input type="text" id="numeroRUC" name="numeroRUC" minlength="11" maxlength="11">
            </div>
            <div>
                <label for="razonSocial">Razón Social:</label>
                <input type="text" id="razonSocial" name="razonSocial" minlength="3">
            </div>
            <div>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" maxlength="15">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div>
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">
            </div>
            <div>
                <label for="fechaRegistro">Fecha de Registro:</label>
                <input type="date" id="fechaRegistro" name="fechaRegistro" value="<?= date('Y-m-d') ?>">
            </div>
            <div>
                <label for="estadoProveedor">Estado Proveedor:</label>
                <select id="estadoProveedor" name="estadoProveedor">
                    <option value="1">Activo</option>
                </select>
            </div>
            <div>
                <input type="submit" value="Agregar Proveedor" name="btnAgregarProveedor">
                <a class="regresar-boton" href="./indexProveedores.php">cancelar</a>
            </div>
        </form>

<?php
        $this->pieShow();
    }
}
?>