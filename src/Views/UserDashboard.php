<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazioni Meeting Rooms - Dashboard</title>
    <link rel="stylesheet" href="/CSS/UserDashboard.css">
</head>

<body>

    <!-- ALERT PER VISUALIZZAZIONE DEI MESSAGGI DI AVVENUTA PRENOTAZIONE -->

    <div id="messageAlert" class="alert-message" style="display: none;">
        <span id="messageText"></span>
        <button id="closeAlert" class="alert-close-btn">&times;</button>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1 class="welcome-title">Ciao, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <form action="/auth" method="POST">
                <input type="hidden" name="request" value="logout">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Container principale -->
    <div class="container">
        <!-- Sezione Sale Riunioni -->
        <div class="section">
            <h2 class="section-title">Sale Riunioni Disponibili</h2>
            <?php if (!empty($availableRooms)): ?>
                <div class="rooms-grid">
                    <?php foreach ($availableRooms as $room): ?>
                        <div class="room-card">
                            <div class="room-header">
                                Sala <?php echo htmlspecialchars($room['ID']); ?>
                            </div>
                            <div class="room-info">
                                <div class="room-info-item">
                                    <div class="room-info-icon">🏢</div>
                                    <div class="room-info-content">
                                        <div class="room-info-label">Edificio</div>
                                        <div class="room-info-value"><?php echo htmlspecialchars($room['Edificio']); ?></div>
                                    </div>
                                </div>
                                <div class="room-info-item">
                                    <div class="room-info-icon">📍</div>
                                    <div class="room-info-content">
                                        <div class="room-info-label">Piano</div>
                                        <div class="room-info-value"><?php echo htmlspecialchars($room['Piano']); ?>°</div>
                                    </div>
                                </div>
                                <div class="room-info-item">
                                    <div class="room-info-icon">👥</div>
                                    <div class="room-info-content">
                                        <div class="room-info-label">Capienza</div>
                                        <div class="room-info-value"><?php echo htmlspecialchars($room['Capienza']); ?> persone
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="room-footer">
                                <button class="book-btn" onclick="openSidebar(<?php echo htmlspecialchars($room['ID']); ?>)">
                                    Prenota
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-message">Nessuna sala disponibile al momento.</p>
            <?php endif; ?>
        </div>


        <!-- Sezione Prenotazioni Utente -->
        <div class="section">
            <h2 class="section-title">Le Tue Prenotazioni</h2>
            <?php if (!empty($userBookings)): ?>
                <div class="bookings-grid">
                    <?php foreach ($userBookings as $booking): ?>
                        <div class="booking-card">
                            <div class="booking-header">
                                <div class="booking-date">
                                    <div class="date-icon">📅</div>
                                    <div class="date-value"><?php echo htmlspecialchars($booking['DATA']); ?></div>
                                </div>
                                <div class="booking-time">
                                    <?php echo htmlspecialchars(substr($booking['FasciaOraria'], 0, -3)); ?>
                                </div>
                            </div>
                            <div class="booking-body">
                                <div class="booking-detail">
                                    <span class="detail-icon">🏢</span>
                                    <div class="detail-content">
                                        <div class="detail-value">Sala <?php echo htmlspecialchars($booking['IDSala']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-footer">
                                <button type="submit" class="cancel-btn">
                                    <span class="cancel-icon">🗑️</span>
                                    Cancella Prenotazione
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-message">Non hai ancora effettuato nessuna prenotazione.</p>
            <?php endif; ?>
        </div>




        <!-- Sidebar per prenotazione -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
        <div class="sidebar" id="bookingSidebar">
            <div class="sidebar-header">
                <h3>Prenota Sala <span id="roomIdDisplay"></span></h3>
                <button class="close-btn" onclick="closeSidebar()">&times;</button>
            </div>
            <div class="sidebar-content">
                <form id="bookingForm">
                    <input type="hidden" id="selectedRoomId" name="room_id">

                    <div class="form-group">
                        <label for="bookingDate">Seleziona Data:</label>
                        <select id="bookingDate" name="date" required>
                            <option value="">-- Scegli una data --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="timeSlot">Seleziona Fascia Oraria:</label>
                        <select id="timeSlot" name="time_slot" disabled required>
                            <option value="">-- Prima seleziona una data --</option>
                        </select>
                    </div>

                    <button type="submit" class="confirm-booking-btn">Conferma Prenotazione</button>
                </form>
            </div>
        </div>

        <script src="/JS/UserDashboard.js"></script>
</body>

</html>