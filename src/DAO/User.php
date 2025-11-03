<?php

namespace Src\DAO;


class User
{
    private string $Username;
    private string $Password;
    private bool $isAdmin;

    public function __construct(string $Username, string $Password, bool $amministratore)
    {
        $this->Username = $Username;
        $this->Password = $Password;
        $this->isAdmin = $amministratore;
    }


    public function getUsername(): string
    {
        return $this->Username;
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function isAmministratore(): bool
    {
        return $this->isAdmin;
    }


    public function setUsername(string $Username): void
    {
        $this->Username = $Username;
    }

    public function setPassword(string $Password): void
    {
        $this->Password = $Password;
    }

    public function setAmministratore(bool $amministratore): void
    {
        $this->amministratore = $amministratore;
    }

}

?>