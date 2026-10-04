<?php
require_once '../src/Estadistica.php';
require_once '../src/Vista.php';
session_start();

$est = new Estadistica();
$error = '';
$resultado = [];
$valoresPrevios = [];
$cantidad = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paso = (string) ($_POST['paso'] ?? '');

    if ($paso === 'cantidad') {
     
        $texto = (string) ($_POST['cantidad'] ?? '');

        if ($est->esCantidadValida($texto)) {
            header('Location: tercer_ejercisio.php?cantidad=' . $est->aEntero($texto));
        } else {
            $_SESSION['error'] = 'Ingrese una cantidad entera entre 1 y ' . Estadistica::MAX_CANTIDAD . '.';
            header('Location: tercer_ejercisio.php');
        }
        exit;
    }

    if ($paso === 'datos') {
    
        $texto = (string) ($_POST['cantidad'] ?? '');
        $enviados = $_POST['valores'] ?? [];

        if (!$est->esCantidadValida($texto) || !is_array($enviados)) {
            $_SESSION['error'] = 'Datos inválidos. Intente de nuevo.';
            header('Location: tercer_ejercisio.php');
            exit;
        }

        $n = $est->aEntero($texto);
        $numeros = [];
        $textos = [];
        $todoValido = true;

        for ($i = 0; $i < $n; $i++) {
            $valor = isset($enviados[$i]) && is_string($enviados[$i]) ? $enviados[$i] : '';
            $textos[] = $valor;
            if ($est->esRealValido($valor)) {
                $numeros[] = $est->aReal($valor);
            } else {
                $todoValido = false;
            }
        }

        if (!$todoValido) {
            $_SESSION['error'] = 'Todos los campos deben ser números reales (ej: 5, -3.2, 0,75).';
            $_SESSION['valores'] = $textos;
            header('Location: tercer_ejercisio.php?cantidad=' . $n);
        } else {
            $_SESSION['resultado'] = [
                'numeros'  => $numeros,
                'promedio' => $est->promedio($numeros),
                'mediana'  => $est->mediana($numeros),
                'moda'     => $est->moda($numeros),
            ];
            header('Location: tercer_ejercisio.php');
        }
        exit;
    }

    header('Location: tercer_ejercisio.php');
    exit;
}


if (isset($_GET['cantidad']) && is_string($_GET['cantidad']) && $est->esCantidadValida($_GET['cantidad'])) {
    $cantidad = $est->aEntero($_GET['cantidad']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}
if (isset($_SESSION['valores'])) {
    $valoresPrevios = $_SESSION['valores'];
}
if (isset($_SESSION['resultado'])) {
    $resultado = $_SESSION['resultado'];
}
unset($_SESSION['error'], $_SESSION['valores'], $_SESSION['resultado']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tercer ejercicio</title>
    <link rel="stylesheet" href="../css/ejercicio3.css">
</head>
<body>
    <h1>Tercer ejercicio</h1>

    <?php if ($cantidad === 0): ?>
      
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
     
        <form method="post" action="" class="datos">
            <input type="hidden" name="paso" value="datos">
            <input type="hidden" name="cantidad" value="<?= $cantidad ?>">
            <?php for ($i = 0; $i < $cantidad; $i++): ?>
                <div>
                    <label for="v<?= $i ?>">Número <?= $i + 1 ?></label>
                    <input type="text" name="valores[]" id="v<?= $i ?>" inputmode="decimal"
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
                    <li><?= $n ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Promedio: <strong><?= $resultado['promedio'] ?></strong></p>
            <p>Mediana: <strong><?= $resultado['mediana'] ?></strong></p>
            <p>Moda:
                <strong>
                <?php if (count($resultado['moda']) === 0): ?>
                    no hay moda (ningún número se repite)
                <?php else: ?>
                    <?= implode(', ', $resultado['moda']) ?>
                <?php endif; ?>
                </strong>
            </p>
        </div>
    <?php endif; ?>

    <a href="../Index.html">volver</a>
</body>
</html>