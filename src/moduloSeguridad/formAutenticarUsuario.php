<?php
include_once(__DIR__ . "/../shared/pantalla.php");
class formAutenticarUsuario extends Pantalla
{
  public function formAutenticarUsuarioShow()
  {
    $this->cabeceraShow("Autenticación de usuario");
?>
    <style>
      form {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
      }

      input[type="text"],
      input[type="password"] {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
      }

      input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }

      input[type="submit"]:hover {
        background-color: #0056b3;
      }
    </style>

    <form name="autenticarUsuario" method="POST" action="./moduloSeguridad/getUsuario.php">
      <table align="center" border="0">
        <tr>
          <td colspan="2" align="left">EDIFICANDO SOBRE LA ROCA E.I.R.L</td>
        </tr>
        <tr>
          <td rowspan="3" align="left">
            <img width="80" height="50" src="../assets/imagen/candado.png" alt="Descripción">
          </td>
        </tr>
        <tr>
          <td>Login:</td>
          <td><input name="txtLogin" id="txtLogin" type="text" /></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input name="txtPassword" id="txtPassword" type="password" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="submit" name="btnAceptar" value="Ingresar" />
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <a href="../moduloSeguridad/controlRestablecerContrasena.php">Restablecer contraseña</a>
          </td>
        </tr>
      </table>
    </form>


<?php
    $this->pieShow();
  }
}
?>