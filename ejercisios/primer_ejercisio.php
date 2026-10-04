<?php
require_once '../src/Acronimo.php';
require_once '../src/Vista.php';
session_start();

$frase = '';
$acronimo = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fraseEnviada = (string) ($_POST['palabra'] ?? '');
    $objeto = new Acronimo($fraseEnviada);

    if ($objeto->estaVacia()) {
        $_SESSION['error'] = 'Por favor ingrese una frase.';
    } else {
        $_SESSION['frase'] = $fraseEnviada;
        $_SESSION['acronimo'] = $objeto->generar();
    }

    header('Location: primer_ejercisio.php');
    exit;
}


if (isset($_SESSION['acronimo'])) {
    $frase = $_SESSION['frase'];
    $acronimo = $_SESSION['acronimo'];
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
}
unset($_SESSION['frase'], $_SESSION['acronimo'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primer ejercicio</title>
    <link rel="stylesheet" href="../css/ejercicio1.css">
</head>
<body>
    <h1>Primer ejercicio</h1>
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
        <div><p>Acrónimo: <strong><?= Vista::e($acronimo) ?></strong></p></div>
    <?php endif; ?>

    <a href="../Index.html">volver</a>
</body>
</html>