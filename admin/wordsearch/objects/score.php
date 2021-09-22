<?php
class Score{
  
    // database connection and table name
    private $conn;
    private $table_name = "scores";
  
    // object properties
    public $id;
    public $user_key;
    public $scores;
    public $created;
    
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // generate user_key
    function generateUserKey()
    {   
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_key=:user_key,
                    scores='',
                    created=:created";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->user_key = htmlspecialchars(strip_tags($this->user_key));
    
        // bind values
        $this->created = date('Y-m-d h:i:s');
        $statement->bindParam(":user_key", $this->user_key);
        $statement->bindParam(":created", $this->created);
    
        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    // get scores by user_key
    function getByUserKey()
    {   
        // query to read single record
        $query = "SELECT *
                FROM " . $this->table_name . " s
                WHERE s.user_key = ?
                LIMIT 0,1";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        // bind id of product to be updated
        $statement->bindParam(1, $this->user_key);

        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->user_key = $row['user_key'];
        $this->scores = $row['scores'];
        $this->created = $row['created'];
    }

    function updateScores() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                scores = :scores
                WHERE
                    user_key=:user_key";
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // bind values
        $statement->bindParam(":user_key", $this->user_key);
        $statement->bindParam(":scores", $this->scores);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    function getHallOfFame()
    {
        // select all query
        $query = "SELECT *
                FROM " . $this->table_name . "
                WHERE scores != ''
                ORDER BY
                    created DESC";

        // prepare query statement
        $statement = $this->conn->prepare($query);

        // execute query
        $statement->execute();
        $scoreList = array();
        $i = 0;
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
            // get the biggest score of all
            extract($row);   
            $scoreArray = json_decode($scores);
            if(!empty($scoreArray->scores)) {
                foreach($scoreArray->scores as $scorePerCategory) {
                    $scoreList[$i]['last_played'] = $scoreArray->date;
                    $scoreList[$i]['user'] = $user_key;
                    $scoreList[$i]['score'] = $scorePerCategory->score;
                    // array_push($scoreList, $scorePerCategory);
                    $i++;
                }
            }
        }

        // now sort
        $sortKey = array_column($scoreList, 'score');
        array_multisort($sortKey, SORT_DESC, $scoreList);

        $hallOfFame = array_slice($scoreList, 0, 10); 
        
        return $hallOfFame;
    }

    function deleteAll()
    {
        $query = "DELETE FROM
                    " . $this->table_name;
    
        // prepare query
        $statement = $this->conn->prepare($query);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }
    
}