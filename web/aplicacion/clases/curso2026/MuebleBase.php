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
        $numero = 0; 
        if($this->puedeCrear($numero)== false)
            throw new Exception("Ya se han creado el maximo de muebles posibles");
        
        else{

            if($this->setNombre($nombre)== true)
                throw new Error("El nombre no puede estar vacio ni tampoco puede tener mas de 40 caracteres");
            else
                $this->setNombre($nombre);

            $this->setFabricante($fabricante);
            $this->setPais($pais);
            $this->setAnio($anio);
            $this->setFechaIniVenta($fechaIniVenta);
            $this->setFechaFinVenta($fechaFinVenta);
            $this->setPrecio($precio);

            self::$mueblesCreados++;
        }  
    }
    //--------------Setter's-----------------//
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

    public function setFechaFinVenta(string $fechaFinVenta): bool{
        if (!validaFecha($fechaFinVenta, "31/12/2040") || $fechaFinVenta == ""){
            $this->fechaFinVenta = "31/12/2040";
            return false;
        }

        $arrayFechaIni = explode("/" , $this->fechaIniVenta);

        $diaIni =(int)$arrayFechaIni[0];
        $mesIni =(int)$arrayFechaIni[1];
        $añoIni =(int)$arrayFechaIni[2];

        $arrayFechaFin = explode("/" , $fechaFinVenta);

        $diaFin =(int)$arrayFechaFin[0];
        $mesFin =(int)$arrayFechaFin[1];
        $añoFin =(int)$arrayFechaFin[2];

        if (!checkdate($mesFin, $diaFin, $añoFin)){
            $this->fechaFinVenta = "31/12/2040";
            return false;
        }

        if ($añoFin < $añoIni){
            $this->fechaFinVenta = "31/12/2040";
            return false; 
        }

        if ($añoFin == $añoIni && $mesFin < $mesIni){
            $this->fechaFinVenta = "31/12/2040";
            return false; 
        }

        if ($añoFin == $añoIni && $mesFin == $mesIni && $diaFin < $diaIni){
            $this->fechaFinVenta = "31/12/2040";
            return false; 
        }

        $this->fechaFinVenta = $fechaFinVenta;
        return true;
    }

    public function setPrecio(float $precio) : bool{
        if($precio < 30){
            $this->precio = 30;
            return false;
        }

        $this->precio = $precio;
        return true;
    }

    //--------------Getter's-----------------//
    public function getNombre(): string {
    return $this->nombre;
    }

    public function getFabricante(): string {
        return $this->fabricante;
    }

    public function getPais(): string {
        return $this->pais;
    }

    public function getAnio(): int {
        return $this->anio;
    }

    public function getFechaIniVenta(): string {
        return $this->fechaIniVenta;
    }

    public function getFechaFinVenta(): string {
        return $this->fechaFinVenta;
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    //--------------Prohibir la sobrecarga dinamica-----------------//
    public function __set($name, $value)
    {
        throw new \Exception("No se permite la sobrecarga de propiedades dinamicas");
    }

    public function __get($name)
    {
        throw new \Exception("No se permite acceder a las propiedades dinamicas");
    }
    
    //--------------Metodo Propiedades Adiccionales-----------------//
    abstract public function damePropiedadesAdicionales(): array;

    public static function puedeCrear(int &$numero): bool{
        if(self::$mueblesCreados == self::MAXIMO_MUEBLES)
            return false;
        else{
            $numero = (self::MAXIMO_MUEBLES - self::$mueblesCreados);
            return true;
        }
    }

    public function __toString()
    {
        return "MUEBLE de clase " . get_class($this) . " con nombre " . 
                $this->getNombre() . ", fabricante " . $this->getFabricante() . 
                ", fabricado en " . $this->getPais() . " a partir del año " . $this->getAnio() . 
                ", vendido desde " . $this->getFechaIniVenta() . " hasta " . $this->getFechaFinVenta() . 
                ", precio " . $this->getPrecio();
    }
    
}