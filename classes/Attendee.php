<?php

class Attendee
{
    private $conn;
    private $table_name = "attendees";

    public $id;
    public $event_id;
    public $name;
    public $email;
    public $phone;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET event_id=:event_id, name=:name, email=:email, phone=:phone, created_at=:created_at, updated_at=:updated_at";
        $stmt = $this->conn->prepare($query);

        $this->event_id = htmlspecialchars(strip_tags($this->event_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":event_id", $this->event_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function attendees()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE event_id = :event_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":event_id", $this->event_id);
        $stmt->execute();

        return $stmt;
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