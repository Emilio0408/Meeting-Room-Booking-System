function generateDates() {
    const dateSelect = document.getElementById('bookingDate');
    const today = new Date();

    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        const dateString = `${year}-${month}-${day}`;
        const option = document.createElement('option');
        option.value = dateString;
        option.textContent = dateString;
        dateSelect.appendChild(option);
    }
}

// Apri sidebar
function openSidebar(roomId) {
    document.getElementById('bookingSidebar').classList.add('active');
    document.getElementById('sidebarOverlay').classList.add('active');
    document.getElementById('selectedRoomId').value = roomId;
    document.getElementById('roomIdDisplay').textContent = roomId;
    document.body.style.overflow = 'hidden';
}

// Chiudi sidebar
function closeSidebar() {
    document.getElementById('bookingSidebar').classList.remove('active');
    document.getElementById('sidebarOverlay').classList.remove('active');
    document.body.style.overflow = 'auto';

    // Reset form
    document.getElementById('bookingForm').reset();

    // Reset manuale delle select
    const dateSelect = document.getElementById('bookingDate');
    const timeSlotSelect = document.getElementById('timeSlot');

    // Reimposta la select delle date al valore predefinito
    dateSelect.selectedIndex = 0;

    // Reimposta e disabilita la select delle fasce orarie
    timeSlotSelect.innerHTML = '<option value="">-- Prima seleziona una data --</option>';
    timeSlotSelect.disabled = true;
}




// Inizializza date al caricamento
generateDates();



// Funzione per mostrare messaggi
function showAlert(message, isSuccess) {
    const alert = document.getElementById('messageAlert');
    const messageText = document.getElementById('messageText');

    messageText.textContent = message;
    alert.className = isSuccess ? 'alert-message success' : 'alert-message error';
    alert.style.display = 'flex';

    // Nascondi automaticamente dopo 5 secondi
    setTimeout(() => {
        hideAlert();
    }, 5000);
}

// Funzione per nascondere messaggi
function hideAlert() {
    const alert = document.getElementById('messageAlert');
    alert.style.display = 'none';
}



// Event listener per chiudere l'alert
document.getElementById('closeAlert').addEventListener('click', hideAlert);


// RICHIESTA AJAX PER OTTENERE I TIMESLOT DISPONIBILI IN UNA CERTA ORA
document.getElementById('bookingDate').addEventListener('change', function () {
    const selectedDate = this.value;
    const roomId = document.getElementById('selectedRoomId').value;
    const timeSlotSelect = document.getElementById('timeSlot');

    if (!selectedDate) {
        timeSlotSelect.disabled = true;
        timeSlotSelect.innerHTML = '<option value="">-- Prima seleziona una data --</option>';
        return;
    }

    // Disabilita la select e mostra "Caricamento..."
    timeSlotSelect.disabled = true;
    timeSlotSelect.innerHTML = '<option value="">Caricamento fasce orarie...</option>';

    // Effettua la richiesta AJAX
    fetch(`/dashboard?request=GetTimeSlot&Data=${selectedDate}&RoomID=${roomId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore nel caricamento delle fasce orarie');
            }
            return response.json();
        })
        .then(availableSlots => {
            // Pulisci la select
            timeSlotSelect.innerHTML = '';

            if (availableSlots.length === 0) {
                timeSlotSelect.innerHTML = '<option value="">Nessuna fascia oraria disponibile</option>';
                return;
            }

            // Aggiungi le opzioni con il formato "9:00 - 10:00"
            availableSlots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;

                // Calcola l'orario di fine (inizio + 1 ora)
                const [hours, minutes] = slot.split(':').map(Number);
                const endTime = new Date(0, 0, 0, hours + 1, minutes);
                const endTimeString = `${String(endTime.getHours()).padStart(2, '0')}:${String(endTime.getMinutes()).padStart(2, '0')}`;

                option.textContent = `${slot} - ${endTimeString}`;
                timeSlotSelect.appendChild(option);
            });

            // Abilita la select
            timeSlotSelect.disabled = false;
        })
        .catch(error => {
            console.error('Errore:', error);
            timeSlotSelect.innerHTML = '<option value="">Errore nel caricamento</option>';
        });
});










// RICHIESTA AJAX PER INVIO PRENOTAZIONE
document.getElementById('bookingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const roomId = document.getElementById('selectedRoomId').value;
    const date = document.getElementById('bookingDate').value;
    const timeSlot = document.getElementById('timeSlot').value;


    // Disabilita il pulsante durante l'invio
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Prenotazione in corso...';

    // Crea i dati da inviare
    const formData = new FormData();
    formData.append('request', 'InsertBooking');
    formData.append('RoomID', roomId);
    formData.append('TimeSlot', timeSlot);
    formData.append('Data', date);

    // Effettua la richiesta AJAX con POST
    fetch('/dashboard', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore di rete');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert(data.message, true);
                closeSidebar();


                // AGGIUNGI LA NUOVA PRENOTAZIONE ALLA GRIGLIA
                addBookingToTable({
                    room_id: roomId,
                    data: date,
                    fascia_oraria: timeSlot,
                    stato: 'Confermata'
                });

            } else {
                showAlert(data.message, false);
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showAlert('Errore durante la prenotazione', false);
        })
        .finally(() => {
            // Riabilita il pulsante
            submitBtn.disabled = false;
            submitBtn.textContent = 'Conferma Prenotazione';
        });
});
//Funzione per aggiungere la prenotazione alla griglia in tempo reale.
function addBookingToTable(bookingData) {
    // Trova la sezione delle prenotazioni in modo più specifico
    const sections = document.querySelectorAll('.section');
    const bookingsSection = sections[sections.length - 1]; // Ultima sezione

    const emptyMessage = bookingsSection.querySelector('.empty-message');
    let bookingsGrid = bookingsSection.querySelector('.bookings-grid');

    // Se c'è il messaggio "nessuna prenotazione", rimuovilo
    if (emptyMessage && emptyMessage.textContent.includes('Non hai ancora effettuato')) {
        emptyMessage.remove();
    }

    // Se la griglia non esiste, creala
    if (!bookingsGrid) {
        const gridHTML = '<div class="bookings-grid"></div>';
        bookingsSection.insertAdjacentHTML('beforeend', gridHTML);
        bookingsGrid = bookingsSection.querySelector('.bookings-grid');
    }

    // Formatta la fascia oraria come nelle card esistenti (solo l'orario di inizio)
    const timeSlotFormatted = bookingData.fascia_oraria.split(' - ')[0];



    // Crea la nuova card
    const newCardHTML = `
        <div class="booking-card">
            <div class="booking-header">
                <div class="booking-date">
                    <div class="date-icon">📅</div>
                    <div class="date-value">${bookingData.data}</div>
                </div>
                <div class="booking-time">${timeSlotFormatted}</div>
            </div>
            <div class="booking-body">
                <div class="booking-detail">
                    <span class="detail-icon">🏢</span>
                    <div class="detail-content">
                        <div class="detail-label">Sala</div>
                        <div class="detail-value">Sala ${bookingData.room_id}</div>
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
    `;

    // Aggiungi la card all'inizio della griglia
    bookingsGrid.insertAdjacentHTML('afterbegin', newCardHTML);
}


/*RICHIESTA AJAX PER LA CANCELLAZIONE DI UNA PRENOTAZIONE*/

document.addEventListener('click', function (e) {
    if (e.target.closest('.cancel-btn')) {
        const cancelBtn = e.target.closest('.cancel-btn');
        const bookingCard = cancelBtn.closest('.booking-card');

        // Recupera i dati della prenotazione dalla card
        const roomId = bookingCard.querySelector('.detail-value').textContent.replace('Sala ', '');
        const date = bookingCard.querySelector('.date-value').textContent;
        const timeSlot = bookingCard.querySelector('.booking-time').textContent.trim(); // Aggiungi i secondi

        // Disabilita il pulsante durante la richiesta
        cancelBtn.disabled = true;
        cancelBtn.innerHTML = '<span class="cancel-icon">⏳</span> Cancellazione in corso...';

        // Crea i dati da inviare
        const formData = new FormData();
        formData.append('request', 'DeleteBooking');
        formData.append('RoomID', roomId);
        formData.append('TimeSlot', timeSlot);
        formData.append('Data', date);

        // Effettua la richiesta AJAX con POST
        fetch('/dashboard', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore di rete');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, true);
                    // Rimuovi la card dalla griglia
                    bookingCard.remove();

                    // Se non ci sono più prenotazioni, mostra il messaggio "nessuna prenotazione"
                    const bookingsGrid = document.querySelector('.bookings-grid');
                    if (bookingsGrid && bookingsGrid.children.length === 0) {
                        const sections = document.querySelectorAll('.section');
                        const bookingsSection = sections[1]; // Seconda sezione (indice 1)
                        if (bookingsSection) {
                            bookingsGrid.remove();
                            const emptyMessageHTML = '<p class="empty-message">Non hai ancora effettuato nessuna prenotazione.</p>';
                            bookingsSection.insertAdjacentHTML('beforeend', emptyMessageHTML);
                        } else {
                            console.error('Sezione prenotazioni non trovata');
                        }
                    }
                } else {
                    showAlert(data.message, false);
                    // Riabilita il pulsante in caso di errore
                    cancelBtn.disabled = false;
                    cancelBtn.innerHTML = '<span class="cancel-icon">🗑️</span> Cancella Prenotazione';
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                showAlert('Errore durante la cancellazione', false);
                // Riabilita il pulsante in caso di errore
                cancelBtn.disabled = false;
                cancelBtn.innerHTML = '<span class="cancel-icon">🗑️</span> Cancella Prenotazione';
            });
    }
});