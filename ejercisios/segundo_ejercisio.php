<?php
require_once '../src/Secuencia.php';
require_once '../src/Vista.php';
session_start();

$numero = '';
$operacion = 'fibonacci';
$serie = [];
$mensaje = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroEnviado = (string) ($_POST['numero'] ?? '');
    $operacionEnviada = (string) ($_POST['operacion'] ?? '');
    $calc = new Secuencia();

    $_SESSION['numero'] = $numeroEnviado;
    $_SESSION['operacion'] = $operacionEnviada;

    if (!$calc->esNumeroValido($numeroEnviado)) {
        $_SESSION['error'] = 'Ingrese un número entero positivo (solo dígitos, máximo 3).';
    } elseif ($operacionEnviada === 'fibonacci') {
        $n = $calc->aEntero($numeroEnviado);
        if ($n < 1 || $n > Secuencia::MAX_FIBONACCI) {
            $_SESSION['error'] = 'Para Fibonacci ingrese un número entre 1 y ' . Secuencia::MAX_FIBONACCI . '.';
        } else {
            $_SESSION['serie'] = $calc->fibonacci($n);
            $_SESSION['mensaje'] = 'Serie de Fibonacci con ' . $n . ' términos:';
        }
    } elseif ($operacionEnviada === 'factorial') {
        $n = $calc->aEntero($numeroEnviado);
        if ($n > Secuencia::MAX_FACTORIAL) {
            $_SESSION['error'] = 'Para factorial ingrese un número entre 0 y ' . Secuencia::MAX_FACTORIAL . '.';
        } else {
            $_SESSION['serie'] = $calc->factorial($n);
            $_SESSION['mensaje'] = 'Factoriales de 0! hasta ' . $n . '! (el último es el resultado de ' . $n . '!):';
        }
    } else {
        $_SESSION['error'] = 'Seleccione una operación válida.';
    }

    header('Location: segundo_ejercisio.php');
    exit;
}


if (isset($_SESSION['operacion'])) {
    $operacion = $_SESSION['operacion'];
    $numero = $_SESSION['numero'];
}
if (isset($_SESSION['serie'])) {
    $serie = $_SESSION['serie'];
    $mensaje = $_SESSION['mensaje'];
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}
unset($_SESSION['numero'], $_SESSION['operacion'], $_SESSION['serie'], $_SESSION['mensaje'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segundo ejercicio</title>
    <link rel="stylesheet" href="../css/ejercicio2.css">
</head>
<body>
    <h1>Segundo ejercicio</h1>
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
                    <li><?= $valor ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <a href="../Index.html">volver</a>
</body>
</html>