<?php

namespace Src\DAO;


class MeetingRoom
{
    private string $edificio;
    private int $piano;
    private int $capienza;


    public function __construct(string $edificio, int $piano, int $capienza)
    {
        $this->edificio = $edificio;
        $this->piano = $piano;
        $this->capienza = $capienza;
    }


    public function getEdificio(): string
    {
        return $this->edificio;
    }

    public function getPiano(): int
    {
        return $this->piano;
    }

    public function getCapienza(): int
    {
        return $this->capienza;
    }


    public function setID(int $id): void
    {
        $this->id = $id;
    }

    public function setEdificio(string $edificio): void
    {
        $this->edificio = $edificio;
    }

    public function setPiano(int $piano): void
    {
        $this->piano = $piano;
    }

    public function setCapienza(int $capienza): void
    {
        $this->capienza = $capienza;
    }

}

?>