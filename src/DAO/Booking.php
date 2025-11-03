<?php

namespace Src\DAO;

class Booking
{
    private string $data;
    private string $timeslot;
    private int $IDRoom;
    private string $User;


    public function __construct(string $data, string $timeslot, int $IDRoom, string $User)
    {
        $this->data = $data;
        $this->timeslot = $timeslot;
        $this->IDRoom = $IDRoom;
        $this->User = $User;
    }

    //Metodi getter

    public function getData(): string
    {
        return $this->data;
    }
    public function getTimeslot(): string
    {
        return $this->timeslot;
    }

    public function getIDRoom(): int
    {
        return $this->IDRoom;
    }

    public function getUser(): string
    {
        return $this->User;
    }


    //Metodi setter

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function setTimeslot(string $timeslot): void
    {
        $this->timeslot = $timeslot;
    }

    public function setIDRoom(int $IDRoom): void
    {
        $this->IDRoom = $IDRoom;
    }

    public function setUser(string $User): void
    {
        $this->User = $User;
    }




}

?>