<?php


namespace Src;
use PDO;
use Src\Controllers\AdminDashboardController;
use Src\Controllers\AuthController;
use Src\Controllers\UserDashboardController;


class Router
{
    private PDO $db;
    private array $routes = [
        '/', //Accessibile a tutti gli utenti anche a quelli che non hanno ancora effettuato il login.
        '/adminDashboard',
        '/adminDashboard/users',
        '/adminDashboard/meetingrooms',
        '/adminDashboard/bookings',
        '/dashboard', //Accessibile solo a chi è autenticato
        '/auth' //Serve unicamente ad effettuare richieste di login e logout, quindi non è un URL mappato ad una pagina effettiva
    ];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function route(): void
    {
        session_start(); //Avviamo la sessione o recuperiamo quella già esistente

        $request = $_SERVER["REQUEST_URI"];
        $path = parse_url($request, PHP_URL_PATH);

        if ($path === $this->routes[0]) //Richiesta della pagina iniziale
        {
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)  //Se l'utente è già loggato, la pagina iniziale è la dashboard
            {
                header("Location: /dashboard");
                exit;
            } else //Altrimenti la pagina iniziale è il form di login 
            {
                require __DIR__ . '/Views/login.php';
                exit;
            }

        } else if ($path === $this->routes[1] || $path === $this->routes[2] || $path === $this->routes[3] || $path === $this->routes[4]) //Richiesta operazione per admin
        {
            $controller = new AdminDashboardController($this->db);
            $controller->handleRequests($request);
        } else if ($path === $this->routes[5]) {
            $controller = new UserDashboardController($this->db);
            $controller->handleRequests($request);
        } else if ($path === $this->routes[6]) {
            $controller = new AuthController($this->db);
            $controller->handleRequests();
        } else {
            http_response_code(404);
            require __DIR__ . '/Views/ErrorPage.php';
        }


    }

}


?>