<?php
require_once dirname(__DIR__, 2) . '/cabecera.php';
require_once __DIR__ . '/../clases/curso2026/MuebleBase.php';
require_once __DIR__ . '/datos.php';

$seleccion = filter_input(INPUT_POST, 'mueble', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';
inicioCabecera('Tienda - Muebles');
cabecera();
finCabecera();
inicioCuerpo('TIENDA', [['TEXTO' => 'Tienda', 'LINK' => '/aplicacion/principal/index.php'], ['TEXTO' => 'Muebles', 'LINK' => '']]);
?>
<!-- Tabla del catálogo con índice, tipo, nombre y más propiedades. -->
<h2>Catálogo de muebles</h2>
<table class="tabla">
    <thead><tr><th>Índice</th><th>Tipo</th><th>Nombre</th><th>Fabricante</th><th>País</th><th>Precio</th><th>Material</th></tr></thead>
    <tbody>
    <?php foreach ($muebles as $indice => $mueble): ?>
        <tr class="<?= $indice % 2 === 0 ? 'par' : 'impar' ?>">
            <td><?= (int)$indice ?></td><td><?= htmlspecialchars((new ReflectionClass($mueble))->getShortName(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($mueble->getNombre(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($mueble->getFabricante(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($mueble->getPais(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= number_format($mueble->getPrecio(), 2, ',', '.') ?> €</td>
            <td><?= htmlspecialchars($mueble->getMaterialDescripcion(), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Un formulario con selector y botones para mostrar o abrir la modificación. -->
<h2>Consultar o modificar un mueble</h2>
<form method="post" action="/aplicacion/principal/index.php">
    <label for="mueble">Mueble:</label>
    <select id="mueble" name="mueble" required>
        <?php foreach ($muebles as $indice => $mueble): ?>
            <option value="<?= (int)$indice ?>" <?= $seleccion === (int)$indice ? 'selected' : '' ?>>
                <?= (int)$indice ?> - <?= htmlspecialchars($mueble->getNombre(), ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((new ReflectionClass($mueble))->getShortName(), ENT_QUOTES, 'UTF-8') ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <button class="boton" type="submit" name="accion" value="mostrar">Mostrar Mueble</button>
    <button class="boton" type="submit" name="accion" value="modificar" formaction="/aplicacion/principal/modificar.php" formtarget="_blank">Modificar Mueble</button>
</form>

<?php // Muestra cada propiedad mediante getter, sin convertir el mueble a cadena.
if ($accion === 'mostrar' && $seleccion !== false && isset($muebles[$seleccion])): $mueble = $muebles[$seleccion]; ?>
    <h2>Datos del mueble <?= (int)$seleccion ?></h2>
    <dl>
        <dt>Tipo</dt><dd><?= htmlspecialchars((new ReflectionClass($mueble))->getShortName(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Nombre</dt><dd><?= htmlspecialchars($mueble->getNombre(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Fabricante</dt><dd><?= htmlspecialchars($mueble->getFabricante(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>País</dt><dd><?= htmlspecialchars($mueble->getPais(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Año</dt><dd><?= $mueble->getAnio() ?></dd>
        <dt>Fecha inicio de venta</dt><dd><?= htmlspecialchars($mueble->getFechaIniVenta(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Fecha fin de venta</dt><dd><?= htmlspecialchars($mueble->getFechaFinVenta(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Precio</dt><dd><?= number_format($mueble->getPrecio(), 2, ',', '.') ?> €</dd>
        <?php foreach ($mueble->damePropiedadesAdicionales() as $propiedad): $getter = 'get' . $propiedad; ?>
            <dt><?= htmlspecialchars($propiedad, ENT_QUOTES, 'UTF-8') ?></dt><dd><?= htmlspecialchars((string)$mueble->$getter(), ENT_QUOTES, 'UTF-8') ?></dd>
        <?php endforeach; ?>
        <dt>Características</dt><dd><?= htmlspecialchars((string)$mueble->getCaracteristicas(), ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Material</dt><dd><?= $mueble->getMaterialDescripcion()?></dd>
    </dl>
<?php elseif ($accion === 'mostrar'): ?>
    <p class="error">Selecciona un mueble válido.</p>
<?php endif; ?>
<?php finCuerpo();

function cabecera() { ?>
    <meta name="description" content="Catálogo y gestión de muebles de la tienda">
<?php }
