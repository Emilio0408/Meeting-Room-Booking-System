

//Al momento del caricamento di tutto il DOM, viene eseguita la funzione initalizeLoginForm che inizializza il form di login e gli associa il listener per la richiesta AJAX
document.addEventListener('DOMContentLoaded', function () {
    initializeLoginForm();
});

function initializeLoginForm() {
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', handleLoginSubmit);
}


//Richiesta AJAX per il login
function handleLoginSubmit(e) {
    e.preventDefault(); // Previene il submit tradizionale del form

    // Recupera i valori dal form
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Mostra lo stato di caricamento
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    loginButton.disabled = true;

    // Nascondi eventuali messaggi precedenti
    const messageDiv = document.getElementById('message');
    messageDiv.style.display = 'none';

    // Crea l'oggetto FormData
    const formData = new FormData();
    formData.append('request', 'login');
    formData.append('username', username);
    formData.append('password', password);

    // Effettua la richiesta AJAX
    fetch('http://localhost:8080/auth', {
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
            // Ripristina il bottone
            loginButton.disabled = false;
            buttonText.style.display = 'inline';



            // Gestisci la risposta
            if (data.success) {
                // Login riuscito
                showMessage(data.message, 'success');

                // Reindirizza alla dashboard dopo 1 secondo
                setTimeout(() => {
                    window.location.href = '/dashboard'; // Modifica con l'URL corretto
                }, 200);
            } else {
                // Login fallito
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            // Ripristina il bottone in caso di errore
            loginButton.disabled = false;
            buttonText.style.display = 'inline';

            showMessage('Errore di connessione. Riprova più tardi.', 'error');
            console.error('Errore:', error);
        });
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.className = `message ${type}`;
    messageDiv.style.display = 'block';

    // Nascondi automaticamente i messaggi di successo dopo 3 secondi
    if (type === 'success') {
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    // Per i messaggi di errore, aggiungi un pulsante per chiuderli
    if (type === 'error') {
        // Rimuovi eventuali pulsanti di chiusura precedenti
        const existingCloseBtn = messageDiv.querySelector('.close-message');
        if (existingCloseBtn) {
            existingCloseBtn.remove();
        }

        // Aggiungi pulsante di chiusura
        const closeBtn = document.createElement('button');
        closeBtn.textContent = '×';
        closeBtn.className = 'close-message';
        closeBtn.onclick = function () {
            messageDiv.style.display = 'none';
        };
        messageDiv.appendChild(closeBtn);
    }
}