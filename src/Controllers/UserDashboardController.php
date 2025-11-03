<?php

namespace Src\Controllers;




use Src\DAO\Booking;
use Src\Models\BookingModel;
use Src\Models\MeetingRoomModel;
use Src\Utils\Utils;


use PDO;
use DateTime;
use DateTimeZone;

class UserDashboardController
{
    private PDO $db;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function handleRequests(string $request): void
    {

        //verifichiamo che l'utente sia autenticato

        if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)) //Se l'utente non è autenticato
        {
            http_response_code(401);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }



        if (isset($_POST['request'])) {

            $request = $_POST['request'];

            if (isset($_POST['RoomID']) && isset($_POST['TimeSlot']) && isset($_POST['Data'])) {

                $RoomID = $_POST['RoomID'];
                $TimeSlot = $_POST['TimeSlot'];
                $Data = $_POST['Data'];

            } else   //In assenza dei parametri richiesti, errore di richiesta non valide (400)
            {
                http_response_code(400);
                require __DIR__ . '/../Views/ErrorPage.php';
                exit;
            }

            if ($request === 'InsertBooking') //Richiesta di inserimento prenotazione
                $this->handleInsertBookingRequest($RoomID, $TimeSlot, $Data);
            else if ($request === 'DeleteBooking') //Richiesta di cancellazione prenotazione
            {
                $this->handleDeleteBookingRequest($RoomID, $TimeSlot, $Data);
            } else {
                http_response_code(400);
                require __DIR__ . '/../Views/ErrorPage.php';
                exit;
            }
        } else if (isset($_GET['request'])) {


            $request = $_GET['request'];
            if ($request === 'GetTimeSlot') {

                if (isset($_GET['Data']) && isset($_GET['RoomID'])) {
                    $data = $_GET['Data'];
                    $RoomID = $_GET['RoomID'];
                    $this->handleGetAvailableTimeSlotRequest($data, $RoomID);
                } else {
                    http_response_code(400);
                    require __DIR__ . '/../Views/ErrorPage.php';
                    exit;
                }


            } else {
                http_response_code(400);
                require __DIR__ . '/../Views/ErrorPage.php';
                exit;
            }
        } else {
            $this->handleGetUserDashboardPageRequest();
        }


    }




    private function handleGetUserDashboardPageRequest(): void //Invocata in assenza di parametro request, ovvero quando viene semplicemente richiesta la visualizzazione della pagina
    {
        $userBookings = [];
        $availableRooms = [];

        //Recuperiamo i dati necessari alla visualizzazione dagli appositi model
        $meetingRoomModel = new MeetingRoomModel($this->db);
        $availableRooms = $meetingRoomModel->retrieveAll();

        $bookingModel = new BookingModel($this->db);
        $userBookings = $bookingModel->doRetrieveByUser($_SESSION['username']);



        require __DIR__ . "/../Views/UserDashboard.php";
        exit;
    }

    private function handleInsertBookingRequest(int $RoomID, string $TimeSlot, string $data): void //Invocata in presenza di parametro request=
    {

        if (!(Utils::checkParametersForBooking($TimeSlot, $data))) {
            http_response_code(400);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }

        //Superati tutti i controlli, possiamo inserire la prenotazione.
        $bookingModel = new BookingModel($this->db);
        $booking = new Booking($data, $TimeSlot, $RoomID, $_SESSION['username']);
        $result = $bookingModel->doSave($booking);

        //Continua con la restituzione della response in formato JSON per la richiesta AJAX
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Prenotazione effettuata con successo'
            ];

            echo json_encode($response);
        } else {
            $response = [
                'success' => true,
                'message' => 'Errore durante la procedura di prenotazione'
            ];

            echo json_encode($response);
        }
    }

    private function handleDeleteBookingRequest(int $RoomID, string $TimeSlot, string $data): void  //Invocata in presenza di parametro request=
    {
        //Controlliamo che l'utente che effettua la richiesta è effettivamente colui che ha effettuato la prenotazione



        $bookingModel = new BookingModel($this->db);
        $bookingOfUser = $bookingModel->doRetrieveByUser($_SESSION['username']);
        $bookingToCancel = new Booking($data, $TimeSlot, $RoomID, $_SESSION['username']);

        if (!(Utils::checkBookingOwnership($bookingOfUser, $bookingToCancel))) {
            http_response_code(400);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }


        //Se il check è andato a buon fine , eseguiamo l'operazione di cancellazione
        $result = $bookingModel->doDelete($data, $TimeSlot, $RoomID);

        //Risposta:
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Prenotazione correttamente eliminata'
            ];
            echo json_encode($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Errore nella cancellazione della prenotazione'
            ];
            echo json_encode($response);
        }
    }



    private function handleGetAvailableTimeSlotRequest(string $data, int $RoomID): void //Invocata in presenza di parametro request=
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) { //Se la data non è nel formato giusto
            http_response_code(400);
            require __DIR__ . '/../Views/ErrorPage.php';
            exit;
        }


        //Recuperiamo le prenotazioni effettuate sulla room selezionata
        $bookingModel = new BookingModel($this->db);
        $bookingOnSelectedRoom = $bookingModel->doRetrieveByMeetingRoom($RoomID);
        //Estraiamo le fasce orarie disponibili in quella data.
        $availableSlots = Utils::extractAvailableTimeSlots($data, $bookingOnSelectedRoom);



        echo json_encode($availableSlots);

    }




}

?>