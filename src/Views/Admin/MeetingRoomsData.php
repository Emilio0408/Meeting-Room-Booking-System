<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Sale Riunioni - Admin Dashboard</title>
    <link rel="stylesheet" href="/CSS/Admin/MeetingRoomsData.css">
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
                <h1 class="page-title">🏢 Gestione Sale Riunioni</h1>
            </div>
        </div>
    </div>

    <!-- Container principale -->
    <div class="container">
        <!-- Messaggio di feedback -->
        <div id="message" class="message"></div>


        <!-- Form per aggiungere sala -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <span>➕</span>
                    Aggiungi Nuova Sala Riunioni
                </h2>
            </div>

            <div class="form-container">
                <form id="addRoomForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="newEdificio">Edificio *</label>
                            <select id="newEdificio" name="edificio" required>
                                <option value="">-- Seleziona edificio --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="newPiano">Piano *</label>
                            <select id="newPiano" name="piano" required>
                                <option value="">-- Seleziona piano --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="newCapienza">Capienza *</label>
                            <input type="number" id="newCapienza" name="capienza" min="1" max="20" required
                                placeholder="Inserisci capienza (1-20)">
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>➕</span>
                        Crea Sala Riunioni
                    </button>
                </form>
            </div>
        </div>




        <!-- Statistiche -->
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-label">Totale Sale</div>
                <div class="stat-value"><?php echo count($meetingRooms ?? []); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Prenotazioni Oggi</div>
                <div class="stat-value">
                    <?php
                    $totalBookings = 0;
                    foreach ($todayBookings ?? [] as $bookings) {
                        $totalBookings += count($bookings);
                    }
                    echo $totalBookings;
                    ?>
                </div>
            </div>
        </div>

        <!-- Grid Sale Riunioni -->
        <?php if (!empty($meetingRooms)): ?>
            <div class="rooms-grid">
                <?php foreach ($meetingRooms as $index => $room): ?>
                    <div class="room-card">
                        <div class="room-header">
                            <div class="room-id">Sala <?php echo htmlspecialchars($room['ID']); ?></div>
                        </div>

                        <div class="room-body">
                            <!-- Info sala -->
                            <div class="room-info-grid">
                                <div class="info-item">
                                    <div class="info-icon">🏢</div>
                                    <div class="info-label">Edificio</div>
                                    <div class="info-value"><?php echo htmlspecialchars($room['Edificio']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">📍</div>
                                    <div class="info-label">Piano</div>
                                    <div class="info-value"><?php echo htmlspecialchars($room['Piano']); ?>°</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">👥</div>
                                    <div class="info-label">Capienza</div>
                                    <div class="info-value"><?php echo htmlspecialchars($room['Capienza']); ?></div>
                                </div>
                            </div>

                            <!-- Prenotazioni oggi -->
                            <div class="bookings-section">
                                <div class="bookings-title">
                                    <span>📅</span>
                                    Prenotazioni di Oggi
                                </div>
                                <?php
                                $roomBookings = $todayBookings[$index] ?? [];
                                if (!empty($roomBookings)):
                                    ?>
                                    <div class="bookings-list">
                                        <?php foreach ($roomBookings as $booking): ?>
                                            <span class="time-badge">
                                                <?php echo htmlspecialchars(substr($booking['FasciaOraria'], 0, -3)); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="no-bookings">Nessuna prenotazione per oggi</p>
                                <?php endif; ?>
                            </div>

                            <!-- Pulsanti azione -->
                            <div class="action-buttons">
                                <button class="btn-edit"
                                    onclick="openEditModal(<?php echo htmlspecialchars(json_encode($room)); ?>)">
                                    <span>✏️</span>
                                    Modifica
                                </button>
                                <button class="btn-delete" onclick="deleteRoom(<?php echo htmlspecialchars($room['ID']); ?>)">
                                    <span>🗑️</span>
                                    Elimina
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🏢</div>
                <p>Nessuna sala riunioni configurata nel sistema</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Modifica -->
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Modifica Sala <span id="modalRoomId"></span></h3>
                <button class="close-modal" onclick="closeEditModal()">×</button>
            </div>

            <form id="editRoomForm">
                <div class="modal-body">
                    <input type="hidden" id="editRoomId" name="roomId">

                    <div class="form-group">
                        <label for="editEdificio">Edificio</label>
                        <select id="editEdificio" name="edificio">
                            <option value="">-- Non modificare --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                        <div class="form-hint">Seleziona un edificio tra A, B o C</div>
                    </div>

                    <div class="form-group">
                        <label for="editPiano">Piano</label>
                        <select id="editPiano" name="piano">
                            <option value="">-- Non modificare --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        <div class="form-hint">Seleziona un piano tra 1 e 4</div>
                    </div>

                    <div class="form-group">
                        <label for="editCapienza">Capienza</label>
                        <input type="number" id="editCapienza" name="capienza" min="1" max="20"
                            placeholder="Non modificare">
                        <div class="form-hint">Inserisci un numero tra 1 e 20</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Annulla</button>
                    <button type="submit" class="btn-save">Salva Modifiche</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/JS/Admin/MeetingRoomsData.js"></script>
</body>

</html>