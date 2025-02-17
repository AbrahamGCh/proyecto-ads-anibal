<?php
// src/moduloSeguridad/preguntaSeguridad.php

include_once('../shared/pantalla.php');

class preguntaSeguridad extends Pantalla
{
    public function preguntaSeguridadShow($login, $pregunta)
    {
        $this->cabeceraShow("Pregunta de Seguridad");
        ?>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
            }

            .form-container {
                width: 100%;
                max-width: 400px;
                padding: 20px;
                background: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                border-radius: 10px;
            }

            .form-container h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #333;
            }

            .form-container input {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
            }

            .form-container button {
                width: 100%;
                padding: 10px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }

            .form-container button:hover {
                background-color: #0056b3;
            }
        </style>
        <div class="form-container">
            <h2>Validar Identidad</h2>
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($login); ?></p>
            <p><?php echo htmlspecialchars($pregunta); ?></p>
            <form method="POST" action="./controlRestablecerContrasena.php">
                <input type="text" name="respuesta" placeholder="Ingrese la respuesta">
                <input type="hidden" name="login" value="<?php echo htmlspecialchars($login); ?>">
                <button type="submit" name="btnValidarRespuesta">Validar Respuesta</button>
            </form>
        </div>
        <?php
        $this->pieShow();
    }
}
?>

