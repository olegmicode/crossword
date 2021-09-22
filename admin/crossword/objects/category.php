<?php
class Category{
  
    // database connection and table name
    private $conn;
    private $table_name = "categories";
  
    // object properties
    public $id;
    public $name;
    public $totalQuestion;
    public $backupTableName;
    
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // get categories
    function getCategories()
    {   
        // select all query
        $query = "SELECT
                    c.id, c.name, COUNT(q.id) AS totalQuestion
                FROM
                    " . $this->table_name . " c
                    LEFT JOIN
                    questions q
                        ON c.id = q.category_id
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

    function getAllCategories()
    {   
        // select all query
        $query = "SELECT *
            FROM " . $this->table_name;
    
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
        $query = "SELECT c.id as id, c.name as name
                FROM " . $this->table_name . " c
                WHERE c.name = ?
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(1, $this->name);

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
                    name=:name";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
    
        // bind values
        $statement->bindParam(":name", $this->name);
    
        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
        
    }

    // CreateTableSQL
    function getCreateTableSQL() {
        // select all query
        $query = "SHOW CREATE TABLE " . $this->backupTableName;
    
        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // execute query
        $statement->execute();

        return $statement;
    }
}