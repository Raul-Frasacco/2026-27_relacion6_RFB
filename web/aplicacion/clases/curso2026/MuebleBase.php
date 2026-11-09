<?php
require_once(__DIR__ . '/../../../scripts/Librerias/validacion.php');

abstract class  MuebleBase{
    protected const MAXIMO_MUEBLES = 1;
    protected static int $mueblesCreados = 0;

    // Propiedades
    protected string $nombre;
    protected string $fabricante = "FaMu:";
    protected string $pais = "ESPAÑA";
    protected int $anio = 2020;
    protected string $fechaIniVenta = "01/01/2020";
    protected string $fechaFinVenta = "31/12/2040";
    protected float $precio = 30;

    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA",
                                int $anio = 2020, string $fechaIniVenta = "01/01/2020", string $fechaFinVenta = "31/12/2040",
                                float $precio = 30)
    {
       

        
    }

    public function setNombre(string $nombre) : bool {
          if (validaCadena($nombre,40,"") == false || $nombre == ""){
            return false;
         }
            $this->$nombre = mb_strtoupper($nombre);
            return true;
    }

    public function setFabricante(string $fabricante) : bool {
          
    }
}