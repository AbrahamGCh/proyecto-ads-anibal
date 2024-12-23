<?php
include_once("conexion.php");

class EreporteVentas
{
    public function obtenerReportePorFechas($desde, $hasta)
    {
        $conexion = Conexion::conectarBD();

        $sql = "
        SELECT
            concat(sc.numeroserie, '-', LPAD(fe.numerocorrelativo, 10, '0')) as NumeroSerieYCorrelativo,
            'Factura' AS TipoComprobante, 
            c.NombreCompletoORazonSocial,
            fe.Obra,
            fe.OrdenDeCompra,
            fe.FechaEmision,
            fe.ImporteTotal,
            fe.CostoTotal,
            fe.Ganancia
        FROM facturaemitida fe
        JOIN seriecomprobante sc on fe.SerieComprobanteID = sc.SerieComprobanteID
        JOIN cliente c on fe.ClienteID = c.ClienteID
        WHERE fe.FechaEmision BETWEEN '$desde' AND '$hasta'

        UNION ALL

        SELECT
            concat(sc.numeroserie, '-', LPAD(be.numerocorrelativo, 10, '0')) as NumeroSerieYCorrelativo,
            'Boleta' AS TipoComprobante, 
            c.NombreCompletoORazonSocial,
            be.Obra,
            be.OrdenDeCompra,
            be.FechaEmision,
            be.ImporteTotal,
            be.CostoTotal,
            be.Ganancia
        FROM boletaemitida be
        JOIN seriecomprobante sc on be.SerieComprobanteID = sc.SerieComprobanteID
        JOIN cliente c on be.ClienteID = c.ClienteID
        WHERE be.FechaEmision BETWEEN '$desde' AND '$hasta'

        ORDER BY FechaEmision DESC;
        ";



        $result = $conexion->query($sql);

        $datos = [];
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }

        Conexion::desConectarBD();
        return $datos;
    }
}
