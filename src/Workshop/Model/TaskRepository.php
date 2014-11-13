<?php

namespace Workshop\Model;

class TaskRepository
{
    /** @var \PDO */
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = $this->db->query('SELECT * FROM todo');

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $statement = $this->db->prepare('SELECT * FROM todo WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($title)
    {
        $statement = $this->db->prepare('INSERT INTO todo (title, is_done) VALUES (:title, 0)');
        $statement->bindParam(':title', $title);
        $statement->execute();
    }

    public function close($id)
    {
        $statement = $this->db->prepare('UPDATE todo SET is_done = 1 WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function remove($id)
    {
        $statement = $this->db->prepare('DELETE FROM todo WHERE id = :id LIMIT 1');
        $statement->bindParam(':id', $id);
        $statement->execute();
    }
}
