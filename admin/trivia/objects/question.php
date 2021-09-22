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
    public $totalStarred;
    public $answer_info;
    public $scrap_source_status;
    public $scrap_answer;
    public $scrap_title;
    public $scrap_image;
    public $scrap_image_question;
    public $slug;
    public $is_alt_img;
    public $likes;
    public $dislikes;
    public $ingame_occurrence;

    public $offset;
    public $perpage;
    public $totalRows;
    public $searchCountry;
    public $searchCategory;
    public $searchKeyword;
    public $searchOrigin;
    public $isDraft;

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
            $additionQuery[] = '(q.question LIKE "%' . $this->searchKeyword . '%" OR 
                                q.question_source LIKE "%' . $this->searchKeyword . '%" OR
                                q.correct_answer LIKE "%' . $this->searchKeyword . '%" OR
                                q.incorrect_answer1 LIKE "%' . $this->searchKeyword . '%" OR
                                q.incorrect_answer2 LIKE "%' . $this->searchKeyword . '%" OR
                                q.incorrect_answer3 LIKE "%' . $this->searchKeyword . '%" OR
                                q.answer_info LIKE "%' . $this->searchKeyword . '%"
                                )';
        }
        if(!empty($this->isDraft)) {
            $additionQuery[] = 'q.status = 0';
        }
        if(!empty($this->is_mturk)) {
            $additionQuery[] = 'q.is_mturk = ' . $this->is_mturk;
        }
        if(isset($this->scrap_source_status)) {
            $additionQuery[] = 'q.scrap_source_status = ' . $this->scrap_source_status;
        }
        if(!empty($this->rate)) {
            ($this->rate == -1) && $this->rate = 0;
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
                    c.name ASC, q.id ASC
                LIMIT
                    ". $this->offset .", ". $this->perpage ." ";
    
        // print_r($query);
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
        if(!empty($this->scrap_source_status)) {
            $additionQuery[] = 'q.scrap_source_status = ' . $this->scrap_source_status;
        }
        if(!empty($this->rate)) {
            ($this->rate == -1) && $this->rate = 0;
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

    // updateScrape
    function updateScrape(
                        $id, 
                        $question_source, 
                        $answer_info, 
                        $scrap_answer, 
                        $scrap_title, 
                        $scrap_image, 
                        $scrap_source_status
                    )
    {
    
        // query to insert record
        $query = "UPDATE ".$this->table_name." SET question_source=:question_source, answer_info=:answer_info, modified=:modified, scrap_source_status=:scrap_source_status, scrap_answer=:scrap_answer, scrap_title=:scrap_title, scrap_image=:scrap_image WHERE id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // bind values
        $modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $id);
        $statement->bindParam(":answer_info", $answer_info);
        $statement->bindParam(":question_source", $question_source);
        $statement->bindParam(":modified", $modified);
        $statement->bindParam(":scrap_source_status", $scrap_source_status);
        $statement->bindParam(":scrap_answer", $scrap_answer);
        $statement->bindParam(":scrap_title", $scrap_title);
        $statement->bindParam(":scrap_image", $scrap_image);
    
        try {
            $statement->execute();
            $response['errorInfo'] = $statement->errorInfo();
            $response['statement'] = $statement;
            $response['query'] = $query;
            return $response;
        } catch(PDOException $e) {
            return $e;
        }
    }

    // getQuestionByQuery
    function getQuestionByQuery($baseQuery, $limit, $offset)
    {   
        // select all query
        $query = $baseQuery." LIMIT ".$offset.", ".$limit;

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();

        $response['statement'] = $statement;
        $response['query'] = $query;
    
        return $response;
    }

    // updateImage
    function updateImage($id, $image_url, $scrap_source_status = 1, $col_image = 'scrap_image') {
        $query = "UPDATE ".$this->table_name." SET $col_image = :image_url, scrap_source_status = :scrap_source_status WHERE id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $statement->bindParam(":id", $id);
        $statement->bindParam(":image_url", $image_url);
        $statement->bindParam(":scrap_source_status", $scrap_source_status);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    // get questions by category
    function getByCategory()
    {   
        $where = " q.deleted = 0 AND q.rate = 1 AND q.status = 1 AND c.name = '" . $this->category_name . "' ";
        ($this->category_name == 'All') && $where = ' q.deleted = 0 AND q.rate = 1 AND q.status = 1 ';

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

    // get questions by category
    function getCountStarredQuestion()
    {   
        // select all query
        $query = "SELECT COUNT(q.id) AS totalStarred
                FROM
                    " . $this->table_name . " q
                WHERE
                    q.deleted = 0 AND 
                    q.rate = 1 AND 
                    q.category_id = " . $this->category_id;
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->totalStarred = $row['totalStarred'];

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
        if(!empty($this->scrap_source_status)) {
            $additionQuery[] = 'q.scrap_source_status = ' . $this->scrap_source_status;
        }
        if(!empty($this->rate)) {
            ($this->rate == -1) && $this->rate = 0;
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
        $this->answer_info = $row['answer_info'];
        $this->scrap_source_status = $row['scrap_source_status'];
        $this->scrap_answer = $row['scrap_answer'];
        $this->scrap_title = $row['scrap_title'];
        $this->scrap_image = $row['scrap_image'];
        $this->scrap_image_question = $row['scrap_image_question'];
        $this->is_alt_img = $row['is_alt_img'];
        $this->likes = $row['likes'];
        $this->dislikes = $row['dislikes'];
        $this->ingame_occurrence = $row['ingame_occurrence'];
    }

    // get question
    function getQuestionBySlug()
    {
        // query to read single record
        $query = "SELECT *
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
        $this->category_id = $row['category_id'];
        $this->question = $row['question'];
        $this->correct_answer = $row['correct_answer'];
        $this->incorrect_answer1 = $row['incorrect_answer1'];
        $this->incorrect_answer2 = $row['incorrect_answer2'];
        $this->incorrect_answer3 = $row['incorrect_answer3'];
        $this->question_source = $row['question_source'];
        $this->slug = $row['slug'];
        $this->country_spesific = $row['country_spesific'];
        $this->correct_count = $row['correct_count'];
        $this->incorrect_count = $row['incorrect_count'];
        $this->reported = $row['reported'];
        $this->status = $row['status'];
        $this->rate = $row['rate'];
        $this->answer_info = $row['answer_info'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
        $this->scrap_source_status = $row['scrap_source_status'];
        $this->scrap_answer = $row['scrap_answer'];
        $this->scrap_title = $row['scrap_title'];
        $this->scrap_image = $row['scrap_image'];
        $this->scrap_image_question = $row['scrap_image_question'];
        $this->is_image_alt = $row['is_image_alt'];
    }

    // create question
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
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
                    correct_count=:correct_count, 
                    incorrect_count=:incorrect_count, 
                    reported=:reported, 
                    status=:status, 
                    origin=:origin,
                    answer_info=:answer_info,
                    modified=:modified,
                    is_mturk=:is_mturk,
                    created=:created,
                    scrap_source_status=:scrap_source_status,
                    scrap_answer=:scrap_answer,
                    scrap_title=:scrap_title,
                    scrap_image=:scrap_image,
                    slug=:slug";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->question = htmlspecialchars(strip_tags($this->question));
        $this->answer_info = htmlspecialchars(strip_tags($this->answer_info));
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));
        $this->incorrect_answer1 = htmlspecialchars(strip_tags($this->incorrect_answer1));
        $this->incorrect_answer2 = htmlspecialchars(strip_tags($this->incorrect_answer2));
        $this->incorrect_answer3 = htmlspecialchars(strip_tags($this->incorrect_answer3));
        $this->question_source = htmlspecialchars(strip_tags($this->question_source));
        $this->country_spesific = htmlspecialchars(strip_tags($this->country_spesific));
        $this->correct_count = htmlspecialchars(strip_tags($this->correct_count));
        $this->incorrect_count = htmlspecialchars(strip_tags($this->incorrect_count));
        $this->reported = htmlspecialchars(strip_tags($this->reported));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->modified = htmlspecialchars(strip_tags($this->modified));
        $this->origin = htmlspecialchars(strip_tags($this->origin));
        $this->scrap_source_status = htmlspecialchars(strip_tags($this->scrap_source_status));
        $this->scrap_answer = htmlspecialchars(strip_tags($this->scrap_answer));
        $this->scrap_title = htmlspecialchars(strip_tags($this->scrap_title));
        $this->scrap_image = htmlspecialchars(strip_tags($this->scrap_image));
        $this->slug = htmlspecialchars(strip_tags($this->slug));
    
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":question", $this->question);
        $statement->bindParam(":answer_info", $this->answer_info);
        $statement->bindParam(":correct_answer", $this->correct_answer);
        $statement->bindParam(":incorrect_answer1", $this->incorrect_answer1);
        $statement->bindParam(":incorrect_answer2", $this->incorrect_answer2);
        $statement->bindParam(":incorrect_answer3", $this->incorrect_answer3);
        $statement->bindParam(":question_source", $this->question_source);
        $statement->bindParam(":country_spesific", $this->country_spesific);
        $statement->bindParam(":correct_count", $this->correct_count);
        $statement->bindParam(":incorrect_count", $this->incorrect_count);
        $statement->bindParam(":reported", $this->reported);
        $statement->bindParam(":status", $this->status);
        $statement->bindParam(":created", $this->created);
        $statement->bindParam(":modified", $this->modified);
        $statement->bindParam(":is_mturk", $this->is_mturk);
        $statement->bindParam(":origin", $this->origin);
        $statement->bindParam(":scrap_source_status", $this->scrap_source_status);
        $statement->bindParam(":scrap_answer", $this->scrap_answer);
        $statement->bindParam(":scrap_title", $this->scrap_title);
        $statement->bindParam(":scrap_image", $this->scrap_image);
        $statement->bindParam(":slug", $this->slug);
        

        // try {
        //     $statement->execute();
        //     print_r($statement->errorInfo());
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
                    answer_info=:answer_info,
                    modified=:modified,
                    scrap_source_status=:scrap_source_status,
                    scrap_answer=:scrap_answer,
                    scrap_title=:scrap_title,
                    scrap_image=:scrap_image
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->question = htmlspecialchars(strip_tags($this->question));
        $this->answer_info = htmlspecialchars(strip_tags($this->answer_info));
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));
        $this->incorrect_answer1 = htmlspecialchars(strip_tags($this->incorrect_answer1));
        $this->incorrect_answer2 = htmlspecialchars(strip_tags($this->incorrect_answer2));
        $this->incorrect_answer3 = htmlspecialchars(strip_tags($this->incorrect_answer3));
        $this->question_source = htmlspecialchars(strip_tags($this->question_source));
        $this->country_spesific = htmlspecialchars(strip_tags($this->country_spesific));
        $this->scrap_source_status = htmlspecialchars(strip_tags($this->scrap_source_status));
        $this->scrap_answer = htmlspecialchars(strip_tags($this->scrap_answer));
        $this->scrap_title = htmlspecialchars(strip_tags($this->scrap_title));
        $this->scrap_image = htmlspecialchars(strip_tags($this->scrap_image));
    
        // bind values
        $this->modified = date('Y-m-d H:i:s');
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":question", $this->question);
        $statement->bindParam(":answer_info", $this->answer_info);
        $statement->bindParam(":correct_answer", $this->correct_answer);
        $statement->bindParam(":incorrect_answer1", $this->incorrect_answer1);
        $statement->bindParam(":incorrect_answer2", $this->incorrect_answer2);
        $statement->bindParam(":incorrect_answer3", $this->incorrect_answer3);
        $statement->bindParam(":question_source", $this->question_source);
        $statement->bindParam(":country_spesific", $this->country_spesific);
        $statement->bindParam(":modified", $this->modified);
        $statement->bindParam(":scrap_source_status", $this->scrap_source_status);
        $statement->bindParam(":scrap_answer", $this->scrap_answer);
        $statement->bindParam(":scrap_title", $this->scrap_title);
        $statement->bindParam(":scrap_image", $this->scrap_image);
    
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

    function setStatistic() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    incorrect_count=:incorrect_count, 
                    correct_count=:correct_count, 
                    reported=:reported,
                    ingame_occurrence=:ingame_occurrence,
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
        $statement->bindParam(":ingame_occurrence", $this->ingame_occurrence);
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

    function getEscapedQuestion()
    {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where scrap_title LIKE "%&amp;%" LIMIT 0,500';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;

    }

    function cleanEscaped() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                scrap_title = :scrap_title
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $statement->bindParam(":id", $this->id);
        // $statement->bindParam(":answer_info", $this->answer_info);
        $statement->bindParam(":scrap_title", $this->scrap_title);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    function slugify()
    {        

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $this->question);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        $this->slug = $text;
    }

    function setSlug() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    slug=:slug
                WHERE
                    id = :id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":slug", $this->slug);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;
    }

    function setAltImage() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    is_alt_img=:is_alt_img
                WHERE
                    id=:id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
        // print_r($this->id);
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":is_alt_img", $this->is_alt_img);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;

        // try {
        //     $statement->execute();
        //     print_r($statement->errorInfo());
        //     return json_encode($statement->errorInfo());
        // } catch(PDOException $e) {
        //     return $e;
        // }
    }

    // get questions
    function getQuestionsWithNoSlug()
    {   
        // select all query
        $query = "SELECT q.*
                FROM
                    " . $this->table_name . " q
                WHERE
                    q.slug = ''
                ORDER BY
                    q.id ASC
                LIMIT
                    ". $this->offset .", ". $this->perpage ." ";
    
        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function setLikes() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    likes=:likes
                WHERE
                    id=:id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
        
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":likes", $this->likes);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;
    }

    function setDislikes() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    dislikes=:dislikes
                WHERE
                    id=:id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
        
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":dislikes", $this->dislikes);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;
    }

    function setScrapImage() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    scrap_image=:scrap_image
                WHERE
                    id=:id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
        
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":scrap_image", $this->scrap_image);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;
    }

    function setScrapImageQuestion() {
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    scrap_image_question=:scrap_image_question
                WHERE
                    id=:id";
    
        // prepare query
        $statement = $this->conn->prepare($query);
        
        // bind values
        $statement->bindParam(":id", $this->id);
        $statement->bindParam(":scrap_image_question", $this->scrap_image_question);
        
        // execute query
        if($statement->execute()){
            return true;
        }

        return false;
    }

    function getMostOccurrence() {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where deleted=0 AND ingame_occurrence > 0 
                ORDER BY ingame_occurrence DESC LIMIT 0,5';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getMostLiked() {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where deleted=0 AND likes > 0 
                ORDER BY likes DESC LIMIT 0,5';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getMostDisliked() {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where deleted=0 AND dislikes > 0 
                ORDER BY dislikes DESC LIMIT 0,5';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getMostReported() {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where deleted=0 AND reported > 0 
                ORDER BY reported DESC LIMIT 0,5';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getMostCorrect() {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where deleted=0 AND correct_count > 0 
                ORDER BY correct_count DESC LIMIT 0,5';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

    function getMostIncorrect() {
        $query = 'SELECT  * FROM '. $this->table_name .' 
                where deleted=0 AND incorrect_count > 0 
                ORDER BY incorrect_count DESC LIMIT 0,5';

        // prepare query statement
        $statement = $this->conn->prepare($query);
    
        // execute query
        $statement->execute();
    
        return $statement;
    }

}