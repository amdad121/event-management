<?php

include_once 'Database.php';

class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register()
    {
        $query = "INSERT INTO ".$this->table_name." SET username=:username, email=:email, password=:password, created_at=:created_at, updated_at=:updated_at";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function login()
    {
        $query = "SELECT id, password FROM ".$this->table_name." WHERE email=:email";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                return true;
            }
            return false;
        }
        return false;
    }

    public function emailExists()
    {
        $query = "SELECT id FROM ".$this->table_name." WHERE email=:email";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);

        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }

        return false;
    }
}