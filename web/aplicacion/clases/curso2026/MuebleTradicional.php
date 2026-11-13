<?php

final class MuebleTradicional extends MuebleBase{
    private float $peso = 15;
    private string $serie = "S01";

    
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA", int $anio = 2020, string $fechaIniVenta = "01/01/2020", string $fechaFinVenta = "31/12/2040", float $precio = 30, float $peso = 15, string $serie = "S01")
    {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $precio);
        $this->setPrecio($precio);
        $this->setSerie($serie);
    }

    public function setPeso(float $precio): bool{
        if (!validaReal($precio, 15,300,15)){
            $this->peso = 15;
            return false;
        }

        $this->precio = $precio;
        return true;
    }
    public function getPeso() : float{
        return $this->peso;
    }

    public function setSerie(string $serie): void{
        $this->serie = $serie;
    }
    public function getSerie() : string{
        return $this->serie;
    }

    public function damePropiedadesAdicionales(): array
    {
        return ["peso", "serie"];
    }

    public function __toString()
    {
        return parent::__toString() . ", el peso es de " . $this->getPeso() . "y la serie es " . $this->getSerie(); 
    }
}