<?php
class Category{
  
    // database connection and table name
    private $conn;
    private $table_name = "categories";
  
    // object properties
    public $id;
    public $name;
    public $deleted;
    public $created;
    public $modified;
    public $totalWords;
    public $totalLevels;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // get categories
    function getCategories()
    {   
        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "c.deleted=" . $deleted;

        // select all query
        $query = "SELECT
                    c.id, c.name, COUNT(w.id) AS totalWords, COUNT(l.id) AS totalLevels
                FROM
                    " . $this->table_name . " c
                    LEFT JOIN
                    words w
                        ON c.id = w.category_id
                    LEFT JOIN
                    levels l
                        ON l.id = w.level_id
                WHERE 
                    " . $stringDeleted . "
                GROUP BY
                    c.id
                ORDER BY
                    c.name ASC";
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    // read questions
    function getByName()
    {
        // query to read single record
        $query = "SELECT c.*
                FROM " . $this->table_name . " c
                WHERE c.name=:name
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(":name", $this->name);

        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
    }

    // read questions
    function getById()
    {
        // query to read single record
        $query = "SELECT c.id as id, c.name as name
                FROM " . $this->table_name . " c
                WHERE c.id = ?
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(1, $this->id);

        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
    }

    // create category
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name,
                    created=:created,
                    modified=:modified";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
    
        // bind values
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":created", $this->created);
        $statement->bindParam(":modified", $this->modified);
    
        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
        
    }

    function update(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name=:name,
                    modified=:modified
                WHERE
                    id=:id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
    
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":modified", $this->modified);
    
        try {
            $statement->execute();
            return json_encode($statement->errorInfo());
        } catch(PDOException $e) {
            return $e;
        }
        
        // execute query
        // if($statement->execute()){
        //     return true;
        // }
    
        // return false;
        
    }

    function flagDeleted() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted=1,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":modified", $this->modified);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;

    }

    function restoreDeleted() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted=0,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":modified", $this->modified);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;

    }
}