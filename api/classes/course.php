<?php
class Course{
  
    // database connection and table name
    private $conn;
    private $table_name = "courses";
  
    // object properties
    public $code;
    public $name;
    public $progression;
    public $link;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all courses
    function read(){
        $query = "SELECT id, code, name, progression, link FROM $this->table_name";
    
        // prepare query statement
        $result = $this->conn->prepare($query);
    
        // execute query
        $result->execute();
    
        return $result;
    }

    // read one course
    function readOne($id){
        $query = "SELECT id, code, name, progression, link FROM $this->table_name WHERE id=$id";
        
        // prepare query statement
        $result = $this->conn->prepare($query);
    
        // execute query
        $result->execute();
    
        return $result;
    }

    // create new course
    function create() {
       $query = "INSERT INTO $this->table_name
            SET
                code=:code, name=:name, progression=:progression, link=:link";
  
        // prepare query
        $result = $this->conn->prepare($query);
    
        // sanitize
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->link=htmlspecialchars(strip_tags($this->link));
    
        // bind values
        $result->bindParam(":code", $this->code);
        $result->bindParam(":name", $this->name);
        $result->bindParam(":progression", $this->progression);
        $result->bindParam(":link", $this->link);
    
        // execute query
        if($result->execute()){
            return true;
        }
    
        return false;
    }

    // delete course 
    function delete($id) {
        // select all query
        $query = "DELETE FROM $this->table_name WHERE id=$id";
        
        // prepare query statement
        $result = $this->conn->prepare($query);
    
        // execute query
        $result->execute();
    
        return $result;
    }

    function update() {
         // update query
        $query = "UPDATE $this->table_name
                SET
                    code = :code,
                    name = :name,
                    progression = :progression,
                    link = :link
                WHERE
                    id = :id";
    
        // prepare query statement
        $result = $this->conn->prepare($query);
    
        // sanitize
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->link=htmlspecialchars(strip_tags($this->link));
    
        // bind new values
        $result->bindParam(':code', $this->code);
        $result->bindParam(':name', $this->name);
        $result->bindParam(':progression', $this->progression);
        $result->bindParam(':link', $this->link);
        $result->bindParam(':id', $this->id);
    
        // execute the query
        if($result->execute()){
            return true;
        }
    
        return false;
    }
}
?>