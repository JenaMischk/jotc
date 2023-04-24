<?php

namespace App\Domain\User\Repository;

use App\Persistency\DatabaseService;


final class UserRepository
{

    private DatabaseService $database;

    public function __construct(DatabaseService $database)
    {
        $this->database = $database;
    }

    public function getUser(array $user)
    {
        $email = $user['email'];
        $firstName = $user['firstName'];
        $lastName = $user['lastName'];
        $birthDate = $user['birthDate'];

        $sql = 'SELECT *
                FROM users
                WHERE email = :email
                AND first_name = :firstName
                AND last_name = :lastName
                AND birth_date = :birthDate
        ';
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':birthDate', $birthDate);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    public function createUser(array $user)
    {
        $email = $user['email'];
        $firstName = $user['firstName'];
        $lastName = $user['lastName'];
        $birthDate = $user['birthDate'];

        $this->database->getPDO()->beginTransaction();

        $sql = 'INSERT INTO users (email, first_name, last_name, birth_date)
                VALUES (:email, :firstName, :lastName, :birthDate)
        ';
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':birthDate', $birthDate);
        $stmt->execute();

        $sql = 'SELECT *
                FROM users
                WHERE id = :id
        ';
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $this->database->getPDO()->lastInsertId('users_id_seq'));
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->database->getPDO()->commit();

        return $user;

    }

}