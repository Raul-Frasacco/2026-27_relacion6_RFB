<?php
final class MuebleTradicional extends MuebleBase
{
    // Clase final con las propiedades Peso y Serie.
    private float $peso = 15;
    private string $serie = 'S01';

    public function __construct(string $nombre, string $fabricante = 'FaMu:', string $pais = 'ESPAÑA', int $anio = 2020,
        string $fechaIniVenta = '01/01/2020', string $fechaFinVenta = '31/12/2040', float $precio = 30,
        float $peso = 15, string $serie = 'S01')
    {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $precio);
        if (!$this->setPeso($peso)) $this->peso = 15;
        if (!$this->setSerie($serie)) $this->serie = 'S01';
    }

    public function setPeso(float $peso): bool
    {
        if (!is_finite($peso) || !validaReal($peso, 15, 300, 15)) return false;
        $this->peso = $peso;
        return true;
    }

    public function getPeso(): float { return $this->peso; }

    public function setSerie(string $serie): bool
    {
        $serie = trim($serie);
        if ($serie === '') return false;
        $this->serie = $serie;
        return true;
    }

    public function getSerie(): string { return $this->serie; }

    public function damePropiedadesAdicionales(): array { return ['Peso', 'Serie']; }
}
