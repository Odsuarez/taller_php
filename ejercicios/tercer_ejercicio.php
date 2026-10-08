<?php
require_once '../clases/inicio.php';

$flash = new Flash('tercer');
$est = new Estadistica();

// 1) Al enviar un formulario: procesar, guardar y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paso = (string) ($_POST['paso'] ?? '');

    if ($paso === 'cantidad') {
        // Paso 1: el usuario indica cuántos números va a ingresar
        $texto = (string) ($_POST['cantidad'] ?? '');

        if ($est->esCantidadValida($texto)) {
            $flash->redirigir('tercer_ejercicio.php?cantidad=' . Numero::aEntero($texto));
        }
        $flash->guardar('error', 'Ingrese una cantidad entera entre 1 y ' . Estadistica::MAX_CANTIDAD . '.');
        $flash->redirigir('tercer_ejercicio.php');
    }

    if ($paso === 'datos') {
        // Paso 2: llegan los números
        $texto = (string) ($_POST['cantidad'] ?? '');
        $enviados = $_POST['valores'] ?? [];

        if (!$est->esCantidadValida($texto) || !is_array($enviados)) {
            $flash->guardar('error', 'Datos inválidos. Intente de nuevo.');
            $flash->redirigir('tercer_ejercicio.php');
        }

        $n = Numero::aEntero($texto);
        $numeros = [];
        $textos = [];
        $todoValido = true;

        for ($i = 0; $i < $n; $i++) {
            $valor = isset($enviados[$i]) && is_string($enviados[$i]) ? $enviados[$i] : '';
            $textos[] = $valor;
            if (Numero::esReal($valor)) {
                $numeros[] = Numero::aReal($valor);
            } else {
                $todoValido = false;
            }
        }

        if (!$todoValido) {
            $flash->guardar('error', 'Todos los campos deben ser números reales (ej: 5, -3.2, 0,75).');
            $flash->guardar('valores', $textos);
            $flash->redirigir('tercer_ejercicio.php?cantidad=' . $n);
        }

        $flash->guardar('resultado', [
            'numeros'  => $numeros,
            'promedio' => $est->promedio($numeros),
            'mediana'  => $est->mediana($numeros),
            'moda'     => $est->moda($numeros),
        ]);
        $flash->redirigir('tercer_ejercicio.php');
    }

    $flash->redirigir('tercer_ejercicio.php');
}

// 2) Al mostrar la página: leer lo guardado (se borra solo)
$datos = $flash->recoger();
$error = $datos['error'] ?? '';
$valoresPrevios = $datos['valores'] ?? [];
$resultado = $datos['resultado'] ?? [];

$cantidad = 0;
if (isset($_GET['cantidad']) && is_string($_GET['cantidad']) && $est->esCantidadValida($_GET['cantidad'])) {
    $cantidad = Numero::aEntero($_GET['cantidad']);
}

$titulo = 'Tercer ejercicio';
require '../partes/encabezado.php';
?>

    <?php if ($cantidad === 0): ?>
        <!-- Paso 1: cuántos números -->
        <form method="post" action="">
            <input type="hidden" name="paso" value="cantidad">
            <div>
                <label for="cantidad">¿Cuántos números va a ingresar?</label>
                <input type="text" name="cantidad" id="cantidad" inputmode="numeric">
            </div>
            <div>
                <button type="submit">continuar</button>
            </div>
        </form>
    <?php else: ?>
        <!-- Paso 2: los números -->
        <form method="post" action="" class="datos">
            <input type="hidden" name="paso" value="datos">
            <input type="hidden" name="cantidad" value="<?= Vista::e($cantidad) ?>">
            <?php for ($i = 0; $i < $cantidad; $i++): ?>
                <div>
                    <label for="v<?= Vista::e($i) ?>">Número <?= Vista::e($i + 1) ?></label>
                    <input type="text" name="valores[]" id="v<?= Vista::e($i) ?>" inputmode="decimal"
                           value="<?= Vista::e(isset($valoresPrevios[$i]) ? (string) $valoresPrevios[$i] : '') ?>">
                </div>
            <?php endfor; ?>
            <div class="botones">
                <button type="submit">calcular</button>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p class="error"><?= Vista::e($error) ?></p>
    <?php endif; ?>

    <?php if (count($resultado) > 0): ?>
        <div class="resultado">
            <p>Números ingresados:</p>
            <ul class="serie">
                <?php foreach ($resultado['numeros'] as $n): ?>
                    <li><?= Vista::e($n) ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Promedio: <strong><?= Vista::e($resultado['promedio']) ?></strong></p>
            <p>Mediana: <strong><?= Vista::e($resultado['mediana']) ?></strong></p>
            <p>Moda:
                <strong>
                <?php if (count($resultado['moda']) === 0): ?>
                    no hay moda (ningún número se repite)
                <?php else: ?>
                    <?= Vista::e(implode(', ', $resultado['moda'])) ?>
                <?php endif; ?>
                </strong>
            </p>
        </div>
    <?php endif; ?>

<?php require '../partes/pie.php'; ?>
