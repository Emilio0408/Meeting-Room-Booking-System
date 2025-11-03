<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti - Admin Dashboard</title>
    <link rel="stylesheet" href="/CSS/Admin/UserData.css">
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
                <h1 class="page-title">👥 Gestione Utenti</h1>
            </div>
        </div>
    </div>

    <!-- Container principale -->
    <div class="container">
        <!-- Messaggio di feedback -->
        <div id="message" class="message"></div>

        <!-- Statistiche -->
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-label">Totale Utenti</div>
                <div class="stat-value"><?php echo count($users ?? []); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Amministratori</div>
                <div class="stat-value">
                    <?php echo count(array_filter($users ?? [], fn($u) => $u['Amministratore'] === 1)); ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Utenti Standard</div>
                <div class="stat-value">
                    <?php echo count(array_filter($users ?? [], fn($u) => $u['Amministratore'] === 0)); ?>
                </div>
            </div>
        </div>

        <!-- Form per aggiungere utente -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <span>➕</span>
                    Aggiungi Nuovo Utente
                </h2>
            </div>

            <div class="form-container">
                <form id="addUserForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" required placeholder="Inserisci username">
                        </div>

                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" required
                                placeholder="Inserisci password">
                        </div>

                        <div class="form-group">
                            <label for="amministratore">Ruolo *</label>
                            <select id="amministratore" name="amministratore" required>
                                <option value="">-- Seleziona ruolo --</option>
                                <option value="0">Utente Standard</option>
                                <option value="1">Amministratore</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Crea Utente
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabella utenti -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <span>📋</span>
                    Lista Utenti
                </h2>
            </div>

            <?php if (!empty($users)): ?>
                <div class="table-container">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Ruolo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($user['Username']); ?></strong></td>
                                    <td>
                                        <code style="background: #f0f0f0; padding: 0.3rem 0.6rem; border-radius: 4px;">
                                                                                                                            <?php echo htmlspecialchars($user['PASSWORD']); ?>
                                                                                                                        </code>
                                    </td>
                                    <td>
                                        <span
                                            class="badge <?php echo $user['Amministratore'] === 1 ? 'badge-admin' : 'badge-user'; ?>">
                                            <?php echo $user['Amministratore'] === 1 ? 'Admin' : 'User'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <p>Nessun utente presente nel sistema</p>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <script src="/JS/Admin/UserData.js"></script>

</body>

</html>