<?php
class Question{
  
    // database connection and table name
    private $conn;
    private $table_name = "questions";
  
    // object properties
    public $id;
    public $category_id;
    public $category_name;
    public $question;
    public $correct_answer;
    public $incorrect_answer1;
    public $incorrect_answer2;
    public $incorrect_answer3;
    public $question_source;
    public $country_spesific;
    public $correct_count;
    public $incorrect_count;
    public $reported;
    public $status;
    public $is_mturk;
    public $rate;
    public $created;
    public $deleted;
    public $modified;
    public $origin;
    public $level_id;
    public $slug;
    public $answer_info;

    public $offset;
    public $perpage;
    public $totalRows;
    public $searchCountry;
    public $searchCategory;
    public $searchKeyword;
    public $searchOrigin;
    public $isDraft;

    public $cookie_id;

    public $backupTableName;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // get questions
    function getQuestions()
    {   
        $additionQuery = array();
        if(!empty($this->searchCountry)) {
            $additionQuery[] = 'q.country_spesific ="' . $this->searchCountry . '"';
        }
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'q.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = 'q.question LIKE "%' . $this->searchKeyword . '%" OR 
                                q.question_source LIKE "%' . $this->searchKeyword . '%" OR
                                q.correct_answer LIKE "%' . $this->searchKeyword . '%" OR
                                q.incorrect_answer1 LIKE "%' . $this->searchKeyword . '%" OR
                                q.incorrect_answer2 LIKE "%' . $this->searchKeyword . '%" OR
                                q.incorrect_answer3 LIKE "%' . $this->searchKeyword . '%"
                                ';
        }
        if(!empty($this->isDraft)) {
            $additionQuery[] = 'q.status = 0';
        }
        if(!empty($this->is_mturk)) {
            $additionQuery[] = 'q.is_mturk = ' . $this->is_mturk;
        }
        if(!empty($this->rate)) {
            $additionQuery[] = 'q.rate = ' . $this->rate;
        }
        if(!empty($this->searchOrigin)) {
            $additionQuery[] = 'q.origin LIKE "%' . $this->searchOrigin . '%"';
        }
        if(!empty($this->level_id)) {
            $additionQuery[] = 'q.level_id = '. $this->level_id;
        }

        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";

        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "q.deleted=" . $deleted;

        // select all query
        $query = "SELECT
                    c.name as category_name, 
                    q.*
                FROM
                    " . $this->table_name . " q
                    LEFT JOIN
                        categories c
                            ON q.category_id = c.id
                WHERE
                    " . $stringDeleted . " " . $additionQueryString . "
                ORDER BY
                    c.name ASC, q.id ASC
                LIMIT
                    ". $this->offset .", ". $this->perpage ." ";
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    // get questions
    function getRandomQuestions()
    {   
        $additionQuery = array();
        if(!empty($this->searchCountry)) {
            $additionQuery[] = 'q.country_spesific ="' . $this->searchCountry . '"';
        }
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'q.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = 'q.question LIKE "%' . $this->searchKeyword . '%"';
        }
        if(!empty($this->isDraft)) {
            $additionQuery[] = 'q.status = 0';
        }
        if(!empty($this->is_mturk)) {
            $additionQuery[] = 'q.is_mturk = ' . $this->is_mturk;
        }
        if(!empty($this->rate)) {
            $additionQuery[] = 'q.rate = ' . $this->rate;
        }
        if(!empty($this->searchOrigin)) {
            $additionQuery[] = 'q.origin LIKE "%' . $this->searchOrigin . '%"';
        }
        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";

        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "q.deleted=" . $deleted;

        // select all query
        $query = "SELECT
                    c.name as category_name, 
                    q.*
                FROM
                    " . $this->table_name . " q
                    LEFT JOIN
                        categories c
                            ON q.category_id = c.id
                WHERE
                    " . $stringDeleted . " " . $additionQueryString . "
                ORDER BY
                    RAND()
                LIMIT
                    ". $this->offset .", ". $this->perpage ." ";
    
        // print_r($query);
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    // get all questions
    function getAllQuestions()
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

    // get questions by category
    function getByCategory()
    {   
        $where = " q.deleted = 0 AND q.status = 1 AND c.name = '" . $this->category_name . "' ";
        ($this->category_name == 'All') && $where = ' q.deleted = 0 ';

        // select all query
        $query = "SELECT
                    c.name as category_name, 
                    q.*
                FROM
                    " . $this->table_name . " q
                    LEFT JOIN
                        categories c
                            ON q.category_id = c.id
                WHERE
                    " . $where . "
                ORDER BY
                    RAND()
                LIMIT
                    0,10";
    
        // print_r($additionQuery); exit;
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getByLevel()
    {   
        $where = " q.deleted = 0 AND rate = 1 AND q.status = 1 AND q.level_id = '" . $this->level_id . "' ";

        // select all query
        $query = "SELECT
                    c.name as category_name, 
                    q.*
                FROM
                    " . $this->table_name . " q
                    LEFT JOIN
                        categories c
                            ON q.category_id = c.id
                WHERE " . $where . "
                ORDER BY
                    RAND()
                ";
    
        // print_r($additionQuery); exit;
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    // get questions
    function getAllQuestionsCount()
    {
        $additionQuery = array();
        if(!empty($this->searchCountry)) {
            $additionQuery[] = 'q.country_spesific ="' . $this->searchCountry . '"';
        }
        if(!empty($this->searchCategory)) {
            $additionQuery[] = 'q.category_id ="' . $this->searchCategory . '"';
        }
        if(!empty($this->searchKeyword)) {
            $additionQuery[] = 'q.question LIKE "%' . $this->searchKeyword . '%"';
        }
        if(!empty($this->isDraft)) {
            $additionQuery[] = 'q.status = 0';
        }
        if(!empty($this->is_mturk)) {
            $additionQuery[] = 'q.is_mturk = ' . $this->is_mturk;
        }
        if(!empty($this->rate)) {
            $additionQuery[] = 'q.rate = ' . $this->rate;
        }
        if(!empty($this->searchOrigin)) {
            $additionQuery[] = 'q.origin LIKE "%' . $this->searchOrigin . '%"';
        }
        $additionQueryString = (!empty($additionQuery)) ? " AND " . implode(" AND ", $additionQuery) : "";

        $deleted = (!empty($this->deleted)) ? $this->deleted : 0;
        $stringDeleted = "q.deleted=" . $deleted;

        // select all query
        $query = "SELECT 
                COUNT(q.id) as totalRow
                FROM " . $this->table_name . " q 
                WHERE
                    " . $stringDeleted . " " . $additionQueryString;
    
        // print_r($query);exit;
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->totalRows = $row['totalRow'];
    }

    // get question
    function getQuestionByName()
    {
        // query to read single record
        $query = "SELECT q.id as id, q.question as question
                FROM " . $this->table_name . " q
                WHERE q.question = ?
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(1, $this->question);

        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->question = $row['question'];
    }

    // get question
    function getQuestionBySlug()
    {
        // query to read single record
        $query = "SELECT q.id as id, q.question as question
                FROM " . $this->table_name . " q
                WHERE q.slug = ?
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(1, $this->slug);

        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
    }

    // get question
    function getQuestionById()
    {
        // query to read single record
        $query = "SELECT *
                FROM " . $this->table_name . " q
                WHERE q.id = ?
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
        $this->question = $row['question'];
        $this->correct_answer = $row['correct_answer'];
        $this->incorrect_answer1 = $row['incorrect_answer1'];
        $this->incorrect_answer2 = $row['incorrect_answer2'];
        $this->incorrect_answer3 = $row['incorrect_answer3'];
        $this->question_source = $row['question_source'];
        $this->country_spesific = $row['country_spesific'];
        $this->correct_count = $row['correct_count'];
        $this->incorrect_count = $row['incorrect_count'];
        $this->reported = $row['reported'];
        $this->status = $row['status'];
        $this->rate = $row['rate'];
        $this->level_id = $row['level_id'];
    }

    // create question
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    category_id=:category_id, 
                    level_id=:level_id,
                    question=:question, 
                    correct_answer=:correct_answer, 
                    incorrect_answer1=:incorrect_answer1, 
                    incorrect_answer2=:incorrect_answer2, 
                    incorrect_answer3=:incorrect_answer3, 
                    question_source=:question_source, 
                    country_spesific=:country_spesific, 
                    correct_count=:correct_count, 
                    incorrect_count=:incorrect_count, 
                    slug=:slug, 
                    reported=:reported, 
                    rate=:rate,
                    status=:status, 
                    origin=:origin,
                    answer_info=:answer_info,
                    cookie_id=:cookie_id,
                    modified=:modified,
                    created=:created";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->question = htmlspecialchars(strip_tags($this->question));
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));
        $this->incorrect_answer1 = htmlspecialchars(strip_tags($this->incorrect_answer1));
        $this->incorrect_answer2 = htmlspecialchars(strip_tags($this->incorrect_answer2));
        $this->incorrect_answer3 = htmlspecialchars(strip_tags($this->incorrect_answer3));
        $this->question_source = htmlspecialchars(strip_tags($this->question_source));
        $this->country_spesific = htmlspecialchars(strip_tags($this->country_spesific));
        $this->correct_count = htmlspecialchars(strip_tags($this->correct_count));
        $this->incorrect_count = htmlspecialchars(strip_tags($this->incorrect_count));
        $this->reported = htmlspecialchars(strip_tags($this->reported));
        $this->rate = htmlspecialchars(strip_tags(empty($this->rate) ? 0 : $this->rate));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->modified = htmlspecialchars(strip_tags($this->modified));
        $this->origin = htmlspecialchars(strip_tags($this->origin));
        $this->slug = htmlspecialchars(strip_tags($this->slug));
        $this->answer_info = htmlspecialchars(strip_tags($this->answer_info));
        $this->cookie_id = $this->cookie_id != null ? htmlspecialchars(strip_tags($this->cookie_id)) : "";
        $this->level_id = empty($this->level_id) ? 0 : htmlspecialchars(strip_tags($this->level_id));

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":level_id", $this->level_id);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":question", $this->question);
        $statement->bindParam(":correct_answer", $this->correct_answer);
        $statement->bindParam(":incorrect_answer1", $this->incorrect_answer1);
        $statement->bindParam(":incorrect_answer2", $this->incorrect_answer2);
        $statement->bindParam(":incorrect_answer3", $this->incorrect_answer3);
        $statement->bindParam(":question_source", $this->question_source);
        $statement->bindParam(":country_spesific", $this->country_spesific);
        $statement->bindParam(":correct_count", $this->correct_count);
        $statement->bindParam(":incorrect_count", $this->incorrect_count);
        $statement->bindParam(":reported", $this->reported);
        $statement->bindParam(":rate", $this->rate);
        $statement->bindParam(":status", $this->status);
        $statement->bindParam(":created", $this->created);
        $statement->bindParam(":modified", $this->modified);
        $statement->bindParam(":origin", $this->origin);
        $statement->bindParam(":slug", $this->slug);
        $statement->bindParam(":answer_info", $this->answer_info);
        $statement->bindParam(":cookie_id", $this->cookie_id);
        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;

        // try {
        //     $statement->execute();
        //     return json_encode($statement->errorInfo());
        // } catch(PDOException $e) {
        //     return $e;
        // }
        
    }

    // create question
    function update(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    category_id=:category_id, 
                    question=:question, 
                    correct_answer=:correct_answer, 
                    incorrect_answer1=:incorrect_answer1, 
                    incorrect_answer2=:incorrect_answer2, 
                    incorrect_answer3=:incorrect_answer3, 
                    question_source=:question_source, 
                    country_spesific=:country_spesific,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->question = htmlspecialchars(strip_tags($this->question));
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));
        $this->incorrect_answer1 = htmlspecialchars(strip_tags($this->incorrect_answer1));
        $this->incorrect_answer2 = htmlspecialchars(strip_tags($this->incorrect_answer2));
        $this->incorrect_answer3 = htmlspecialchars(strip_tags($this->incorrect_answer3));
        $this->question_source = htmlspecialchars(strip_tags($this->question_source));
        $this->country_spesific = htmlspecialchars(strip_tags($this->country_spesific));
    
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":question", $this->question);
        $statement->bindParam(":correct_answer", $this->correct_answer);
        $statement->bindParam(":incorrect_answer1", $this->incorrect_answer1);
        $statement->bindParam(":incorrect_answer2", $this->incorrect_answer2);
        $statement->bindParam(":incorrect_answer3", $this->incorrect_answer3);
        $statement->bindParam(":question_source", $this->question_source);
        $statement->bindParam(":country_spesific", $this->country_spesific);
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

    function setStatistic() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    incorrect_count=:incorrect_count, 
                    correct_count=:correct_count, 
                    reported=:reported,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->incorrect_count = htmlspecialchars(strip_tags($this->incorrect_count));
        $this->correct_count = htmlspecialchars(strip_tags($this->correct_count));
        $this->reported = htmlspecialchars(strip_tags($this->reported));
        
    
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":incorrect_count", $this->incorrect_count);
        $statement->bindParam(":correct_count", $this->correct_count);
        $statement->bindParam(":reported", $this->reported);
        $statement->bindParam(":modified", $this->modified);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;
    }

    // sync category
    function categorySync(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    category_id=:category_id ,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":modified", $this->modified);

        try {
            $statement->execute();
            
            return true;
        } catch(PDOException $e) {
            return $e;
        }
    
        return false;
        
    }

    function flagDeleted() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted=1,
                    level_id=0,
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

    // delete question 
    function delete(){
  
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
      
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
    }

    function formatAnswer() {

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    correct_answer=:correct_answer,
                    incorrect_answer1=:incorrect_answer1, 
                    incorrect_answer2=:incorrect_answer2,
                    incorrect_answer3=:incorrect_answer3,
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));
        $this->incorrect_answer1 = htmlspecialchars(strip_tags($this->incorrect_answer1));
        $this->incorrect_answer2 = htmlspecialchars(strip_tags($this->incorrect_answer2));
        $this->incorrect_answer3 = htmlspecialchars(strip_tags($this->incorrect_answer3));
        
    
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":correct_answer", $this->correct_answer);
        $statement->bindParam(":incorrect_answer1", $this->incorrect_answer1);
        $statement->bindParam(":incorrect_answer2", $this->incorrect_answer2);
        $statement->bindParam(":incorrect_answer3", $this->incorrect_answer3);
        
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

    function setRate() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    rate = :rate,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":rate", $this->rate);
        $statement->bindParam(":modified", $this->modified);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    function setOrigin() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    origin = :origin,
                    modified=:modified
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":origin", $this->origin);
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

    // get questions by cookie_id
    function getByCookieId($cookie_id)
    {   
        $where = " q.cookie_id = '" . $cookie_id . "' AND q.deleted = 0";

        // select all query
        $query = "SELECT
                    q.*
                FROM
                    " . $this->table_name . " q
                WHERE
                    " . $where;
                    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }
    function updateByCookieId(){
    
        // query to insert record
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