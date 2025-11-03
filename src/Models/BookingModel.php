<?php

namespace Src\Models;



use Src\DAO\Booking;
use PDO;
use PDOException;



class BookingModel
{
    private PDO $connection;
    private const TABLE = "Prenotazione";


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /*
        Metodo per l'inserimento di una prenotazione all'interno del sistema.
        Parametri in ingresso: Booking $booking (oggetto booking contenente i dati della prenotazione dainserire)
        Restituisce true se l'inserimento va a buon fine, false altrimenti.
    */
    public function doSave(Booking $booking): bool
    {
        try {
            $query = "INSERT INTO " . self::TABLE . " (Data,FasciaOraria,IDSala,Utente) VALUES (:data,:fasciaOraria,:idsala,:utente)";
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ":data" => $booking->getData(),
                ":fasciaOraria" => $booking->getTimeslot(),
                ":idsala" => $booking->getIDRoom(),
                ":utente" => $booking->getUser()
            ]);

        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }

        return true;
    }


    /*
        Metodo per la cancellazione di una prenotazione dal sistema.
        Parametri in ingresso: string $aata (data della prenotazione che si vuole cancellare), string $timeSlot (fascia oraria della prenotazione che si vuole cancellare), int $IDRoom (id della sala prenotata)
        Restituisce true se la cancellazione va a buon fine, false altrimenti.
    */


    public function doDelete(string $data, string $timeSlot, int $IDRoom): bool
    {
        try {
            $query = "DELETE FROM " . self::TABLE . " WHERE DATA = :data AND FasciaOraria = :fasciaoraria AND IDSala = :idSala";
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ":data" => $data,
                ":fasciaoraria" => $timeSlot,
                ":idSala" => $IDRoom
            ]);

        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }


        return true;

    }

    /*
        Metodo per recuperare le prenotazioni effettuate da un certo utente.
        Parametri in ingresso : string $username (l'username dell'utente di cui si vogliono recuperare le prenotazioni)
        Se l'operazione va a buon fine, restituisce un array di array associativi con la seguente struttura

        [
            ["DATA" => "X" , "FasciaOraria" => "X" , "IDSala" => X, "Utente" => "X"],
            ["DATA" => "X" , "FasciaOraria" => "X" , "IDSala" => X, "Utente" => "X"]
        ]

        Altrimenti , restituisce false.

    */

    public function doRetrieveByUser(string $username): array|false
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE Utente = :username";
            $statement = $this->connection->prepare($query);
            $statement->execute([":username" => $username]);
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }


        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
        Metodo per recuperare le prenotazioni effettuate su una certa meeting room.
        Parametri in ingresso : int $IDRoom (ID della sala di cui si vogliono recuperare le prenotazioni)
        Se l'operazione va a buon fine, restituisce un array di array associativi con la seguente struttura

        [
            ["DATA" => "X" , "FasciaOraria" => "X" , "IDSala" => X, "Utente" => "X"],
            ["DATA" => "X" , "FasciaOraria" => "X" , "IDSala" => X, "Utente" => "X"]
        ]

        Altrimenti , restituisce false.

    */

    public function doRetrieveByMeetingRoom(string $IDRoom): array|false
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE IDSala = :idsala";
            $statement = $this->connection->prepare($query);
            $statement->execute([":idsala" => $IDRoom]);
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }


        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }



    /*
        Metodo per recuperare tutte le prenotazioni effettuate.
        Parametri in ingresso : nessuno
        Se l'operazione va a buon fine, restituisce un array di array associativi con la seguente struttura

        [
            ["DATA" => "X" , "FasciaOraria" => "X" , "IDSala" => X, "Utente" => "X"],
            ["DATA" => "X" , "FasciaOraria" => "X" , "IDSala" => X, "Utente" => "X"]
        ]

        Altrimenti , restituisce false.

    */

    public function doRetrieveAll(): array|false
    {
        try {
            $query = "SELECT * FROM " . self::TABLE;
            $statement = $this->connection->prepare($query);
            $statement->execute();
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }


        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>