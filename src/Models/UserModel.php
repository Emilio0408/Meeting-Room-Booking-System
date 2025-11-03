<?php

namespace Src\Models;


use Src\DAO\User;
use PDO;
use PDOException;

class UserModel
{
    private PDO $connection;
    private const TABLE = "Utente";



    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /*
        Metodo per l'inserimento di un utente all'interno del sistema.
        Parametri in ingresso: User $user (oggetto utente contenente i dati da inserire)
        Restituisce true se l'inserimento va a buon fine, false altrimenti.

    */
    public function doSave(User $user): bool
    {
        try {
            $query = "INSERT INTO " . self::TABLE . " (Username,PASSWORD,Amministratore) VALUES (:username,:password,:amministratore)";
            $statement = $this->connection->prepare($query);

            if ($user->isAmministratore())
                $amministratore = 1;
            else
                $amministratore = 0;

            $statement->execute([
                ':username' => $user->getUsername(),
                ':password' => $user->getPassword(),
                ':amministratore' => $amministratore
            ]);
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }

        return true;
    }



    /*
        Metodo per prelevare i dati relativi a tutti gli utenti all'interno del sistema.
        Parametri in ingresso: nessuno.

        Se l'operazione va a buon fine, restituisce una array di array associativi con la seguente struttura:

        [   
            ["Username" => "X" , "Password" => "X" , "Amministratore" => 0/1]
            ["Username" => "X" , "Password" => "X" , "Amministratore" => 0/1]
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

    /*
        Metodo per prelevare i dati relativi ad un utente con un certo username all'interno del sistema.
        Parametri in ingresso: string $username (username dell'utente).

        Se l'operazione va a buon fine, restituisce un array associativo con questa struttura

        [
            ["Username" => "X" , "PASSWORD" => "X" , "Amministratore" => 0/1]
        ]

        altrimenti restituisce false.

    */


    public function retrieveByUsername(string $Username): array|false
    {
        try {

            $query = "SELECT * FROM " . self::TABLE . " WHERE Username = :username";
            $statement = $this->connection->prepare($query);
            $statement->execute([":username" => $Username]);

        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            return false;
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }


}

?>