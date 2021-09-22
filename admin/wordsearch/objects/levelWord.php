<?php
class LevelWord{
  
    // database connection and table name
    private $conn;
    private $table_name = "level_words";
  
    // object properties
    public $id;
    public $level_id;
    public $word_id;

    public $category_id;
    public $name;
    public $description;
    public $status;
    public $deleted;
    public $created;
    public $modified;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getLevelWords()
    {   
        // select all query
        $query = "SELECT
                    lw.*
                FROM
                    " . $this->table_name . " lw,
                WHERE
                    lw.level_id = :level_id
                ORDER BY
                    w.name ASC";
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // bind id of product to be updated
        $statement->bindParam(":level_id", $this->level_id);

        // execute query
        $statement->execute();
    
        return $statement;
    }
}
