<?php
require_once '../clases/inicio.php';

$flash = new Flash('cuarto');

// 1) Al enviar el formulario: calcular, guardar el resultado y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $textoA = (string) ($_POST['conjunto_a'] ?? '');
    $textoB = (string) ($_POST['conjunto_b'] ?? '');
    $conj = new Conjuntos();

    $a = $conj->aConjunto($textoA);
    $b = $conj->aConjunto($textoB);

    $flash->guardar('textoA', $textoA);
    $flash->guardar('textoB', $textoB);

    if ($a === null || $b === null) {
        $flash->guardar('error', 'Cada conjunto debe tener entre 1 y ' . Conjuntos::MAX_ELEMENTOS
            . ' números enteros (máximo ' . Conjuntos::MAX_DIGITOS . ' dígitos) separados por espacios o comas. Ej: 1, 2, 3');
    } else {
        $flash->guardar('resultado', [
            'a'            => $a,
            'b'            => $b,
            'union'        => $conj->union($a, $b),
            'interseccion' => $conj->interseccion($a, $b),
            'a_menos_b'    => $conj->diferencia($a, $b),
            'b_menos_a'    => $conj->diferencia($b, $a),
        ]);
    }

    $flash->redirigir('cuarto_ejercicio.php');
}

// 2) Al mostrar la página: leer lo guardado (se borra solo)
$datos = $flash->recoger();
$textoA = $datos['textoA'] ?? '';
$textoB = $datos['textoB'] ?? '';
$resultado = $datos['resultado'] ?? [];
$error = $datos['error'] ?? '';

$titulo = 'Cuarto ejercicio';
require '../partes/encabezado.php';
?>
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
            <p>A = <strong><?= Vista::conjunto($resultado['a']) ?></strong></p>
            <p>B = <strong><?= Vista::conjunto($resultado['b']) ?></strong></p>
            <hr>
            <p>Unión (A ∪ B): <strong><?= Vista::conjunto($resultado['union']) ?></strong></p>
            <p>Intersección (A ∩ B): <strong><?= Vista::conjunto($resultado['interseccion']) ?></strong></p>
            <p>Diferencia (A − B): <strong><?= Vista::conjunto($resultado['a_menos_b']) ?></strong></p>
            <p>Diferencia (B − A): <strong><?= Vista::conjunto($resultado['b_menos_a']) ?></strong></p>
        </div>
    <?php endif; ?>

<?php require '../partes/pie.php'; ?>
