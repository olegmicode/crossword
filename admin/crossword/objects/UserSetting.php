<?php
class UserSetting{
  
    // database connection and table name
    private $conn;
    private $table_name = "user_settings";
  
    // object properties
    public $id;
    public $setting_param;
    public $setting_value;
    public $user_key;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getSetting()
    {   
        // query to insert record
        $query = "SELECT * 
                FROM " . $this->table_name . "
                WHERE
                    user_key=:user_key AND
                    setting_param=:setting_param";
    
        // prepare query
        $statement = $this->conn->prepare($query);
    
        // sanitize
        $this->setting_param = htmlspecialchars(strip_tags($this->setting_param));
    
        // bind values
        $statement->bindParam(":user_key", $this->user_key);
        $statement->bindParam(":setting_param", $this->setting_param);
    
        // execute query
        $statement->execute();

        // get retrieved row
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->user_key = $row['user_key'];
        $this->setting_param = $row['setting_param'];
        $this->setting_value = (empty($row['setting_value'])) ? 0 : $row['setting_value'];
    }

    function setSetting()
    {   
        // query to read single record
        $query = "UPDATE " . $this->table_name . "
                SET setting_value=:setting_value
                WHERE user_key=:user_key AND
                setting_param=:setting_param
                ";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        $this->setting_param = htmlspecialchars(strip_tags($this->setting_param));
        $this->setting_value = htmlspecialchars(strip_tags($this->setting_value));

        // bind id of product to be updated
        $statement->bindParam(":user_key", $this->user_key);
        $statement->bindParam(":setting_param", $this->setting_param);
        $statement->bindParam(":setting_value", $this->setting_value);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }

    function addSetting()
    {
        $query = "INSERT INTO " . $this->table_name . "
                SET 
                    setting_param=:setting_param,
                    setting_value=:setting_value,
                    user_key=:user_key
                ";

        // prepare query statement
        $statement = $this->conn->prepare( $query );

        $this->setting_param = htmlspecialchars(strip_tags($this->setting_param));
        $this->setting_value = htmlspecialchars(strip_tags($this->setting_value));

        // bind id of product to be updated
        $statement->bindParam(":user_key", $this->user_key);
        $statement->bindParam(":setting_param", $this->setting_param);
        $statement->bindParam(":setting_value", $this->setting_value);

        // execute query
        if($statement->execute()){
            return true;
        }
    
        return false;
    }
}