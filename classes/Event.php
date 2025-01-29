<?php

class Event
{
    private $conn;
    private $table_name = "events";

    public $id;
    public $name;
    public $description;
    public $date_time;
    public $user_limit;
    public $user_id;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // public function readAll()
    // {
    //     $query = "SELECT * FROM ".$this->table_name." WHERE user_id=:user_id ORDER BY id DESC";
    //     $stmt = $this->conn->prepare($query);
    //
    //     $stmt->bindParam(":user_id", $this->user_id);
    //
    //     $stmt->execute();
    //
    //     return $stmt;
    // }

    public function create()
    {
        $query = "INSERT INTO ".$this->table_name." SET name=:name, description=:description, date_time=:date_time, user_limit=:user_limit, user_id=:user_id, created_at=:created_at, updated_at=:updated_at";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->date_time = htmlspecialchars(strip_tags($this->date_time));
        $this->user_limit = htmlspecialchars(strip_tags($this->user_limit));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":date_time", $this->date_time);
        $stmt->bindParam(":user_limit", $this->user_limit);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readOne(){
        $query = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE ".$this->table_name." SET name=:name, description=:description, date_time=:date_time, user_limit=:user_limit, updated_at=:updated_at WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->date_time = htmlspecialchars(strip_tags($this->date_time));
        $this->user_limit = htmlspecialchars(strip_tags($this->user_limit));
        $this->updated_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":date_time", $this->date_time);
        $stmt->bindParam(":user_limit", $this->user_limit);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":updated_at", $this->updated_at);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM ".$this->table_name." WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}