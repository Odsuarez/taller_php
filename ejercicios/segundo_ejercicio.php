<?php
require_once '../clases/inicio.php';

$flash = new Flash('segundo');

// 1) Al enviar el formulario: calcular, guardar el resultado y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroEnviado = (string) ($_POST['numero'] ?? '');
    $operacionEnviada = (string) ($_POST['operacion'] ?? '');
    $calc = new Secuencia();

    $flash->guardar('numero', $numeroEnviado);
    $flash->guardar('operacion', $operacionEnviada);

    if (!Numero::esEntero($numeroEnviado, Secuencia::MAX_DIGITOS)) {
        $flash->guardar('error', 'Ingrese un número entero positivo (solo dígitos, máximo ' . Secuencia::MAX_DIGITOS . ').');
    } elseif ($operacionEnviada === 'fibonacci') {
        $n = Numero::aEntero($numeroEnviado);
        if ($n < 1 || $n > Secuencia::MAX_FIBONACCI) {
            $flash->guardar('error', 'Para Fibonacci ingrese un número entre 1 y ' . Secuencia::MAX_FIBONACCI . '.');
        } else {
            $flash->guardar('serie', $calc->fibonacci($n));
            $flash->guardar('mensaje', 'Serie de Fibonacci con ' . $n . ' términos:');
        }
    } elseif ($operacionEnviada === 'factorial') {
        $n = Numero::aEntero($numeroEnviado);
        if ($n > Secuencia::MAX_FACTORIAL) {
            $flash->guardar('error', 'Para factorial ingrese un número entre 0 y ' . Secuencia::MAX_FACTORIAL . '.');
        } else {
            $flash->guardar('serie', $calc->factorial($n));
            $flash->guardar('mensaje', 'Factoriales de 0! hasta ' . $n . '! (el último es el resultado de ' . $n . '!):');
        }
    } else {
        $flash->guardar('error', 'Seleccione una operación válida.');
    }

    $flash->redirigir('segundo_ejercicio.php');
}

// 2) Al mostrar la página: leer lo guardado (se borra solo)
$datos = $flash->recoger();
$numero = $datos['numero'] ?? '';
$operacion = $datos['operacion'] ?? 'fibonacci';
$serie = $datos['serie'] ?? [];
$mensaje = $datos['mensaje'] ?? '';
$error = $datos['error'] ?? '';

$titulo = 'Segundo ejercicio';
require '../partes/encabezado.php';
?>
    <form method="post" action="">
        <div>
            <label for="numero">Número</label>
            <input type="text" name="numero" id="numero" inputmode="numeric" value="<?= Vista::e($numero) ?>">
        </div>
        <div>
            <label for="operacion">Operación</label>
            <select name="operacion" id="operacion">
                <option value="fibonacci" <?= $operacion === 'fibonacci' ? 'selected' : '' ?>>Sucesión de Fibonacci</option>
                <option value="factorial" <?= $operacion === 'factorial' ? 'selected' : '' ?>>Factorial</option>
            </select>
        </div>
        <div>
            <button type="submit">calcular</button>
        </div>
    </form>

    <?php if ($error !== ''): ?>
        <p class="error"><?= Vista::e($error) ?></p>
    <?php elseif (count($serie) > 0): ?>
        <div class="resultado">
            <p><?= Vista::e($mensaje) ?></p>
            <ul class="serie">
                <?php foreach ($serie as $valor): ?>
                    <li><?= Vista::e($valor) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

<?php require '../partes/pie.php'; ?>
