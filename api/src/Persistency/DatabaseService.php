<?php

namespace App\Persistency;


final class DatabaseService
{

    private \PDO $pdo;

    public function __construct(array $settings)
    {
        $host = $settings['host'];
        $port = $settings['port'];
        $user = $settings['user'];
        $pw   = $settings['pw'];

        $this->pdo = new \PDO(
			"pgsql:host=$host;port=$port;",
			$user,
			$pw,
			[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
		);
    }

    public function getPDO()
    {
        return $this->pdo;
    }

}