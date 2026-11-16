<?php
require_once(__DIR__ . '/../../../scripts/Librerias/validacion.php');

abstract class MuebleBase
{
    // Límite total de instancias y contador privado compartido por la clase.
    protected const MAXIMO_MUEBLES = 10;
    private static int $mueblesCreados = 0;

    protected string $nombre;
    protected string $fabricante = 'FaMu:';
    protected string $pais = 'ESPAÑA';
    protected int $anio = 2020;
    protected string $fechaIniVenta = '01/01/2020';
    protected string $fechaFinVenta = '31/12/2040';
    protected float $precio = 30;
    private Caracteristicas $caracteristicas;

    public function __construct(string $nombre, string $fabricante = 'FaMu:', string $pais = 'ESPAÑA',
        int $anio = 2020, string $fechaIniVenta = '01/01/2020', string $fechaFinVenta = '31/12/2040', float $precio = 30)
    {
        // Comprueba el máximo, exige nombre y asigna el resto usando sus setters.
        $numero = 0;
        if (!self::puedeCrear($numero)) {
            throw new Exception('Ya se ha alcanzado el máximo de muebles permitidos.');
        }
        if (!$this->setNombre($nombre)) {
            throw new Error('El nombre es obligatorio y no puede superar 40 caracteres.');
        }
        $this->caracteristicas = new Caracteristicas();

        if (!$this->setFabricante($fabricante)) $this->fabricante = 'FaMu:';
        if (!$this->setPais($pais)) $this->pais = 'ESPAÑA';
        if (!$this->setAnio($anio)) $this->anio = 2020;
        if (!$this->setFechaIniVenta($fechaIniVenta)) $this->fechaIniVenta = '01/01/' . $this->anio;
        if (!$this->setFechaFinVenta($fechaFinVenta)) $this->fechaFinVenta = '31/12/2040';
        if (!$this->setPrecio($precio)) $this->precio = 30;
        self::$mueblesCreados++;
    }

    // Setters/getters con validación de las propiedades comunes.
    public function setNombre(string $nombre): bool
    {
        $nombre = trim($nombre);
        if ($nombre === '' || !validaCadena($nombre, 40, '')) return false;
        $this->nombre = mb_strtoupper($nombre, 'UTF-8');
        return true;
    }

    public function setFabricante(string $fabricante): bool
    {
        $fabricante = trim($fabricante);
        if ($fabricante === '') return false;
        if (!str_starts_with($fabricante, 'FaMu:')) $fabricante = 'FaMu:' . $fabricante;
        $this->fabricante = mb_substr($fabricante, 0, 30, 'UTF-8');
        return true;
    }

    public function setPais(string $pais): bool
    {
        $pais = trim($pais);
        if ($pais === '' || !validaCadena($pais, 20, '')) return false;
        $this->pais = $pais;
        return true;
    }

    public function setAnio(int $anio): bool
    {
        if ($anio < 2020 || $anio > (int)date('Y')) return false;
        $this->anio = $anio;
        return true;
    }

    public function setFechaIniVenta(string $fechaIniVenta): bool
    {
        if (!validaFecha($fechaIniVenta, '')) return false;
        [$dia, $mes, $anio] = array_map('intval', explode('/', $fechaIniVenta));
        if ($anio < $this->anio || ($anio === $this->anio && ($mes < 1 || ($mes === 1 && $dia < 1)))) return false;
        $this->fechaIniVenta = $fechaIniVenta;
        return true;
    }

    public function setFechaFinVenta(string $fechaFinVenta): bool
    {
        if (!validaFecha($fechaFinVenta, '')) return false;
        [$d, $m, $a] = array_map('intval', explode('/', $fechaFinVenta));
        [$di, $mi, $ai] = array_map('intval', explode('/', $this->fechaIniVenta));
        if ([$a, $m, $d] < [$ai, $mi, $di]) return false;
        $this->fechaFinVenta = $fechaFinVenta;
        return true;
    }

    public function setPrecio(float $precio): bool
    {
        if (!is_finite($precio) || $precio < 30) return false;
        $this->precio = $precio;
        return true;
    }

    public function getNombre(): string { return $this->nombre; }
    public function getFabricante(): string { return $this->fabricante; }
    public function getPais(): string { return $this->pais; }
    public function getAnio(): int { return $this->anio; }
    public function getFechaIniVenta(): string { return $this->fechaIniVenta; }
    public function getFechaFinVenta(): string { return $this->fechaFinVenta; }
    public function getPrecio(): float { return $this->precio; }

    public function __set($name, $value): void
    {
        throw new Exception('No se permite la sobrecarga dinámica de propiedades.');
    }

    public function __get($name): mixed
    {
        throw new Exception('No se permite acceder a propiedades dinámicas.');
    }

    abstract public function damePropiedadesAdicionales(): array;

    // Incorpora pares nombre/valor; un parámetro impar final se ignora.
    public function anadirCaracteristicas(mixed ...$argumentos): void
    {
        $numero = count($argumentos);
        for ($i = 0; $i + 1 < $numero; $i += 2) {
            if (!is_string($argumentos[$i]) || $argumentos[$i] === '') {
                throw new InvalidArgumentException('El nombre de la característica debe ser una cadena no vacía.');
            }
            $this->caracteristicas->{$argumentos[$i]} = $argumentos[$i + 1];
        }
    }

    public function getCaracteristicas(): Caracteristicas { return $this->caracteristicas; }

    // Indica si queda capacidad y devuelve cuántos muebles caben.
    public static function puedeCrear(int &$numero): bool
    {
        $numero = max(0, self::MAXIMO_MUEBLES - self::$mueblesCreados);
        return $numero > 0;
    }

    public function __toString(): string
    {
        // Genera la descripción común, las adicionales y las características.
        return 'MUEBLE de clase ' . get_class($this) . ' con nombre ' . $this->nombre . ', fabricante ' .
            $this->fabricante . ', fabricado en ' . $this->pais . ' a partir del año ' . $this->anio .
            ', vendido desde ' . $this->fechaIniVenta . ' hasta ' . $this->fechaFinVenta . ', precio ' .
            $this->precio . $this->textoPropiedadesAdicionales() .
            ($this->caracteristicas->__toString() !== '' ? ', ' . $this->caracteristicas : '');
    }

    protected function textoPropiedadesAdicionales(): string
    {
        $partes = [];
        foreach ($this->damePropiedadesAdicionales() as $propiedad) {
            $metodo = 'get' . $propiedad;
            $partes[] = ', ' . $propiedad . ' ' . $this->$metodo();
        }
        return implode('', $partes);
    }
}
