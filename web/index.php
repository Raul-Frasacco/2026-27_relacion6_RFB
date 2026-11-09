<?php
include_once(dirname(__FILE__) . "/cabecera.php");
//controlador


//dibuja la plantilla de la vista
inicioCabecera("Tienda");
cabecera();
finCabecera();
inicioCuerpo("TIENDA");
cuerpo();  //llamo a la vista
finCuerpo();
// **********************************************************

//vista
function cabecera() 
{
    ?>
    <!-- esto va en el head -->
     <?php   


}

//vista
function cuerpo()
{
?>
    <br><br>
   <a href="./aplicacion/pruebas/index.php">Acceso a pruebas</a>
   <br><br>
<?php
}
