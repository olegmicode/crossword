<?php
class Word{
  
    // database connection and table name
    private $conn;
    private $table_name = "words";
  
    // object properties
    public $id;
    public $level_id;
    public $category_id;
    public $category_name;
    public $name;
    public $description;
    public $status;
    public $deleted;
    public $cookie_id;
    public $created;
    public $modified;

    public $searchCategory;

    public $offset;
    public $perpage;
    
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getWords()
    {   
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'w.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = '(w.name LIKE "%' . $this->searchKeyword . '%" OR 
                                w.description LIKE "%' . $this->searchKeyword . '%")
                                ';
        }
        if(!empty($this->isDraft)) {
            $additionQuery[] = 'w.status = 0';
        }

        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";

        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "w.deleted=" . $deleted;

        $query = "SELECT
                    c.name as category_name, 
                    w.*
                FROM
                    " . $this->table_name . " w
                    LEFT JOIN
                    categories c
                        ON c.id = w.category_id
                WHERE
                    " . $stringDeleted . " " . $additionQueryString . "
                ORDER BY
                    c.name ASC, w.name ASC
                LIMIT
                    ". $this->offset .", ". $this->perpage ." ";
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getAllWordsCount()
    {
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'w.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = '(w.name LIKE "%' . $this->searchKeyword . '%" OR 
                                w.description LIKE "%' . $this->searchKeyword . '%")
                                ';
        }
        if(!empty($this->isDraft)) {
            $additionQuery[] = 'w.status = 0';
        }

        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";

        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "w.deleted=" . $deleted;

        // select all query
        $query = "SELECT 
                COUNT(w.id) as totalRow
                FROM " . $this->table_name . " w
                WHERE
                    " . $stringDeleted . " " . $additionQueryString;
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->totalRows = $row['totalRow'];
    }

    function getByName()
    {
        // query to read single record
        $query = "SELECT w.*
                FROM " . $this->table_name . " w
                WHERE w.name = ?
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
        if(!empty($row)){
            $this->id = $row['id'];
            $this->category_id = $row['category_id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->status = $row['status'];
            $this->deleted = $row['deleted'];
            $this->created = $row['created'];
            $this->modified = $row['modified'];
        }
    }

    function getById()
    {
        // query to read single record
        $query = "SELECT c.name as category_name, w.*
                FROM " . $this->table_name . " w
                    LEFT JOIN
                    categories c
                        ON c.id = w.category_id    
                WHERE w.id = ?
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
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->level_id = $row['level_id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->status = $row['status'];
        $this->deleted = $row['deleted'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }

    function getByLevel()
    {   
        $where = " w.deleted = 0 AND w.level_id = '" . $this->level_id . "' ";

        // select all query
        $query = "SELECT                    
                    w.*
                FROM
                    " . $this->table_name . " w
                WHERE
                    " . $where . "
                ORDER BY
                    RAND()
                LIMIT
                    0,20";
    
        // print_r($additionQuery); exit;
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    category_id=:category_id, 
                    level_id=:level_id,    
                    name=:name,
                    description=:description, 
                    status=:status, 
                    deleted=:deleted,
                    cookie_id=:cookie_id,
                    created=:created,
                    modified=:modified
                ";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->level_id = htmlspecialchars(strip_tags($this->level_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->deleted = htmlspecialchars(strip_tags($this->deleted));
        $this->cookie_id = htmlspecialchars(strip_tags($this->cookie_id));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->modified = htmlspecialchars(strip_tags($this->modified));
        
        // bind values
        $this->deleted = 0;
        $this->created = date('Y-m-d H:i:s');
        $this->modified = date('Y-m-d H:i:s');
        $level_id = !empty($this->level_id) ? $this->level_id : 0;
        $statement->bindParam(":level_id", $level_id );
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":status", $this->status);
        $statement->bindParam(":deleted", $this->deleted);
        $statement->bindParam(":cookie_id", $this->cookie_id);
        $statement->bindParam(":created", $this->created);
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

    function update(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET  
                    name=:name, 
                    description=:description, 
                    category_id=:category_id, 
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        // $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
    
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":modified", $this->modified);
    
        // try {
        //     $statement->execute();
        //     return json_encode($statement->errorInfo());
        // } catch(PDOException $e) {
        //     return $e;
        // }
        
        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
        
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

    function setLevel() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    level_id = :level_id,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":level_id", $this->level_id);
        $statement->bindParam(":modified", $this->modified);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    function setStatus() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    status = :status,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":status", $this->status);
        $statement->bindParam(":modified", $this->modified);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    // get words by cookie_id
    function getByCookieId($cookie_id)
    {   
        $where = " w.cookie_id = '" . $cookie_id . "' AND w.deleted = 0";

        // select all query
        $query = "SELECT
                    w.*
                FROM
                    " . $this->table_name . " w
                WHERE
                    " . $where;
                    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function updateByCookieId(){
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    cookie_id='',
                    level_id=:level_id,
                    modified=:modified
                WHERE
                    cookie_id=:cookie_id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->level_id = htmlspecialchars(strip_tags($this->level_id));
        
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":cookie_id", $this->cookie_id);
        $statement->bindParam(":level_id", $this->level_id);
        $statement->bindParam(":modified", $this->modified);
        
        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
        
    }
}