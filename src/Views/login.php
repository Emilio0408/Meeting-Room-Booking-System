<?php
// Se necessario, qui potrai in futuro aggiungere logica PHP per la sessione, messaggi di errore, ecc.
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeetingRoomBooking - Login</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h1>Accedi</h1>

            <!-- Messaggio di errore/successo -->
            <div id="message" class="message" style="display: none;"></div>

            <form id="loginForm" class="login-form">
                <input type="hidden" name="request" value="login">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Inserisci il tuo username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Inserisci la tua password"
                        required>
                </div>

                <button type="submit" class="btn-login" id="loginButton">
                    <span id="buttonText">Accedi</span>
                    <span id="loadingSpinner" style="display: none;">Caricamento...</span>
                </button>
            </form>
        </div>
    </div>

    <script src="JS/login.js"></script>
</body>

</html>