<?php

namespace Src\Controllers;



use Src\Models\UserModel;


use PDO;

class AuthController
{
    private PDO $db;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function handleRequests(): void
    {

        /* 
            Questo controller accetta solo richieste POST derivanti dal form della pagina login.php o dal bottone di logout nella dashboard.
            Nel caso in cui la richiesta non dovesse contenere il parametro request inviato come POST, significa che la richiesta non è stata
            effettuata da nessuna delle due fonti accettate, pertanto si dà un error 404.
        */

        if (isset($_POST['request']))
            $request = $_POST['request'];
        else {
            http_response_code(400);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }


        if ($request === 'login') {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $this->handleLoginRequest($username, $password);
            } else {
                http_response_code(400);
                require __DIR__ . '/../Views/ErrorPage.php';
            }

        } else if ($request === 'logout') {
            $this->handleLogoutRequest();
        } else {
            http_response_code(400);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }


    }


    private function handleLoginRequest(string $username, string $password): void
    {

        $UserModel = new UserModel($this->db);
        $user = $UserModel->retrieveByUsername($username);



        if (empty($user)) { //Se la funzione di recupero dell'utente restituisce un array vuoto, allora significa che l'username è sbagliato
            $response = [
                'success' => false,
                'message' => "Username inesistente"
            ];

            header("Content-Type: application/json");
            echo json_encode($response);
        } else {
            if (password_verify($password, $user[0]["PASSWORD"])) { //Se l'utente esiste e la sua password coincide con quella inserita, allora consentiamo l'accesso e inizializziamo la sessione

                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;
                $_SESSION['Admin'] = $user[0]['Amministratore'];

                $response = [
                    'success' => true,
                    'message' => 'Login effettuato con successo'
                ];

                echo json_encode($response);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Password errata'
                ];

                echo json_encode($response);
            }
        }



    }



    private function handleLogoutRequest(): void
    {

        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            session_unset();
            session_destroy();
            header("Location: /");
            exit;
        } else {
            http_response_code(401);
            require __DIR__ . '/Views/ErrorPage.php';
        }
    }



}

?>