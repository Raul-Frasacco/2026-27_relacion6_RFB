<?php
/*
function validaEntero(int &$var, int $min, int $max, int $defecto):bool Esta función
comprueba que $var contiene un entero cuyo valor está entre $min y $max. En $var se
devuelve el entero saneado (en caso de no cumplir las condiciones asigna a $var el valor
$defecto). La función devuelve true si es correcto y false en caso contrario.
*/
function validaEntero(int &$var, int $min, int $max, int $defecto):bool{
    if ($var < $min || $var > $max){
        $var = $defecto;
        return false;
    }
    return true;
}
 /*
 function validaReal(float &$var, float $min, float $max, float $defecto):bool Esta función
comprueba que $var contiene un real cuyo valor está entre $min y $max. En $var se devuelve
el real saneado (en caso de no cumplir las condiciones asigna a $var el valor $defecto). La
función devuelve true si es correcto y false en caso contrario.

 */
function validaReal(float &$var, float $min, float $max, float $defecto):bool {
    if ($var < $min || $var > $max){
        $var = $defecto;
        return false;
    }
    return true;
}

/*
function validaFecha(string &$var, string $defecto):bool Esta función comprueba que $var
contiene una fecha correcta en el formato dd/mm/aaaa. En $var se devuelve la fecha saneada
(2 cifras para dia y mes y cuatro para año- ej fecha 1/2/2023 es válida aunque se sanea y se
convierte en 01/02/2023). En caso de no cumplir las condiciones devuelve $defecto en
$var. La función devuelve true si es correcta y false en caso contrario.
*/
function validaFecha(string &$var, string $defecto):bool {
    $arrayFecha = explode("/" , $var);

    if (count($arrayFecha) !== 3) {
        $var = $defecto;
        return false;
    }

    $dia = sprintf("%02d" , (int)$arrayFecha[0]);
    $mes = sprintf("%02d" , (int)$arrayFecha[1]);
    $año = sprintf("%04d", (int)$arrayFecha[2]);

    if (!checkdate($mes, $dia, $año)){
        $var = $defecto;
        return false;
    }

    $var = "$dia/$mes/$año";
    return true;
}
/*
function validaHora(string &$var, string $defecto):bool Esta función comprueba que $var 
contiene una hora correcta en el formato hh:mm:ss . En $var se devuelve la hora saneada (2 
cifras para hora, min, segundos - Ej 0:5:1, hora valida aunque se sanea y se convierte a 
00:05:01). En caso de no cumplir las condiciones devuelve $defecto en $var. La función 
devuelve true si es correcta y false en caso contrario 
*/

function validaHora(string &$var, string $defecto):bool {
    $arrayHora = explode(":" , $var);

    if (count($arrayHora) !== 3) {
        $var = $defecto;
        return false;
    }

    $hora = sprintf("%02d" , (int)$arrayHora[0]);
    $min = sprintf("%02d" , (int)$arrayHora[1]);
    $seg = sprintf("%02d", (int)$arrayHora[2]);

    if ($hora > 23 || $hora < 0 || $min > 59 || $min < 0 ||$seg > 59 || $seg < 0){
        $var = $defecto;
        return false;
    }

    $var = "$hora:$min:$seg";
    return true;
}

/*
function validaEmail(string &$var, string $defecto):bool Esta función comprueba que $var 
contiene un email correcto en el formato aaaaa@bbbb.ccc. En $var se devuelve el email 
saneado (en caso de no cumplir las condiciones devuelve $defecto). La función devuelve 
true si es correcto y false en caso contrario. 
*/

function validaEmail(string &$var, string $defecto):bool{
    $correoSaneado = trim(mb_strtolower($var));
    $rex = "/^[a-z0-9]{5}[@][a-z0-9]{4}\.[a-z0-9]{3}$/";

    if(preg_match($rex, $correoSaneado) !== 1){
        $var = $defecto;
        return false;
    }

    $var = $correoSaneado;
    return true;
}

/*
function validaCadena(string &$var, int $longitud, string $defecto):bool Esta función 
comprueba que $var contiene una cadena de longitud máxima $longitud. En caso de no 
cumplir las condiciones se asigna a $var el valor $defecto. La función devuelve true si es 
correcto y false en caso contrario. 
*/

function validaCadena(string &$var, int $longitud, string $defecto):bool{
    if (mb_strlen($var) > $longitud){
        $var = $defecto;
        return false;
    }

    return true;
}

/*function validaExpresion(string &$var, string $expresion, string $defecto):bool Esta función
comprueba que $var cumple con la expresión regular $expresion. En caso de no cumplir las
condiciones se asigna a $var el valor por $defecto. La función devuelve true si es correcto y
false en caso contrario.*/

function validaExpresion(string &$var, string $expresion, string $defecto): bool {
    if (preg_match($expresion, $var)) {
        return true;
    }

    $var = $defecto;
    return false;
}

/*function validaRango(mixed $var, array $posibles, int $tipo=1):bool Esta función comprueba
que $var sea igual a uno de los elementos del array $posibles ($tipo=1) o a una de las claves
del array $posibles ($tipo=2). La función devuelve true si es correcta y false en caso contrario.*/

function validaRango(mixed $var, array $posibles, int $tipo = 1): bool {
    if ($tipo === 1) {
        return in_array($var, $posibles, true); 
    } elseif ($tipo === 2) {
        return array_key_exists($var, $posibles); 
    }

    return false; 
}
?>