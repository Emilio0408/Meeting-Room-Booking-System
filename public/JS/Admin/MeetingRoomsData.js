let currentRoomId = null;

// Apri modal modifica
function openEditModal(room) {
    currentRoomId = room.ID;
    document.getElementById('modalRoomId').textContent = room.ID;
    document.getElementById('editRoomId').value = room.ID;

    // Reset form
    document.getElementById('editRoomForm').reset();

    // Mostra modal
    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Chiudi modal
function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('editRoomForm').reset();
}

// Chiudi modal cliccando fuori
document.getElementById('editModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Mostra messaggi
function showMessage(text, type) {
    const messageEl = document.getElementById('message');
    messageEl.textContent = text;
    messageEl.className = 'message ' + type + ' show';

    setTimeout(() => {
        messageEl.classList.remove('show');
    }, 5000);
}


// Gestione submit form aggiunta sala
// Gestione submit form aggiunta sala
document.getElementById('addRoomForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Recupera i dati dal form
    const formData = new FormData(this);
    const edificio = formData.get('edificio');
    const piano = formData.get('piano');
    const capienza = formData.get('capienza');

    // Validazione base
    if (!edificio || !piano || !capienza) {
        showMessage('Per favore compila tutti i campi obbligatori', 'error');
        return;
    }

    // Crea il body della richiesta
    const requestBody = new URLSearchParams();
    requestBody.append('request', 'InsertMeetingRoom')
    requestBody.append('Capienza', capienza);
    requestBody.append('Edificio', edificio);
    requestBody.append('Piano', piano);

    // Effettua la richiesta AJAX POST
    fetch('/adminDashboard', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: requestBody
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                // Reset del form
                document.getElementById('addRoomForm').reset();
                // Aggiungi la nuova sala al documento
                addNewRoomToDOM(edificio, piano, capienza, data.newRoomId);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showMessage('Errore di connessione', 'error');
        });
});

// Funzione per aggiungere la nuova sala al DOM
function addNewRoomToDOM(edificio, piano, capienza, roomId) {
    const roomsGrid = document.querySelector('.rooms-grid');
    const emptyState = document.querySelector('.empty-state');

    // Se c'è lo stato vuoto, rimuovilo
    if (emptyState) {
        emptyState.remove();
    }

    // Se non esiste la grid, creala
    if (!roomsGrid) {
        const container = document.querySelector('.container');
        const newRoomsGrid = document.createElement('div');
        newRoomsGrid.className = 'rooms-grid';
        container.appendChild(newRoomsGrid);
    }

    // Crea l'HTML per la nuova sala
    const roomCard = document.createElement('div');
    roomCard.className = 'room-card';
    roomCard.innerHTML = `
        <div class="room-header">
            <div class="room-id">Sala ${roomId}</div>
        </div>

        <div class="room-body">
            <!-- Info sala -->
            <div class="room-info-grid">
                <div class="info-item">
                    <div class="info-icon">🏢</div>
                    <div class="info-label">Edificio</div>
                    <div class="info-value">${edificio}</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-label">Piano</div>
                    <div class="info-value">${piano}°</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">👥</div>
                    <div class="info-label">Capienza</div>
                    <div class="info-value">${capienza}</div>
                </div>
            </div>

            <!-- Prenotazioni oggi -->
            <div class="bookings-section">
                <div class="bookings-title">
                    <span>📅</span>
                    Prenotazioni di Oggi
                </div>
                <p class="no-bookings">Nessuna prenotazione per oggi</p>
            </div>

            <!-- Pulsanti azione -->
            <div class="action-buttons">
                <button class="btn-edit" onclick="openEditModal({
                    'ID': '${roomId}',
                    'Edificio': '${edificio}',
                    'Piano': '${piano}',
                    'Capienza': '${capienza}'
                })">
                    <span>✏️</span>
                    Modifica
                </button>
                <button class="btn-delete" onclick="deleteRoom('${roomId}')">
                    <span>🗑️</span>
                    Elimina
                </button>
            </div>
        </div>
    `;

    // Aggiungi la nuova sala alla grid
    document.querySelector('.rooms-grid').appendChild(roomCard);

    // Aggiorna il contatore delle sale
    updateRoomsCount();
}

// Funzione per aggiornare il contatore delle sale
function updateRoomsCount() {
    const roomCards = document.querySelectorAll('.room-card');
    const statValue = document.querySelector('.stat-card:first-child .stat-value');
    if (statValue && roomCards.length > 0) {
        statValue.textContent = roomCards.length;
    }
}


// Gestione submit form modifica sala
document.getElementById('editRoomForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Recupera i dati dal form
    const formData = new FormData(this);
    const roomId = formData.get('roomId');
    const edificio = formData.get('edificio');
    const piano = formData.get('piano');
    const capienza = formData.get('capienza');

    // Validazione: almeno un campo deve essere modificato
    if (!edificio && !piano && !capienza) {
        showMessage('Per favore modifica almeno un campo', 'error');
        return;
    }

    // Costruisce i parametri per la richiesta
    const requestBody = new URLSearchParams();
    requestBody.append('request', 'UpdateMeetingRoom');
    requestBody.append('IDRoom', roomId);

    if (edificio) requestBody.append('Edificio', edificio);
    if (piano) requestBody.append('Piano', piano);
    if (capienza) requestBody.append('Capienza', capienza);

    // Effettua la richiesta AJAX POST
    fetch('/adminDashboard', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: requestBody
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                closeEditModal();
                // Refresh della pagina per aggiornare i dati
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showMessage('Errore di connessione', 'error');
        });
});


//Richiesta AJAX per cancellazione di una meeting room

function deleteRoom(roomId) {
    // Conferma prima dell'eliminazione
    if (!confirm('Sei sicuro di voler eliminare questa sala riunioni?')) {
        return;
    }

    // Crea il body della richiesta
    const requestBody = new URLSearchParams();
    requestBody.append('request', 'DeleteMeetingRoom');
    requestBody.append('IDRoom', roomId);

    // Effettua la richiesta AJAX POST
    fetch('/adminDashboard', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: requestBody
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                // Rimuove l'elemento HTML della sala
                removeRoomFromDOM(roomId);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showMessage('Errore di connessione', 'error');
        });
}

// Funzione per rimuovere la sala dal DOM
function removeRoomFromDOM(roomId) {
    // Trova tutte le card delle sale
    const roomCards = document.querySelectorAll('.room-card');

    for (let card of roomCards) {
        // Cerca l'elemento che contiene l'ID della sala
        const roomIdElement = card.querySelector('.room-id');
        if (roomIdElement && roomIdElement.textContent.includes(roomId)) {
            // Rimuove la card
            card.remove();

            // Aggiorna il contatore delle sale
            updateRoomsCount();

            // Se non ci sono più sale, mostra lo stato vuoto
            checkEmptyState();

            break;
        }
    }
}

// Funzione per verificare e mostrare lo stato vuoto
function checkEmptyState() {
    const roomsGrid = document.querySelector('.rooms-grid');
    const roomCards = document.querySelectorAll('.room-card');

    if (roomCards.length === 0 && roomsGrid) {
        // Crea lo stato vuoto
        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.innerHTML = `
            <div class="empty-icon">🏢</div>
            <p>Nessuna sala riunioni configurata nel sistema</p>
        `;

        // Sostituisce la grid con lo stato vuoto
        roomsGrid.replaceWith(emptyState);
    }
}




