<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Prenotazioni - Admin Dashboard</title>
    <link rel="stylesheet" href="/CSS/Admin/BookingsData.css">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="/adminDashboard" class="back-btn">
                    <span>←</span>
                    Indietro
                </a>
                <h1 class="page-title">📅 Gestione Prenotazioni</h1>
            </div>
        </div>
    </div>

    <!-- Container principale -->
    <div class="container">
        <!-- Messaggio di feedback -->
        <div id="message" class="message"></div>

        <!-- Statistiche -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <div class="stat-label">Totale Prenotazioni</div>
                    <div class="stat-value"><?php echo count($AllBookings ?? []); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <div class="stat-label">Prenotazioni Oggi</div>
                    <div class="stat-value">
                        <?php
                        $oggi = date('Y-m-d');
                        echo count(array_filter($AllBookings ?? [], fn($b) => $b['DATA'] === $oggi));
                        ?>
                    </div>
                </div>
            </div>

        </div>


        <!-- Tabella Prenotazioni -->
        <div class="table-section">
            <div class="section-header">
                <h2 class="section-title">
                    <span>📋</span>
                    Tutte le Prenotazioni
                </h2>
                <div class="table-info">
                    <span id="resultCount"><?php echo count($AllBookings ?? []); ?></span> risultati
                </div>
            </div>

            <?php if (!empty($AllBookings)): ?>
                <div class="table-wrapper">
                    <table class="bookings-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">🏢</span>
                                        Sala
                                    </div>
                                </th>
                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">👤</span>
                                        Utente
                                    </div>
                                </th>
                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">📅</span>
                                        Data
                                    </div>
                                </th>
                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">🕐</span>
                                        Fascia Oraria
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($AllBookings as $booking): ?>
                                <tr class="booking-row" data-sala="<?php echo htmlspecialchars($booking['IDSala']); ?>"
                                    data-utente="<?php echo htmlspecialchars(strtolower($booking['Utente'])); ?>"
                                    data-data="<?php echo htmlspecialchars($booking['DATA']); ?>">
                                    <td>
                                        <div class="sala-badge">
                                            <span class="sala-icon">🏢</span>
                                            <strong>Sala <?php echo htmlspecialchars($booking['IDSala']); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <span class="user-avatar">👤</span>
                                            <span class="user-name"><?php echo htmlspecialchars($booking['Utente']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-display">
                                            <span class="date-icon">📅</span>
                                            <?php
                                            $date = new DateTime($booking['DATA']);
                                            echo $date->format('d/m/Y');
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-badge">
                                            🕐 <?php echo htmlspecialchars(substr($booking['FasciaOraria'], 0, -3)); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h3>Nessuna Prenotazione</h3>
                    <p>Non ci sono prenotazioni nel sistema</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>