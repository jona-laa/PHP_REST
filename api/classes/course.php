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
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // read one course
    function readOne($id){
        $query = "SELECT id, code, name, progression, link FROM $this->table_name WHERE id=$id";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create new course
    function create() {
       $query = "INSERT INTO $this->table_name
            SET
                code=:code, name=:name, progression=:progression, link=:link";
  
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->link=htmlspecialchars(strip_tags($this->link));
    
        // bind values
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":progression", $this->progression);
        $stmt->bindParam(":link", $this->link);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete course
    function delete($id) {
        // select all query
        $query = "DELETE FROM $this->table_name WHERE id=$id";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
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
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->link=htmlspecialchars(strip_tags($this->link));
    
        // bind new values
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':progression', $this->progression);
        $stmt->bindParam(':link', $this->link);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}
?>