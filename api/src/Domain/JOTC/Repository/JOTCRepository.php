<?php

namespace App\Domain\JOTC\Repository;

use App\Persistency\DatabaseService;


final class JOTCRepository
{

    private DatabaseService $database;

    public function __construct(DatabaseService $database)
    {
        $this->database = $database;
    }

    public function getSubmissions(array $params)
    {
        $dateBegin = isset($params['dateBegin']) ? $params['dateBegin'] : null;
        $dateEnd = isset($params['dateEnd']) ? $params['dateEnd'] : null;
        $email = isset($params['email']) ? $params['email'] : null;

        $whereInsert = '';
        $whereInsert .= $dateBegin ? "AND date > to_timestamp(:dateBegin) " : '';
        $whereInsert .= $dateEnd ? "AND date < to_timestamp(:dateEnd) " : '';
        $whereInsert .= $email ? "AND user_id IN (SELECT id FROM users WHERE email = :email) " : '';

        $sql = "SELECT *
                FROM submissions
                WHERE true
                $whereInsert
        ";
        $stmt = $this->database->getPDO()->prepare($sql);
        if($dateBegin) $stmt->bindValue(':dateBegin', $dateBegin);
        if($dateEnd) $stmt->bindValue(':dateEnd', $dateEnd);
        if($email) $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createSubmission(array $submission)
    {
        $userId = $submission['userId'];
        $input = $submission['input'];
        $output = $submission['output'];

        $sql = 'INSERT INTO submissions (user_id, input, output, date)
                VALUES (:user_id, :input, :output, current_timestamp);
        ';
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':input', $input);
        $stmt->bindValue(':output', $output);
        $stmt->execute();
    }

}