<?php

declare(strict_types=1);

namespace database;

use PDO;

class Database
{
    protected string $username = "root";
    protected string $password = "";
    protected string $dsn = 'mysql:host=localhost;dbname=customer_db';
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO($this->dsn, $this->username, $this->password);
    }

    /**
     * @param array<string, string> $input
     *
     * @return bool
     */
    public function save(array $input): bool
    {
        // Sanitize db input
        $sql = "INSERT INTO customer
                    (first_name, last_name, email, phone_number, company, message)
            VALUES
            (:first_name, :last_name, :email, :phone_number, :company, :message)"; #NOSONAR

        try {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $this->db->prepare($sql);
            $statement->bindParam('email', $input['email']);
            $statement->bindParam('phone_number', $input['phone_number']);
            $statement->bindParam('last_name', $input['last_name']);
            $statement->bindParam('first_name', $input['first_name']);
            $statement->bindParam('company', $input['company']);
            $statement->bindParam('message', $input['message']);
            return $statement->execute();
        } catch (\Throwable $throwable) {
            error_log($throwable->getMessage());
            return false;
        }
    }

    public function exists(string $email): bool
    {
        $sql = "SELECT * FROM customer WHERE email = :email";

        try {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $this->db->prepare($sql);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetchAll();
            return \count($result) > 0;
        } catch (\Throwable $throwable) {
            error_log($throwable->getMessage());
            return false;
        }

    }

}
