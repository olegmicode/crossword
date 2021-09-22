<?php
class Level{
  
    // database connection and table name
    private $conn;
    private $table_name = "levels";
  
    // object properties
    public $id;
    public $category_id;
    public $name;
    public $description;
    public $width;
    public $height;
    public $difficulty;
    public $permalink;
    public $board_json;
    
    public $totalQuestion;
    
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getLevels()
    {   
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'l.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = '(l.name LIKE "%' . $this->searchKeyword . '%" OR 
                                l.description LIKE "%' . $this->searchKeyword . '%")
                                ';
        }
        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";

        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "l.deleted=" . $deleted;

        // select all query
        $query = "SELECT
                    c.name as category_name, 
                    l.*, 
                    COUNT(q.id) AS totalQuestion
                FROM
                    " . $this->table_name . " l
                    LEFT JOIN
                    questions q
                        ON l.id = q.level_id
                    
                    LEFT JOIN
                        categories c
                            ON l.category_id = c.id
                WHERE
                    " . $stringDeleted . " " . $additionQueryString . "
                GROUP BY
                    l.id
                ORDER BY
                    l.name ASC";
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getAllLevelsCount()
    {
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'l.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = '(l.name LIKE "%' . $this->searchKeyword . '%" OR 
                                l.description LIKE "%' . $this->searchKeyword . '%")
                                ';
        }
        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";
        
        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "q.deleted=" . $deleted;

        // select all query
        $query = "SELECT 
                COUNT(q.id) as totalRow
                FROM " . $this->table_name . " q 
                WHERE
                    " . $stringDeleted ." ". $additionQueryString;
    
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
        $query = "SELECT l.*
                FROM " . $this->table_name . " l
                WHERE l.name = ?
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(1, $this->name);

        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if($row){
            // set values to object properties
            $this->id = $row['id'];
            $this->category_id = $row['category_id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->width = $row['width'];
            $this->height = $row['height'];
            $this->difficulty = $row['difficulty'];
            $this->status = $row['status'];
            $this->deleted = $row['deleted'];
            $this->permalink = $row['permalink'];
        }
    }

    function getById()
    {
        // query to read single record
        $query = "SELECT l.*, COUNT(q.id) AS totalQuestion
                FROM " . $this->table_name . " l
                LEFT JOIN
                questions q
                    ON l.id = q.level_id
                WHERE l.id = ?
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
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->width = $row['width'];
        $this->height = $row['height'];
        $this->difficulty = $row['difficulty'];
        $this->status = $row['status'];
        $this->deleted = $row['deleted'];
        $this->totalQuestion = $row['totalQuestion'];
        $this->permalink = $row['permalink'];
    }

    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name,
                    category_id=:category_id,
                    description=:description, 
                    width=:width, 
                    height=:height,
                    difficulty=:difficulty,
                    status=:status,
                    deleted=:deleted,
                    created=:created,
                    modified=:modified,
                    permalink=:permalink
                ";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->width = htmlspecialchars(strip_tags($this->width));
        $this->height = htmlspecialchars(strip_tags($this->height));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->deleted = htmlspecialchars(strip_tags($this->deleted));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->modified = htmlspecialchars(strip_tags($this->modified));
        $this->permalink = htmlspecialchars(strip_tags($this->permalink));
    
        // bind values
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":width", $this->width);
        $statement->bindParam(":height", $this->height);
        $statement->bindParam(":difficulty", $this->difficulty);
        $statement->bindParam(":status", $this->status);
        $statement->bindParam(":deleted", $this->deleted);
        $statement->bindParam(":created", $this->created);
        $statement->bindParam(":modified", $this->modified);
        $statement->bindParam(":permalink", $this->permalink);

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
                    category_id=:category_id,
                    description=:description, 
                    width=:width, 
                    height=:height, 
                    difficulty=:difficulty,
                    permalink=:permalink
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->width = htmlspecialchars(strip_tags($this->width));
        $this->height = htmlspecialchars(strip_tags($this->height));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));
        $this->permalink = htmlspecialchars(strip_tags($this->permalink));
    
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":width", $this->width);
        $statement->bindParam(":height", $this->height);
        $statement->bindParam(":difficulty", $this->difficulty);
        $statement->bindParam(":permalink", $this->permalink);
    
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

    function updateBoardJSON(){
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    board_json=:board_json,
                    modified=:modified
                WHERE
                    id = :id";

        // prepare query
        $statement = $this->conn->prepare($query);

        // sanitize
        $this->modified = htmlspecialchars(strip_tags($this->modified));

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":board_json", $this->board_json);
        $statement->bindParam(":modified", $this->modified);

        if($statement->execute()){
            return true;
        }
        return false;
    }
}