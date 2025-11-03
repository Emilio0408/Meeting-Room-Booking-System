<?php

namespace Src\Models;



use Src\DAO\MeetingRoom;
use PDO;
use PDOException;


class MeetingRoomModel
{
    private PDO $connection;
    private const TABLE = "SalaRiunioni";


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    /*
        Metodo per l'inserimento di una meeting room all'interno del sistema.
        Parametri in ingresso: MeetingRoom $meetingRoom (oggetto meeting room contenente i dati da inserire)
        Restituisce l'ID della nuova meeting room se l'inserimento va a buon fine, 0 altrimenti.

    */

    public function doSave(MeetingRoom $meetingRoom): int
    {

        try {
            $query = "INSERT INTO " . self::TABLE . " (Capienza,Piano,Edificio) VALUES (:capienza, :piano , :edificio)";
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':capienza' => $meetingRoom->getCapienza(),
                ':piano' => $meetingRoom->getPiano(),
                ':edificio' => $meetingRoom->getEdificio()
            ]);

        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return 0;
        }


        return (int) $this->connection->lastInsertId();
    }


    /*
        Metodo per la cancellazione di una meeting room dal sistema.
        Parametri in ingresso : int $idRoom (l'id della room da cancellare)
        Restituisce true se l'operazione di cancellazione va a buon fine, false altrimenti.

    */


    public function doDelete(int $idRoom): bool
    {
        try {
            $query = "DELETE FROM " . self::TABLE . " WHERE ID = :id";
            $statement = $this->connection->prepare($query);
            $statement->execute([":id" => $idRoom]);
        } catch (PDOException $e) {
            return false;
        }

        return true;

    }


    /*
        Metodo per la modifica dei valori degli attributi di una certa meeting room del sistema.
        Parametri in ingresso : int $IDroom (obbligatorio), $capienza (opzionale), $piano (opzionale), $edificio(opzionale);
        Restituisce true se l'operazione è andata a buon fine.
        Restituisce false se non vengono passati parametri per la modifica o se l'operazione non va a buon fine.

    */


    public function doUpdate(int $IDroom, $capienza = null, $piano = null, $edificio = null): bool
    {
        if ($capienza === null && $edificio === null && $piano === null) //Caso in cui non vengono passati parametri per la modifica
            return false;

        $updates = [];
        $values = [":room_id" => $IDroom];

        if ($capienza !== null) {
            $updates[] = "Capienza = :capienza";
            $values[":capienza"] = $capienza;
        }

        if ($piano !== null) {
            $updates[] = "Piano = :piano";
            $values[":piano"] = $piano;
        }

        if ($edificio !== null) {
            $updates[] = "Edificio = :edificio";
            $values[":edificio"] = $edificio;
        }

        $sql = "UPDATE " . self::TABLE . " SET " . implode(', ', $updates) . ' WHERE ID = :room_id';

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($values);
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }

        return true;
    }


    /*
        Metodo per prelevare i dati relativi a tutte le meeting rooms all'interno del sistema.
        Parametri in ingresso: nessuno.

        Se l'operazione va a buon fine, restituisce una array di array associativi con la seguente struttura:

        [  
            ["ID" => "X" , "Capienza" => X , "Piano" => "X" , "Edificio" => "X"]
            ["ID" => "X" , "Capienza" => X , "Piano" => "X" , "Edificio" => "X"]
        ]

        Altrimenti restituisce false;


    */
    public function retrieveAll(): array|false
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