<?php
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Router.php';
require __DIR__ . '/../src/Controllers/AdminDashboardController.php';
require __DIR__ . '/../src/Controllers/UserDashboardController.php';
require __DIR__ . '/../src/Controllers/AuthController.php';
require __DIR__ . "/../src/Models/MeetingRoomModel.php";
require __DIR__ . "/../src/Models/BookingModel.php";
require __DIR__ . "/../src/Models/UserModel.php";
require __DIR__ . "/../src/DAO/Booking.php";
require __DIR__ . "/../src/DAO/User.php";
require __DIR__ . "/../src/DAO/MeetingRoom.php";
require __DIR__ . "/../src/Utils/Utils.php";



use Src\Database;
use Src\Router;

$db = Database::getConnection();
$router = new Router($db);
$router->route();

try {


} catch (Throwable $e) {
    http_response_code(500);
    require __DIR__ . '/../src/Views/ErrorPage.php';
}






?>