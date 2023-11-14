<?php
// 'user' object for all useer related fucntionalities 
class User{
    // database connection and table name
    private $conn;
    private $table_name = "users";
    // object properties
    public $id;
    public $name;
    public $email;
    public $username;
    public $password;
    public $token;
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // create new user record
    function create(){
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name,
                    email = :email,
                    username = :username,
                    password = :password";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        // bind the values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':username', $this->username);
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = (int) $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    // check if given username exist in the database
    function usernameexists(){
        // query to check if username exists
        $query = "SELECT id, name, username, email, password, token
                FROM " . $this->table_name . "
                WHERE username = ?
                LIMIT 0,1";
        // prepare the query
        $stmt = $this->conn->prepare( $query );
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        // bind given username value
        $stmt->bindParam(1, $this->username);
        // execute the query
        $stmt->execute();
        // get number of rows
        $num = $stmt->rowCount();
        // if username exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->token = $row['token'];
            // return true because username exists in the database
            return true;
        }
        // return false if username does not exist in the database
        return false;
    }

    // check if given username exist in the database in update process
    function checkusernameforupdate(){
        // query to check if username exists
        $query = "SELECT id, name, username, email, password, token
                FROM " . $this->table_name . "
                WHERE username = :username
                and id != :id
                LIMIT 0,1";
        // prepare the query
        $stmt = $this->conn->prepare( $query );
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        // bind value
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":id", $this->id);
        // execute the query
        $stmt->execute();
        // get number of rows
        $num = $stmt->rowCount();
        if($num>0){
            return true;
        }
        return false;
    }
    
    // update a user record
    public function update(){
        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
                SET
                    name = :name,
                    username = :username,
                    email = :email
                    {$password_set}
                WHERE id = :id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->username=htmlspecialchars(strip_tags($this->username));
        // bind the values from the form
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':username', $this->username);
        // hash the password before saving to database
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }
        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function get(){
        $query = "SELECT id, name, username, email, password, token
                FROM " . $this->table_name . "
                WHERE id = ?
                LIMIT 0,1";
        // prepare the query
        $stmt = $this->conn->prepare( $query );
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        // bind given username value
        $stmt->bindParam(1, $this->id);
        // execute the query
        $stmt->execute();
        // get number of rows
        $num = $stmt->rowCount();
        // if username exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->token = $row['token'];
            // return true because username exists in the database
            return true;
        }
        // return false if username does not exist in the database
        return false;
    }

    public function updateToken(){
        $query = "UPDATE " . $this->table_name . "
                SET token = ?
                WHERE id = ?";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // bind the values from the form
        $stmt->bindParam(1, $this->token);
        // unique ID of record to be edited
        $stmt->bindParam(2, $this->id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

}