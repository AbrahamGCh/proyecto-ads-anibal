<?php
function validarBoton($boton)
{
    return isset($boton);    
}
function validarTexto($txtLogin,$txtPassword)
{
    if(strlen(strtolower(trim($txtLogin))) > 3 and strlen($txtPassword) >4)
        return 1;
    else
        return 0;    
}

$btnAceptar = $_POST['btnAceptar'] ?? null;

if(validarBoton($btnAceptar))
{
    if(validarTexto($_POST['txtLogin'],$_POST['txtPassword']))
    {
        $login= strtolower(trim($_POST['txtLogin']));
        $password = $_POST['txtPassword'];
        include_once('controlAutenticarUsuario.php');
        $obcontrol = new controlAutenticarUsuario();
        $obcontrol -> verificarUsuario($login,$password);
    }
    else
    {
        include_once('../shared/screenMensajeSistema.php'); 
        $objMensaje = new screenMensajeSistema();
        $objMensaje -> screenMensajeSistemaShow("Llenar campos correctamente","<a href='../index.php'>Inicio</a>");      
    }
}
else
{
   include_once('../shared/screenMensajeSistema.php'); 
   $objMensaje = new screenMensajeSistema();
   $objMensaje -> screenMensajeSistemaShow("Acceso no permitido","<a href='../index.php'>Inicio</a>");
}

?>

