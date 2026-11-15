<?php 

error_reporting(0); 

date_default_timezone_set("Europe/Berlin");

define("RUTABASE", dirname(__FILE__));
//define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO","desarrollo"); //en "produccion o en desarrollo

if (MODO_TRABAJO=="produccion")
    error_reporting(0);
    else 
        error_reporting(E_ALL);  

spl_autoload_register(function ($clase){
    $rutas = [
        RUTABASE . "/aplicacion/clases/curso2026/$clase.php",
        RUTABASE . "/scripts/clases/$clase.php",
    ];
    foreach ($rutas as $fichero) {
        if (file_exists($fichero)) {
            require_once($fichero);
            return;
        }
    }
});

include(RUTABASE."/aplicacion/plantilla/plantilla.php");

//creo todos los objetos que necesita mi aplicación
