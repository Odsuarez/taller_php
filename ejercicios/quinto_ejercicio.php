<?php
require_once '../clases/inicio.php';

$flash = new Flash('quinto');

// 1) Al enviar el formulario: calcular, guardar el resultado y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroEnviado = (string) ($_POST['numero'] ?? '');
    $conversor = new ConversorBinario();

    $flash->guardar('numero', $numeroEnviado);

    if (!Numero::esEntero($numeroEnviado, ConversorBinario::MAX_DIGITOS, true)) {
        $flash->guardar('error', 'Ingrese un número entero de máximo ' . ConversorBinario::MAX_DIGITOS . ' dígitos (puede ser negativo).');
    } else {
        $entero = Numero::aEntero($numeroEnviado);
        $conversion = $conversor->aBinario($entero);
        $flash->guardar('resultado', [
            'entero'  => $entero,
            'binario' => $conversion['binario'],
            'pasos'   => $conversion['pasos'],
        ]);
    }

    $flash->redirigir('quinto_ejercicio.php');
}

// 2) Al mostrar la página: leer lo guardado (se borra solo)
$datos = $flash->recoger();
$numero = $datos['numero'] ?? '';
$resultado = $datos['resultado'] ?? [];
$error = $datos['error'] ?? '';

$titulo = 'Quinto ejercicio';
require '../partes/encabezado.php';
?>
    <form method="post" action="">
        <div>
            <label for="numero">Número entero</label>
            <input type="text" name="numero" id="numero" inputmode="numeric" value="<?= Vista::e($numero) ?>">
        </div>
        <div>
            <button type="submit">convertir</button>
        </div>
    </form>

    <?php if ($error !== ''): ?>
        <p class="error"><?= Vista::e($error) ?></p>
    <?php elseif (count($resultado) > 0): ?>
        <div class="resultado">
            <p><?= Vista::e($resultado['entero']) ?> en binario es: <strong><?= Vista::e($resultado['binario']) ?></strong></p>

            <table>
                <tr>
                    <th>División</th>
                    <th>Cociente</th>
                    <th>Residuo</th>
                </tr>
                <?php foreach ($resultado['pasos'] as $paso): ?>
                    <tr>
                        <td><?= Vista::e($paso['dividendo']) ?> ÷ 2</td>
                        <td><?= Vista::e($paso['cociente']) ?></td>
                        <td><?= Vista::e($paso['residuo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p class="nota">Los residuos se leen de abajo hacia arriba.</p>
        </div>
    <?php endif; ?>

<?php require '../partes/pie.php'; ?>
