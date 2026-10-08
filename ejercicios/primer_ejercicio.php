<?php
require_once '../clases/inicio.php';

$flash = new Flash('primer');

// 1) Al enviar el formulario: calcular, guardar el resultado y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fraseEnviada = (string) ($_POST['palabra'] ?? '');
    $objeto = new Acronimo($fraseEnviada);

    if ($objeto->estaVacia()) {
        $flash->guardar('error', 'Por favor ingrese una frase.');
    } else {
        $flash->guardar('frase', $fraseEnviada);
        $flash->guardar('acronimo', $objeto->generar());
    }

    $flash->redirigir('primer_ejercicio.php');
}

// 2) Al mostrar la página: leer lo guardado (se borra solo, por eso al recargar no queda nada)
$datos = $flash->recoger();
$frase = $datos['frase'] ?? '';
$acronimo = $datos['acronimo'] ?? '';
$error = $datos['error'] ?? '';

$titulo = 'Primer ejercicio';
require '../partes/encabezado.php';
?>
    <form method="post" action="">
        <div>
            <label for="palabra">Ingrese su frase</label>
            <input type="text" name="palabra" id="palabra" value="<?= Vista::e($frase) ?>">
        </div>
        <div>
            <button type="submit">convertir</button>
        </div>
    </form>

    <?php if ($error !== ''): ?>
        <p class="error"><?= Vista::e($error) ?></p>
    <?php elseif ($acronimo !== ''): ?>
        <div class="resultado"><p>Acrónimo: <strong><?= Vista::e($acronimo) ?></strong></p></div>
    <?php endif; ?>

<?php require '../partes/pie.php'; ?>
