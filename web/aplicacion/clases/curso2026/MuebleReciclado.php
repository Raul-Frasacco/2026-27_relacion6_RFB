<?php

final class MuebleReciclado extends MuebleBase{
    private int $PorcentajeReciclado = 10;

    
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA", int $anio = 2020, string $fechaIniVenta = "01/01/2020", string $fechaFinVenta = "31/12/2040", float $precio = 30, int $PorcentajeReciclado = 10)
    {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $precio);
        $this->setPorcentajeReciclado($PorcentajeReciclado);
    }

    public function setPorcentajeReciclado(int $PorcentajeReciclado): bool{
        if (!validaEntero($PorcentajeReciclado, 0, 100, 10)){
            $this->PorcentajeReciclado = 10;
            return false;
        }

        $this->PorcentajeReciclado = $PorcentajeReciclado;
        return true;
    }

    public function getPorcentajeReciclado(): int{ 
        return $this->PorcentajeReciclado;
    }

    public function damePropiedadesAdicionales(): array
    {
        return ["PorcentajeReciclado"];
    }

    public function __toString()
    {
        return parent::__toString() . " y el porcentaje reciclado es -> " . $this->getPorcentajeReciclado();
    }
}