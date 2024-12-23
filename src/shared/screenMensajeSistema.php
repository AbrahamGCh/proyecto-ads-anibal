<?php
include_once("pantalla.php");
class screenMensajeSistema extends pantalla
{
    public function screenMensajeSistemaShow($msj, $descripcion, $link = "")
    {
        $this->cabeceraShow("Mensaje del Sistema");
        ?>
        <p align="center">
            <strong><?php echo ucwords($msj); ?></strong>
            <br>
            <?php echo $descripcion; ?>
            <br>
            <?php
            if ($link != "") {
                echo $link;
            }
            ?>
        </p>
        <?php
        $this->pieShow();
    }
}
?>