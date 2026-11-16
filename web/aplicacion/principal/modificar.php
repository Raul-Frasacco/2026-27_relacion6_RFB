<?php
require_once dirname(__DIR__, 2) . '/cabecera.php';
require_once __DIR__ . '/../clases/curso2026/MuebleBase.php';
require_once __DIR__ . '/../clases/curso2026/Materiales.php';
require_once __DIR__ . '/datos.php';

$indice = filter_input(INPUT_POST, 'mueble', FILTER_VALIDATE_INT);
$mueble = ($indice !== false && $indice !== null) ? ($muebles[$indice] ?? null) : null;
$materiales = Materiales::cases();
$errores = [];
$guardado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar']) && $mueble) {
    $nombre = trim($_POST['nombre'] ?? '');
    $fabricante = trim($_POST['fabricante'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $anio = filter_var($_POST['anio'] ?? null, FILTER_VALIDATE_INT);
    $inicio = trim($_POST['fechaIniVenta'] ?? '');
    $fin = trim($_POST['fechaFinVenta'] ?? '');
    $precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);

    // Una mitad se valida con los setters de una instancia auxiliar.
    $aux = clone $mueble;
    if (!$aux->setNombre($nombre)) $errores[] = 'El nombre es obligatorio y admite hasta 40 caracteres.';
    if (!$aux->setFabricante($fabricante)) $errores[] = 'El fabricante no puede estar vacío.';
    if (!$aux->setPais($pais)) $errores[] = 'El país es obligatorio y admite hasta 20 caracteres.';
    if ($anio === false || !$aux->setAnio((int)$anio)) $errores[] = 'El año debe estar entre 2020 y el año actual.';

    // La otra mitad (fechas, precio y campos específicos) se valida directamente.
    $fechaValida = static function (string $fecha): bool {
        if (!preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $partes)) return false;
        return checkdate((int)$partes[2], (int)$partes[1], (int)$partes[3]);
    };
    if (!$fechaValida($inicio)) $errores[] = 'La fecha inicial no es válida.';
    if (!$fechaValida($fin)) $errores[] = 'La fecha final no es válida.';
    if ($fechaValida($inicio) && $anio !== false) {
        $p = array_map('intval', explode('/', $inicio));
        if ($p[2] < (int)$anio) $errores[] = 'La fecha inicial no puede ser anterior al año de fabricación.';
    }
    if ($fechaValida($inicio) && $fechaValida($fin)) {
        $a = array_map('intval', explode('/', $inicio));
        $b = array_map('intval', explode('/', $fin));
        if ([$b[2], $b[1], $b[0]] < [$a[2], $a[1], $a[0]]) $errores[] = 'La fecha final debe ser posterior o igual a la inicial.';
    }
    if ($precio === false || $precio < 30) $errores[] = 'El precio mínimo es 30.';

    $adicional = null;
    if ($mueble instanceof MuebleTradicional) {
        $peso = filter_var($_POST['peso'] ?? null, FILTER_VALIDATE_FLOAT);
        $serie = trim($_POST['serie'] ?? '');
        if ($peso === false || $peso < 15 || $peso > 300) $errores[] = 'El peso debe estar entre 15 y 300.';
        if ($serie === '') $errores[] = 'La serie es obligatoria.';
        $adicional = [$peso, $serie];
    } else {
        $porcentaje = filter_var($_POST['porcentajeReciclado'] ?? null, FILTER_VALIDATE_INT);
        if ($porcentaje === false || $porcentaje < 0 || $porcentaje > 100) $errores[] = 'El porcentaje reciclado debe estar entre 0 y 100.';
        $adicional = [$porcentaje];
    }

    // Solo si todas las restricciones se cumplen se actualiza el objeto de esta petición.
    if (!$errores) {
        $mueble->setNombre($nombre);
        $mueble->setFabricante($fabricante);
        $mueble->setPais($pais);
        $mueble->setAnio((int)$anio);
        $mueble->setFechaIniVenta($inicio);
        $mueble->setFechaFinVenta($fin);
        $mueble->setPrecio((float)$precio);
        if ($mueble instanceof MuebleTradicional) {
            $mueble->setPeso((float)$adicional[0]);
            $mueble->setSerie($adicional[1]);
        } else {
            $mueble->setPorcentajeReciclado((int)$adicional[0]);
        }
        $guardado = true;
    }
}

inicioCabecera('Modificar mueble');
cabecera();
finCabecera();
inicioCuerpo('MODIFICAR MUEBLE', [['TEXTO' => 'Tienda', 'LINK' => '/aplicacion/principal/index.php'], ['TEXTO' => 'Modificar', 'LINK' => '']]);
?>
<h2><?= $mueble ? 'Modificar ' . htmlspecialchars($mueble->getNombre(), ENT_QUOTES, 'UTF-8') : 'Mueble no encontrado' ?></h2>
<?php if (!$mueble): ?>
    <p class="error">No se ha seleccionado un mueble válido.</p>
<?php else: ?>
    <?php if ($errores): ?><div class="error"><ul><?php foreach ($errores as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <?php if ($guardado): ?><p>Datos recibidos y aplicados durante esta petición. Al no utilizar almacenamiento persistente, los cambios no se conservan al recargar.</p><?php endif; ?>
    <!-- Formulario precargado con las propiedades comunes y las del tipo concreto. -->
    <form method="post" action="/aplicacion/principal/modificar.php">
        <input type="hidden" name="mueble" value="<?= (int)$indice ?>">
        <p><label>Nombre <input class="for_texto" name="nombre" maxlength="40" required value="<?= htmlspecialchars($mueble->getNombre(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>Fabricante <input class="for_texto" name="fabricante" maxlength="30" required value="<?= htmlspecialchars($mueble->getFabricante(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>País <input class="for_texto" name="pais" maxlength="20" required value="<?= htmlspecialchars($mueble->getPais(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>Año <input class="for_texto" type="number" name="anio" min="2020" max="<?= (int)date('Y') ?>" required value="<?= $mueble->getAnio() ?>"></label></p>
        <p><label>Inicio de venta (dd/mm/aaaa) <input class="for_texto" name="fechaIniVenta" required value="<?= htmlspecialchars($mueble->getFechaIniVenta(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>Fin de venta (dd/mm/aaaa) <input class="for_texto" name="fechaFinVenta" required value="<?= htmlspecialchars($mueble->getFechaFinVenta(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>Precio <input class="for_texto" type="number" name="precio" min="30" step="0.01" required value="<?= htmlspecialchars((string)$mueble->getPrecio(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <?php if ($mueble instanceof MuebleTradicional): ?>
            <p><label>Peso <input class="for_texto" type="number" name="peso" min="15" max="300" step="0.01" required value="<?= $mueble->getPeso() ?>"></label></p>
            <p><label>Serie <input class="for_texto" name="serie" required value="<?= htmlspecialchars($mueble->getSerie(), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <?php else: ?>
            <p><label>Porcentaje reciclado <input class="for_texto" type="number" name="porcentajeReciclado" min="0" max="100" required value="<?= $mueble->getPorcentajeReciclado() ?>"></label></p>
        <?php endif; ?>
       <!-- Materiales -->
        <label for="material">Material:</label>
        <select id="material" name="material" required>
        <?php foreach ($materiales as $indice => $material): ?>
            <?php foreach ($material as $in=> $mat): ?>
            <option value="<?= (int)$indice ?>" ?>
                <?= (int)$mat ?> - 
            </option>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </select>
        <button class="boton" type="submit" name="guardar" value="1">Guardar cambios</button>
    </form>
<?php endif; ?>
<p><a href="/aplicacion/principal/index.php">Ir a Index.php</a></p>
<?php finCuerpo();

function cabecera() { ?><meta name="description" content="Formulario de modificación de muebles"><?php }
