<?php
$code = http_response_code();
$messages = [
    404 => 'Pagina non trovata',
    500 => 'Errore interno del server',
    403 => 'Accesso negato',
];

$message = $messages[$code] ?? 'Si è verificato un errore.';
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Errore <?= htmlspecialchars($code) ?></title>
    <link rel="stylesheet" href="/css/ErrorPage.css">
</head>

<body>
    <div class="error-container">
        <h1>Errore <?= htmlspecialchars($code) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="/">Torna alla home</a>
    </div>
</body>

</html>

<?