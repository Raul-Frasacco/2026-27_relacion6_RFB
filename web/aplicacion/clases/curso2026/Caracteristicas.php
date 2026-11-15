<?php

class Caracteristicas implements Iterator 
{
    private array $_caract = [];
    private bool $_bloqueaNuevas = false;
    private array $_clavesIteracion = [];
    private int $_posicion = 0;

    public function __set(string $clave, mixed $valor): void
    {
        $esNueva = !array_key_exists($clave, $this->_caract);
        if ($esNueva && $this->_bloqueaNuevas) {
            throw new Exception("No se pueden añadir características nuevas después de ULTIMA.");
        }
        $this->_caract[$clave] = $valor;
        if (strtoupper($clave) === 'ULTIMA') {
            $this->_bloqueaNuevas = true;
        }
    }

    public function __get(string $clave): mixed
    {
        if (!array_key_exists($clave, $this->_caract)) {
            throw new Exception("La característica $clave no existe.");
        }
        return $this->_caract[$clave];
    }

    public function __isset(string $clave): bool
    {
        return isset($this->_caract[$clave]);
    }

    public function __unset(string $clave): void
    {
        unset($this->_caract[$clave]);
    }

    public function rewind(): void
    {
        $this->_clavesIteracion = array_reverse(array_keys($this->_caract));
        $this->_posicion = 0;
    }

    public function current(): mixed
    {
        $clave = $this->key();
        return $clave === null ? false : $this->_caract[$clave];
    }

    public function key(): mixed
    {
        return $this->_clavesIteracion[$this->_posicion] ?? null;
    }

    public function next(): void
    {
        $this->_posicion++;
    }

    public function valid(): bool
    {
        return isset($this->_clavesIteracion[$this->_posicion]);
    }

    public function __toString(): string
    {
        $partes = [];
        foreach ($this->_caract as $clave => $valor) {
            if (is_bool($valor)) {
                $valor = $valor ? 'true' : 'false';
            } elseif (is_array($valor) || is_object($valor)) {
                $valor = json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
            }
            $partes[] = $clave . ':' . (string)$valor;
        }
        return implode(';', $partes);
    }
}
