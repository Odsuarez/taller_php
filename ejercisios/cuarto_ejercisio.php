<?php
require_once '../src/Conjuntos.php';
require_once '../src/Vista.php';
session_start();

$textoA = '';
$textoB = '';
$resultado = [];
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $textoA = (string) ($_POST['conjunto_a'] ?? '');
    $textoB = (string) ($_POST['conjunto_b'] ?? '');
    $conj = new Conjuntos();

    $a = $conj->aConjunto($textoA);
    $b = $conj->aConjunto($textoB);

    $_SESSION['textoA'] = $textoA;
    $_SESSION['textoB'] = $textoB;

    if ($a === null || $b === null) {
        $_SESSION['error'] = 'Cada conjunto debe tener entre 1 y ' . Conjuntos::MAX_ELEMENTOS
            . ' números enteros (máximo 9 dígitos) separados por espacios o comas. Ej: 1, 2, 3';
    } else {
        $_SESSION['resultado'] = [
            'a'            => $a,
            'b'            => $b,
            'union'        => $conj->union($a, $b),
            'interseccion' => $conj->interseccion($a, $b),
            'a_menos_b'    => $conj->diferencia($a, $b),
            'b_menos_a'    => $conj->diferencia($b, $a),
        ];
    }

    header('Location: cuarto_ejercisio.php');
    exit;
}


if (isset($_SESSION['textoA'])) {
    $textoA = $_SESSION['textoA'];
    $textoB = $_SESSION['textoB'];
}
if (isset($_SESSION['resultado'])) {
    $resultado = $_SESSION['resultado'];
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}
unset($_SESSION['textoA'], $_SESSION['textoB'], $_SESSION['resultado'], $_SESSION['error']);

function mostrarConjunto(array $conjunto): string
{
    if (count($conjunto) === 0) {
        return '∅ (vacío)';
    }
    $texto = '';
    foreach ($conjunto as $i => $elemento) {
        if ($i > 0) {
            $texto .= ', ';
        }
        $texto .= $elemento;
    }
    return '{ ' . $texto . ' }';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuarto ejercicio</title>
    <link rel="stylesheet" href="../css/ejercicio4.css">
</head>
<body>
    <h1>Cuarto ejercicio</h1>
    <form method="post" action="" class="conjuntos">
        <div>
            <label for="conjunto_a">Conjunto A</label>
            <input type="text" name="conjunto_a" id="conjunto_a" placeholder="1, 2, 3, 4"
                   value="<?= Vista::e($textoA) ?>">
        </div>
        <div>
            <label for="conjunto_b">Conjunto B</label>
            <input type="text" name="conjunto_b" id="conjunto_b" placeholder="3, 4, 5, 6"
                   value="<?= Vista::e($textoB) ?>">
        </div>
        <div class="botones">
            <button type="submit">calcular</button>
        </div>
    </form>

    <?php if ($error !== ''): ?>
        <p class="error"><?= Vista::e($error) ?></p>
    <?php elseif (count($resultado) > 0): ?>
        <div class="resultado">
            <p>A = <strong><?= Vista::e(mostrarConjunto($resultado['a'])) ?></strong></p>
            <p>B = <strong><?= Vista::e(mostrarConjunto($resultado['b'])) ?></strong></p>
            <hr>
            <p>Unión (A ∪ B): <strong><?= Vista::e(mostrarConjunto($resultado['union'])) ?></strong></p>
            <p>Intersección (A ∩ B): <strong><?= Vista::e(mostrarConjunto($resultado['interseccion'])) ?></strong></p>
            <p>Diferencia (A − B): <strong><?= Vista::e(mostrarConjunto($resultado['a_menos_b'])) ?></strong></p>
            <p>Diferencia (B − A): <strong><?= Vista::e(mostrarConjunto($resultado['b_menos_a'])) ?></strong></p>
        </div>
    <?php endif; ?>

    <a href="../Index.html">volver</a>
</body>
</html>