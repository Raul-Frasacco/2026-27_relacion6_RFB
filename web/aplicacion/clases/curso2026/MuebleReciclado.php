<?php
final class MuebleReciclado extends MuebleBase
{
    // Clase final con porcentaje reciclado entre 0 y 100.
    private int $porcentajeReciclado = 10;

    public function __construct(string $nombre, string $fabricante = 'FaMu:', string $pais = 'ESPAÑA', int $anio = 2020,
        string $fechaIniVenta = '01/01/2020', string $fechaFinVenta = '31/12/2040', float $precio = 30,
        int $porcentajeReciclado = 10)
    {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $precio);
        if (!$this->setPorcentajeReciclado($porcentajeReciclado)) $this->porcentajeReciclado = 10;
    }

    public function setPorcentajeReciclado(int $porcentajeReciclado): bool
    {
        if (!validaEntero($porcentajeReciclado, 0, 100, 10)) return false;
        $this->porcentajeReciclado = $porcentajeReciclado;
        return true;
    }

    public function getPorcentajeReciclado(): int { return $this->porcentajeReciclado; }

    public function damePropiedadesAdicionales(): array { return ['PorcentajeReciclado']; }
}
