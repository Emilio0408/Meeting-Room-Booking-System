document.addEventListener('DOMContentLoaded', function () {
    const addUserForm = document.getElementById('addUserForm');
    const messageDiv = document.getElementById('message');
    const usersTable = document.querySelector('.users-table tbody');
    const emptyState = document.querySelector('.empty-state');
    const statsValues = document.querySelectorAll('.stat-value');

    addUserForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Recupera i valori dal form
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const amministratore = document.getElementById('amministratore').value;

        // Validazione base
        if (!username || !password || amministratore === '') {
            showMessage('Per favore, compila tutti i campi obbligatori', 'error');
            return;
        }

        // Crea i parametri per la richiesta
        const params = new URLSearchParams();
        params.append('request', 'InsertUser');
        params.append('Username', username);
        params.append('Password', password);
        params.append('Admin', amministratore);

        // Effettua la richiesta AJAX POST
        fetch('/adminDashboard', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params.toString()
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore di rete');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    addUserForm.reset();

                    // Aggiungi il nuovo utente alla tabella
                    addUserToTable(username, password, amministratore);

                    // Aggiorna le statistiche
                    updateStats(amministratore);

                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                showMessage('Errore di connessione al server', 'error');
            });
    });

    function addUserToTable(username, password, amministratore) {
        // Se c'è lo stato vuoto, nascondilo
        if (emptyState) {
            emptyState.style.display = 'none';
        }

        // Se la tabella non esiste, creala
        if (!usersTable) {
            createTableAndAddUser(username, password, amministratore);
            return;
        }

        // Crea la nuova riga
        const newRow = document.createElement('tr');

        // Cella Username
        const usernameCell = document.createElement('td');
        const usernameStrong = document.createElement('strong');
        usernameStrong.textContent = username;
        usernameCell.appendChild(usernameStrong);

        // Cella Password
        const passwordCell = document.createElement('td');
        const passwordCode = document.createElement('code');
        passwordCode.style.cssText = 'background: #f0f0f0; padding: 0.3rem 0.6rem; border-radius: 4px;';
        passwordCode.textContent = password;
        passwordCell.appendChild(passwordCode);

        // Cella Ruolo
        const roleCell = document.createElement('td');
        const roleBadge = document.createElement('span');
        roleBadge.className = amministratore === '1' ? 'badge badge-admin' : 'badge badge-user';
        roleBadge.textContent = amministratore === '1' ? 'Admin' : 'User';
        roleCell.appendChild(roleBadge);

        // Assembla la riga
        newRow.appendChild(usernameCell);
        newRow.appendChild(passwordCell);
        newRow.appendChild(roleCell);

        // Aggiungi la riga alla tabella (in cima)
        usersTable.insertBefore(newRow, usersTable.firstChild);
    }

    function createTableAndAddUser(username, password, amministratore) {
        // Se non esiste la tabella, crea tutta la struttura
        const tableContainer = document.querySelector('.table-container') ||
            document.querySelector('.section:last-child');

        // Rimuovi lo stato vuoto se presente
        if (emptyState) {
            emptyState.remove();
        }

        // Crea il container della tabella se non esiste
        let tableContainerDiv = document.querySelector('.table-container');
        if (!tableContainerDiv) {
            tableContainerDiv = document.createElement('div');
            tableContainerDiv.className = 'table-container';
            tableContainer.appendChild(tableContainerDiv);
        }

        // Crea la tabella
        const table = document.createElement('table');
        table.className = 'users-table';

        // Crea l'header della tabella
        const thead = document.createElement('thead');
        thead.innerHTML = `
    <tr>
        <th>Username</th>
        <th>Password</th>
        <th>Ruolo</th>
    </tr>
    `;

        // Crea il body della tabella
        const tbody = document.createElement('tbody');

        // Aggiungi la tabella al container
        table.appendChild(thead);
        table.appendChild(tbody);
        tableContainerDiv.appendChild(table);

        // Ora aggiungi l'utente
        addUserToTable(username, password, amministratore);
    }

    function updateStats(amministratore) {
        // Aggiorna il totale utenti
        if (statsValues[0]) {
            const currentTotal = parseInt(statsValues[0].textContent) || 0;
            statsValues[0].textContent = currentTotal + 1;
        }

        // Aggiorna il contatore degli admin o utenti standard
        if (amministratore === '1' && statsValues[1]) {
            const currentAdmins = parseInt(statsValues[1].textContent) || 0;
            statsValues[1].textContent = currentAdmins + 1;
        } else if (amministratore === '0' && statsValues[2]) {
            const currentUsers = parseInt(statsValues[2].textContent) || 0;
            statsValues[2].textContent = currentUsers + 1;
        }
    }

    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = 'message ' + type;
        messageDiv.style.display = 'block';

        // Nascondi il messaggio dopo 5 secondi
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
});