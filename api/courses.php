<?php
// include database and object files
include_once './config/database.php';
include_once './classes/course.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
$req_method = $_SERVER['REQUEST_METHOD'];

if(isset($_GET['id'])) {
    $id = $_GET['id'];
}


// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$course = new Course($db);



// Endpoints
switch($req_method) {
    
    // GET
    case 'GET':
        // query products
        if(isset($id)) {
            $stmt = $course->readOne($id);
        } else {
            $stmt = $course->read();
        }
        $num = $stmt->rowCount();
          
        // check if more than 0 record found
        if($num>0){
        
            $courses_arr=array();
            $courses_arr["records"]=array();
        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
          
                $course_item=array(
                    "id" => $id,
                    "code" => $code,
                    "name" => $name,
                    "progression" => $progression,
                    "link" => $link
                );
          
                array_push($courses_arr["records"], $course_item);
            }

            http_response_code(200);
            echo json_encode($courses_arr);

        } else {
            http_response_code(404);
            echo json_encode(
                array("message" => "No courses found.")
            );
        }
        break;

        
        
    // POST
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->code) &&
            !empty($data->name) &&
            !empty($data->progression) &&
            !empty($data->link)
        ){
            $course->code = $data->code;
            $course->name = $data->name;
            $course->progression = $data->progression;
            $course->link = $data->link;
    
            if($course->create()) {
                http_response_code(201);
                    echo json_encode(
                    array("message" => "New course created")
                );
            } else {
                http_response_code(503);
                echo json_encode(
                    array("message" => "Sever error. Try again.")
                );
            }
        } else{
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
        }
        break;
    
    
    
    // DELETE
    case 'DELETE':
        if(!isset($id)) {
            http_response_code(510);
            echo json_encode(
                array("message" => "No id was sent")
            );
        } else {
            if($course->delete($id)) {
                http_response_code(200);
                echo json_encode(
                    array("message" => "Course deleted")
                );
            } else {
                http_response_code(503);
                echo json_encode(
                    array("message" => "Sever error. Try again.")
                );
            }
        }
        break;
    


    // PUT
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        // set ID property of product to be edited
        $product->id = $data->id;
        
        // set product property values
        $product->code = $data->code;
        $product->name = $data->name;
        $product->progression = $data->progression;
        $product->link = $data->link;

        if($course->update()) {
            http_response_code(200);
            echo json_encode(
                array("message" => "Course updated")
            );
        } else {
            http_response_code(503);
            echo json_encode(
                array("message" => "Sever error. Try again.")
            );           
        }
        break;
}
