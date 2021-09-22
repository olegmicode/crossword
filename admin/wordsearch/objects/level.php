<?php
class Level{
  
    // database connection and table name
    private $conn;
    private $table_name = "levels";
  
    // object properties
    public $id;
    public $category_id;
    public $category_name;
    public $name;
    public $description;
    public $alphabet;
    public $size;
    public $hint;
    public $total_words;
    public $initial_score;
    public $time_interval;
    public $point_deduction;
    public $difficulty;
    public $status;
    public $deleted;
    public $permalink;
    public $created;
    public $modified;
  
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
                    COUNT(w.id) AS total_words
                FROM
                    " . $this->table_name . " l
                    LEFT JOIN
                    words w
                        ON l.id = w.level_id

                    LEFT JOIN
                    categories c
                        ON l.category_id = c.id
                WHERE
                    " . $stringDeleted . "  " . $additionQueryString . "
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

    function getLevelByPermalink()
    {   
        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "l.deleted=" . $deleted;

        $stringPermalink = "l.permalink='" . $this->permalink . "'";

        // select all query
        $query = "SELECT
                    c.name as category_name, 
                    l.*,
                    COUNT(w.id) AS total_words
                FROM
                    " . $this->table_name . " l
                    LEFT JOIN
                    words w
                        ON l.id = w.level_id

                    LEFT JOIN
                    categories c
                        ON l.category_id = c.id
                WHERE
                    " . $stringDeleted . " AND  " . $stringPermalink . "
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
                COUNT(l.id) as totalRow
                FROM " . $this->table_name . " l 
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

        // set values to object properties
        if(!empty($row)){
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->category_id = $row['category_id'];
            $this->description = $row['description'];
            $this->alphabet = $row['alphabet'];
            $this->size = $row['size'];
            $this->hint = $row['hint'];
            $this->total_words = $row['total_words'];
            $this->initial_score = $row['initial_score'];
            $this->time_interval = $row['time_interval'];
            $this->point_deduction = $row['point_deduction'];
            $this->difficulty = $row['difficulty'];
            $this->status = $row['status'];
            $this->deleted = $row['deleted'];
            $this->permalink = $row['permalink'];
            $this->modified = $row['modified'];
            $this->created = $row['created'];
        }
    }

    function getById()
    {
        // query to read single record
        $query = "SELECT 
                    l.*, 
                    COUNT(w.id) AS total_words
                FROM " . $this->table_name . " l
                    LEFT JOIN
                    words w
                        ON l.id = w.level_id
                WHERE w.deleted = 0 AND l.id = ?
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
        $this->category_id = $row['category_id'];
        $this->description = $row['description'];
        $this->alphabet = $row['alphabet'];
        $this->size = $row['size'];
        $this->hint = $row['hint'];
        $this->total_words = $row['total_words'];
        $this->initial_score = $row['initial_score'];
        $this->time_interval = $row['time_interval'];
        $this->point_deduction = $row['point_deduction'];
        $this->difficulty = $row['difficulty'];
        $this->status = $row['status'];
        $this->deleted = $row['deleted'];
        $this->permalink = $row['permalink'];
        $this->modified = $row['modified'];
        $this->created = $row['created'];
    }

    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    category_id=:category_id,     
                    name=:name, 
                    description=:description, 
                    alphabet=:alphabet, 
                    size=:size, 
                    hint=:hint, 
                    time_interval=:time_interval, 
                    point_deduction=:point_deduction, 
                    initial_score=:initial_score, 
                    difficulty=:difficulty,
                    status=:status,
                    modified=:modified,
                    created=:created,
                    permalink=:permalink
                ";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->alphabet = htmlspecialchars(strip_tags($this->alphabet));
        $this->size = htmlspecialchars(strip_tags($this->size));
        $this->hint = htmlspecialchars(strip_tags($this->hint));
        $this->time_interval = htmlspecialchars(strip_tags($this->time_interval));
        $this->point_deduction = htmlspecialchars(strip_tags($this->point_deduction));
        $this->initial_score = htmlspecialchars(strip_tags($this->initial_score));
        $this->modified = htmlspecialchars(strip_tags($this->modified));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));
        $this->permalink = htmlspecialchars(strip_tags($this->permalink));
    
        // bind values
        $this->created = date('Y-m-d H:i:s');
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":alphabet", $this->alphabet);
        $statement->bindParam(":size", $this->size);
        $statement->bindParam(":hint", $this->hint);
        $statement->bindParam(":time_interval", $this->time_interval);
        $statement->bindParam(":point_deduction", $this->point_deduction);
        $statement->bindParam(":initial_score", $this->initial_score);
        $statement->bindParam(":status", $this->status);
        $statement->bindParam(":modified", $this->modified);
        $statement->bindParam(":created", $this->created);
        $statement->bindParam(":difficulty", $this->difficulty);
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
                    alphabet=:alphabet, 
                    size=:size, 
                    hint=:hint, 
                    time_interval=:time_interval, 
                    point_deduction=:point_deduction, 
                    initial_score=:initial_score, 
                    difficulty=:difficulty,
                    modified=:modified,
                    permalink=:permalink
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->alphabet = htmlspecialchars(strip_tags($this->alphabet));
        $this->size = htmlspecialchars(strip_tags($this->size));
        $this->hint = htmlspecialchars(strip_tags($this->hint));
        $this->time_interval = htmlspecialchars(strip_tags($this->time_interval));
        $this->point_deduction = htmlspecialchars(strip_tags($this->point_deduction));
        $this->initial_score = htmlspecialchars(strip_tags($this->initial_score));
        $this->modified = htmlspecialchars(strip_tags($this->modified));
        $this->difficulty = htmlspecialchars(strip_tags($this->difficulty));
        $this->permalink = htmlspecialchars(strip_tags($this->permalink));

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":alphabet", $this->alphabet);
        $statement->bindParam(":size", $this->size);
        $statement->bindParam(":hint", $this->hint);
        $statement->bindParam(":time_interval", $this->time_interval);
        $statement->bindParam(":point_deduction", $this->point_deduction);
        $statement->bindParam(":initial_score", $this->initial_score);
        $statement->bindParam(":modified", $this->modified);
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
}