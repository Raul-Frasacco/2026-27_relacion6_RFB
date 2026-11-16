<?php
require_once dirname(__DIR__, 2) . '/cabecera.php';
require_once __DIR__ . '/../clases/curso2026/MuebleBase.php';
require_once __DIR__ . '/../clases/curso2026/Materiales.php';

inicioCabecera('Pruebas de clases');
cabecera();
finCabecera();
inicioCuerpo(
    'PRUEBAS DE LAS CLASES',
    [
        ['TEXTO' => 'Tienda', 'LINK' => '/aplicacion/principal/index.php'],
        ['TEXTO' => 'Pruebas', 'LINK' => ''],
    ]
);

// Demuestra constructores, setters/getters, propiedades adicionales y __toString.
$mueble = new MuebleTradicional('Mesa de pruebas');
$mueble->setFabricante('Taller local');
$mueble->setPais('ESPAÑA');
$mueble->setAnio(2022);
$mueble->setFechaIniVenta('1/2/2022');
$mueble->setFechaFinVenta('31/12/2040');
$mueble->setPrecio(125.5);
$mueble->setPeso(24.5);
$mueble->setSerie('T-01');
$mueble->anadirCaracteristicas('color', 'blanco', 'cajones', 2);

$reciclado = new MuebleReciclado('Silla recuperada', porcentajeReciclado: 75);
$reciclado->anadirCaracteristicas(
    'material', 'plástico',
    'exterior', true,
    'ULTIMA', 'fin'
);
$numeroDisponible = 0;
?>

<h2>Creación, getters, setters y conversión a cadena</h2>
<p><?= htmlspecialchars((string)$mueble, ENT_QUOTES, 'UTF-8') ?></p>
<p>
    Nombre: <?= htmlspecialchars($mueble->getNombre(), ENT_QUOTES, 'UTF-8') ?>
    · Precio: <?= $mueble->getPrecio() ?>
    · Peso: <?= $mueble->getPeso() ?>
    · Serie: <?= htmlspecialchars($mueble->getSerie(), ENT_QUOTES, 'UTF-8') ?>
</p>
<p>
    Propiedades adicionales tradicional:
    <?= htmlspecialchars(implode(', ', $mueble->damePropiedadesAdicionales()), ENT_QUOTES, 'UTF-8') ?>
</p>
<p><?= htmlspecialchars((string)$reciclado, ENT_QUOTES, 'UTF-8') ?></p>
<p>
    Propiedades adicionales reciclado:
    <?= htmlspecialchars(implode(', ', $reciclado->damePropiedadesAdicionales()), ENT_QUOTES, 'UTF-8') ?>
</p>

<!-- Demuestra el foreach inverso, el bloqueo ULTIMA y la modificación permitida. -->
<h2>Recorrido inverso de características</h2>
<ul>
    <?php foreach ($mueble->getCaracteristicas() as $clave => $valor): ?>
        <li>
            <?= htmlspecialchars((string)$clave, ENT_QUOTES, 'UTF-8') ?>:
            <?= htmlspecialchars(is_bool($valor) ? ($valor ? 'true' : 'false') : (string)$valor, ENT_QUOTES, 'UTF-8') ?>
        </li>
    <?php endforeach; ?>
</ul>

<h2>Bloqueo ULTIMA y modificación de una existente</h2>
<?php
$reciclado->getCaracteristicas()->material = 'madera recuperada';

try {
    $reciclado->getCaracteristicas()->nueva = 'no permitida';
    echo '<p class="error">El bloqueo ULTIMA no se ha aplicado.</p>';
} catch (Exception $e) {
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
?>
<p><?= htmlspecialchars((string)$reciclado->getCaracteristicas(), ENT_QUOTES, 'UTF-8') ?></p>

<!-- Demuestra puedeCrear y el rechazo de un valor fuera de rango. -->
<h2>Control de instancias</h2>
<p>
    ¿Se puede crear otro mueble?
    <?= MuebleBase::puedeCrear($numeroDisponible) ? 'Sí' : 'No' ?>
    (quedan <?= $numeroDisponible ?>).
</p>
<p>
    Precio rechazado por debajo del mínimo:
    <?= $mueble->setPrecio(10) ? 'aceptado' : 'rechazado' ?>;
    precio conservado: <?= $mueble->getPrecio() ?>.
</p>
<?php


?>

<p><a href="/aplicacion/principal/index.php">Volver a la tienda</a></p>

<?php
finCuerpo();

function cabecera()
{
    ?>
    <meta name="description" content="Demostración de las clases de muebles">
    <?php
}
