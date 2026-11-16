<?php
// Un único array indexado desde 1, incluido tanto en el listado como en el formulario.
if (!isset($muebles)) {
    $muebles = [
        // Cinco muebles de ambos tipos, cada uno con al menos dos características propias.
        1 => new MuebleReciclado('Mesa recuperada', 'EcoMadera', 'ESPAÑA', 2021, '01/03/2021', '31/12/2040', 145.50, Materiales::carton),
        2 => new MuebleTradicional('Silla nogal', 'Carpintería Norte', 'ESPAÑA', 2022, '15/02/2022', '31/12/2040', 89.90,  Materiales::madera, 18.5, 'N-22'),
        3 => new MuebleReciclado('Estantería urbana', 'ReMueble', 'PORTUGAL', 2023, '01/06/2023', '31/12/2040', 210, Materiales::cobre, 65),
        4 => new MuebleTradicional('Cómoda roble', 'FaMu:Roble Vivo', 'ESPAÑA', 2020, '01/01/2020', '31/12/2035', 320,Materiales::hierro, 42, 'R-04'),
        5 => new MuebleReciclado('Banco jardín', 'Verde Hogar', 'FRANCIA', 2024, '10/04/2024', '31/12/2040', 125, Materiales::carton, 95,),
    ];
    $muebles[1]->anadirCaracteristicas('material', 'madera reciclada', 'cajones', 2);
    $muebles[2]->anadirCaracteristicas('color', 'nogal', 'apilable', false);
    $muebles[3]->anadirCaracteristicas('altura', 180, 'montaje', 'sencillo');
    $muebles[4]->anadirCaracteristicas('cajones', 4, 'acabado', 'barniz natural');
    $muebles[5]->anadirCaracteristicas('uso', 'exterior', 'tratamiento', 'impermeable');
}
