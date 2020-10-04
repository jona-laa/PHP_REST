<?php
class Course{
    private $conn;
    private $table_name = "courses";
  
    // Course Properties
    public $id;
    public $code;
    public $name;
    public $progression;
    public $link;
    public $credits;
    public $icon;
  
    public function __construct($db){
        $this->conn = $db;
    }



    // Get All Courses
    function read(){
        $query = "SELECT id, code, name, progression, link, credits, icon FROM $this->table_name";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result;
    }



    // Get One Course
    function readOne($id){
        $query = "SELECT id, code, name, progression, link, credits, icon FROM $this->table_name WHERE id=$id";        
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result;
    }



    // Create New Course
    function create() {
       $query = "INSERT INTO 
       $this->table_name
            SET
                code=:code, name=:name, progression=:progression, link=:link, credits=:credits, icon=:icon";
  
        // Prep that Query yo
        $statement = $this->conn->prepare($query);
    
        // Better Sanitize them Datas
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->credits=htmlspecialchars(strip_tags($this->credits));
        $this->icon=htmlspecialchars(strip_tags($this->icon));
    
        // Bind those values my dude
        $statement->bindParam(":code", $this->code);
        $statement->bindParam(":name", $this->name);
        $statement->bindParam(":progression", $this->progression);
        $statement->bindParam(":link", $this->link);
        $statement->bindParam(":credits", $this->credits);
        $statement->bindParam(":icon", $this->icon);
    
        if($statement->execute()){
            return true;
        }
    
        return false;
    }



    // Delete a Course
    function delete($id) {
        $query = "DELETE FROM $this->table_name WHERE id=$id";
        $result = $this->conn->prepare($query);
        $result->execute();    
        return $result;
    }


    
    // Update Course
    function update() {
        $query = "UPDATE 
            $this->table_name
                SET
                    code = :code,
                    name = :name,
                    progression = :progression,
                    link = :link,
                    credits = :credits,
                    icon = :icon
                WHERE
                    id = :id";
    
        $statement = $this->conn->prepare($query);
    
        // Sanitize Them Stings, Son 
        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->progression=htmlspecialchars(strip_tags($this->progression));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->credits=htmlspecialchars(strip_tags($this->credits));
        $this->icon=htmlspecialchars(strip_tags($this->icon));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // Bind Values
        $statement->bindParam(':code', $this->code);
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':progression', $this->progression);
        $statement->bindParam(':link', $this->link);
        $statement->bindParam(':credits', $this->credits);
        $statement->bindParam(':icon', $this->icon);
        $statement->bindParam(':id', $this->id);
    
        if($statement->execute()){
            return true;
        }
    
        return false;
    }
}
?>