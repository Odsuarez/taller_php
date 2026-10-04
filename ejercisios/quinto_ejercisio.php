<?php
require_once '../src/ConversorBinario.php';
require_once '../src/Vista.php';
session_start();

$numero = '';
$resultado = [];
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroEnviado = (string) ($_POST['numero'] ?? '');
    $conversor = new ConversorBinario();

    $_SESSION['numero'] = $numeroEnviado;

    if (!$conversor->esEnteroValido($numeroEnviado)) {
        $_SESSION['error'] = 'Ingrese un número entero de máximo ' . ConversorBinario::MAX_DIGITOS . ' dígitos (puede ser negativo).';
    } else {
        $entero = $conversor->aEntero($numeroEnviado);
        $conversion = $conversor->aBinario($entero);
        $_SESSION['resultado'] = [
            'entero'  => $entero,
            'binario' => $conversion['binario'],
            'pasos'   => $conversion['pasos'],
        ];
    }

    header('Location: quinto_ejercisio.php');
    exit;
}


if (isset($_SESSION['numero'])) {
    $numero = $_SESSION['numero'];
}
if (isset($_SESSION['resultado'])) {
    $resultado = $_SESSION['resultado'];
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}
unset($_SESSION['numero'], $_SESSION['resultado'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quinto ejercicio</title>
    <link rel="stylesheet" href="../css/ejercicio5.css">
</head>
<body>
    <h1>Quinto ejercicio</h1>
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
            <p><?= $resultado['entero'] ?> en binario es: <strong><?= Vista::e($resultado['binario']) ?></strong></p>

            <table>
                <tr>
                    <th>División</th>
                    <th>Cociente</th>
                    <th>Residuo</th>
                </tr>
                <?php foreach ($resultado['pasos'] as $paso): ?>
                    <tr>
                        <td><?= $paso['dividendo'] ?> ÷ 2</td>
                        <td><?= $paso['cociente'] ?></td>
                        <td><?= $paso['residuo'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p class="nota">Los residuos se leen de abajo hacia arriba.</p>
        </div>
    <?php endif; ?>

    <a href="../Index.html">volver</a>
</body>
</html>