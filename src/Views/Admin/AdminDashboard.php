<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sistema Prenotazioni</title>
    <link rel="stylesheet" href="/CSS/Admin/AdminDashboard.css" ;>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="admin-title">
                <h1 class="welcome-title">Dashboard Admin</h1>
                <span class="admin-badge">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <form action="/auth" method="POST">
                <input type="hidden" name="request" value="logout">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Container principale -->
    <div class="container">
        <h2 class="page-title">Pannello di Amministrazione</h2>

        <div class="cards-grid">
            <!-- Card Utenti -->
            <a href="/adminDashboard/users" class="nav-card">
                <span class="card-icon">👥</span>
                <h3 class="card-title">Gestione Utenti</h3>
                <p class="card-description">
                    Visualizza, aggiungi e gestisci gli utenti del sistema
                </p>
            </a>

            <!-- Card Sale Riunioni -->
            <a href="/adminDashboard/meetingrooms" class="nav-card">
                <span class="card-icon">🏢</span>
                <h3 class="card-title">Sale Riunioni</h3>
                <p class="card-description">
                    Gestisci le sale disponibili, modifica e cancella
                </p>
            </a>

            <!-- Card Prenotazioni -->
            <a href="/adminDashboard/bookings" class="nav-card">
                <span class="card-icon">📅</span>
                <h3 class="card-title">Prenotazioni</h3>
                <p class="card-description">
                    Visualizza tutte le prenotazioni del sistema
                </p>
            </a>
        </div>
    </div>
</body>

</html>