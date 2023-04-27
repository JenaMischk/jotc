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

        $page = isset($params['page']) ? $params['page'] : 0;
        $pageSize = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $sort = isset($params['sort']) ? $params['sort'] : 'asc';
        $sortBy = isset($params['sortBy']) ? $params['sortBy'] : 'id';

        $dateBegin = isset($params['dateBegin']) ? $params['dateBegin'] : null;
        $dateEnd = isset($params['dateEnd']) ? $params['dateEnd'] : null;
        $email = isset($params['email']) ? $params['email'] : null;

        $whereInsert = '';
        $whereInsert .= $dateBegin ? "AND date > to_timestamp(:dateBegin) " : '';
        $whereInsert .= $dateEnd ? "AND date < to_timestamp(:dateEnd) " : '';
        $whereInsert .= $email ? "AND user_id IN (SELECT id FROM users WHERE email = :email) " : '';

        $orderByInsert = '';
        //TODO: this statement is open to SQL injection; fix it
        //Had to resort to concatenating values for the sort as I hit a variable binding bug
        $orderByInsert .= $sortBy ? "ORDER BY $sortBy " : '';
        $orderByInsert .= $sort === 'desc' ? "DESC " : '';

        $sql = "SELECT *, count(*) OVER() AS total_rows
                FROM submissions
                WHERE true
                $whereInsert
                $orderByInsert
                LIMIT :pageSize
                OFFSET :pageOffset
        ";

        $stmt = $this->database->getPDO()->prepare($sql);

        var_dump($stmt->queryString);

        if($dateBegin) $stmt->bindValue(':dateBegin', $dateBegin);
        if($dateEnd) $stmt->bindValue(':dateEnd', $dateEnd);
        if($email) $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pageSize', $pageSize);
        $stmt->bindValue(':pageOffset', $page * $pageSize);

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