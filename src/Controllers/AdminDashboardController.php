<?php

namespace Src\Controllers;
use DateTime;
use DateTimeZone;
use Src\Models\BookingModel;
use Src\Models\UserModel;
use Src\Models\MeetingRoomModel;
use Src\DAO\User;
use Src\DAO\MeetingRoom;
use Src\Utils\Utils;


use PDO;

class AdminDashboardController
{
    private PDO $db;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function handleRequests(string $request): void
    {
        //Verifichiamo che l'utente che prova ad accedere sia effettivamente admin
        if (!(isset($_SESSION['Admin'])) || !($_SESSION['Admin'])) {
            http_response_code(401);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }




        //Sistema di smistamento delle richieste

        if (isset($_POST['request'])) {

            $request = $_POST['request'];
            if ($request == 'InsertUser') {
                if (isset($_POST['Username']) && isset($_POST['Password']) && isset($_POST['Admin']))
                    $this->handleInsertUserRequest($_POST['Username'], $_POST['Password'], $_POST['Admin']);
                else {
                    http_response_code(400);
                    require __DIR__ . '/../Views/ErrorPage.php';
                    exit;
                }
            } else if ($request == 'InsertMeetingRoom') {
                if (isset($_POST['Capienza']) && isset($_POST['Edificio']) && isset($_POST['Piano']))
                    $this->handleInsertMeetingRoomRequest($_POST['Capienza'], $_POST['Edificio'], (int) $_POST['Piano']);
            } else if ($request == 'DeleteMeetingRoom') {
                if (isset($_POST['IDRoom']))
                    $this->handleDeleteMeetingRoomRequest($_POST['IDRoom']);

            } else if ($request == 'UpdateMeetingRoom') {

                if (isset($_POST['IDRoom'])) {
                    $capienza = $_POST['Capienza'] ?? null;
                    $edificio = $_POST['Edificio'] ?? null;
                    $piano = $_POST['Piano'] ?? null;

                    $this->handleUpdateMeetingRoomRequest((int) ($_POST['IDRoom']), $capienza, $piano, $edificio);
                }

            } else {
                http_response_code(401);
                require __DIR__ . '/../Views/ErrorPage.php';
                exit;
            }
        } else {
            $request = $_SERVER["REQUEST_URI"];
            $path = parse_url($request, component: PHP_URL_PATH);


            if ($path === "/adminDashboard/users") {
                $this->handleGetUserDataPageRequest();
            } else if ($path === "/adminDashboard/meetingrooms") {
                $this->handleGetMeetingRoomDataPageRequest();
            } else if ($path === "/adminDashboard/bookings") {
                $this->handleGetBookingDataPageRequest();
            } else if ($path === "/adminDashboard") {
                $this->handleGetAdminDashboardPageRequest();
            }
        }






    }



    private function handleGetAdminDashboardPageRequest(): void
    {
        require __DIR__ . "/../Views/Admin/AdminDashboard.php";
        exit;
    }


    private function handleGetUserDataPageRequest(): void
    {
        $userModel = new UserModel($this->db);
        $users = $userModel->retrieveAll();
        require __DIR__ . "/../Views/Admin/UserData.php";
        exit;
    }

    private function handleGetMeetingRoomDataPageRequest(): void
    {

        //Recuperiamo i dati di tutte le meeting rooms
        $meetingRoomModel = new MeetingRoomModel($this->db);
        $meetingRooms = $meetingRoomModel->retrieveAll();

        //Recuperiamo tutte le prenotazioni sulle meeting rooms effettuate nella data odierna
        $bookingModel = new BookingModel($this->db);
        $todayBookings = [];

        foreach ($meetingRooms as $room)
            $todayBookings[] = Utils::filterTodayBookings($bookingModel->doRetrieveByMeetingRoom(IDRoom: $room['ID']));



        require __DIR__ . "/../Views/Admin/MeetingRoomsData.php";
        exit;
    }

    private function handleGetBookingDataPageRequest(): void
    {
        $bookingModel = new BookingModel($this->db);
        $AllBookings = $bookingModel->doRetrieveAll();


        require __DIR__ . "/../Views/Admin/BookingsData.php";
        exit;
    }

    private function handleInsertUserRequest(string $username, string $password, bool $amministratore): void
    {

        $userModel = new UserModel($this->db);


        $checkUser = $userModel->retrieveByUsername($username);
        if (!empty($checkUser)) //Se l'utente esiste già
        {
            $response = [
                'success' => false,
                'message' => 'Username già esistente'
            ];
            echo json_encode($response);
            exit;
        }


        //Se vengono superati tutti i controlli, inseriamo l'utente.
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($username, $passwordHash, $amministratore);
        $result = $userModel->doSave($user);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Utente inserito con successo'
            ];
            echo json_encode($response);
        } else {
            $response = [
                'success' => false,
                'message' => "Errore nell'inserimento dell'utente"
            ];
            echo json_encode($response);
        }
    }

    private function handleInsertMeetingRoomRequest(int $capienza, string $edificio, int $piano): void
    {
        $meetingRoomModel = new MeetingRoomModel($this->db);
        $newMeetingRoom = new MeetingRoom($edificio, $piano, $capienza);
        $result = $meetingRoomModel->doSave($newMeetingRoom);


        if ($result) {
            $response = [
                "success" => true,
                'message' => 'Meeting Room inserita correttamente',
                'newRoomId' => $result
            ];

            echo json_encode($response);
            exit;
        } else {
            $response = [
                "success" => false,
                'message' => "Errore nell'inserimento della meeting room"
            ];

            echo json_encode($response);
            exit;
        }
    }

    private function handleDeleteMeetingRoomRequest(int $RoomID): void
    {
        $meetingRoomModel = new MeetingRoomModel($this->db);
        $result = $meetingRoomModel->doDelete($RoomID);



        if ($result) {
            $response = [
                "success" => true,
                'message' => 'Meeting Room eliminata correttamente'
            ];

            echo json_encode($response);
            exit;
        } else {
            $response = [
                "success" => false,
                'message' => "Errore: non puoi cancellare una room con prenotazioni attive"
            ];

            echo json_encode($response);
            exit;
        }

    }


    private function handleUpdateMeetingRoomRequest(int $RoomID, $capienza, $piano, $edificio): void
    {
        $meetingRoomModel = new MeetingRoomModel($this->db);
        $result = $meetingRoomModel->doUpdate($RoomID, $capienza, $piano, $edificio);


        if ($result) {
            $response = [
                "success" => true,
                'message' => 'Meeting Room modificata correttamente'
            ];

            echo json_encode($response);
            exit;
        } else {
            $response = [
                "success" => false,
                'message' => "Errore nella modifica della meeting room"
            ];

            echo json_encode($response);
            exit;
        }
    }





}

?>