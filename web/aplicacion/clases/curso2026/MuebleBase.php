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
          if ($fabricante == ""){
            $this->fabricante = "FuMa:";
            return false;
          }

          if (str_starts_with($fabricante, "FuMa:")){
            $this->fabricante = mb_substr($fabricante, 0, 30, 'UTF-8');
          }else{
            $this->fabricante = mb_substr(("FuMa:" . $fabricante), 0, 30, 'UTF-8');
          }

          return true;
    }

    public function setPais(string $pais) : bool {
          if (validaCadena($pais,20,"") == false || $pais == ""){
            $this->pais = "ESPAÑA";
            return false;
         }
            $this->pais = $pais;
            return true;
    }

    public function setAnio(?int $anio): bool {
        if ($anio === null || $anio < 2020 || $anio > (int)date('Y')){
            $this->anio = 2020;
            return false;
        }

        $this->anio = $anio;
        return true;
    }

    public function setFechaIniVenta(string $fechaIniVenta): bool{
        if (!validaFecha($fechaIniVenta, "01/01/2020") || $fechaIniVenta == ""){
            $this->fechaIniVenta = "01/01/" .$this->anio;
            return false;
        }

         $arrayFecha = explode("/" , $fechaIniVenta);

        $dia =(int)$arrayFecha[0];
        $mes =(int)$arrayFecha[1];
        $año =(int)$arrayFecha[2];

        if (!checkdate($mes, $dia, $año)){
            $this->fechaIniVenta = "01/01/".$this->anio;
            return false;
        }

        if ($año < $this->anio){
            $this->fechaIniVenta = "01/01/" . $this->anio;
            return false;
        }

        $this->fechaIniVenta = $fechaIniVenta;
        return true;
    }
}