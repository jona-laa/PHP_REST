<?php
// include database and object files
include_once './db/database.php';
include_once './classes/course.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
  
$req_method = $_SERVER['REQUEST_METHOD'];

if(isset($_GET['id'])) {
    $id = $_GET['id'];
}


// instantiate database and course object
$database = new Database();
$db = $database->getConnection();

// initialize object
$course = new Course($db);



// Endpoints
switch($req_method) {
    
    // GET
    case 'GET':
        // query courses
        if(isset($id)) {
            $result = $course->readOne($id);
        } else {
            $result = $course->read();
        }
        
        $rows = $result->rowCount();
          
        // check if more than 0 record found
        if($rows>0){
        
            $courses_arr=array();
            $courses_arr["courses"]=array();
        
            while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
          
                $course_item=array(
                    "id" => $id,
                    "code" => $code,
                    "name" => $name,
                    "progression" => $progression,
                    "link" => $link,
                    "credits" => $credits,
                    "icon" => $icon
                );
          
                array_push($courses_arr["courses"], $course_item);
            }

            http_response_code(200);
            echo json_encode($courses_arr);

        } else {
            http_response_code(404);
            echo json_encode(
                array("code" => 404, "message" => "No courses found.")
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
            !empty($data->link) &&
            !empty($data->credits) 
            // !empty($data->icon)
        ){
            $course->code = $data->code;
            $course->name = $data->name;
            $course->progression = $data->progression;
            $course->link = $data->link;
            $course->credits = $data->credits;
            $course->icon = $data->icon;
    
            if($course->create()) {
                http_response_code(201);
                    echo json_encode(
                    array("code" => 201, "message" => "New course created")
                );
            } else {
                http_response_code(503);
                echo json_encode(
                    array("code" => 503, "message" => "Something went wrong. Try again.")
                );
            }
        } else{
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("code" => 400, "message" => "Unable to create course. Data is incomplete."));
        }
        break;
    
    
    
    // DELETE
    case 'DELETE':
        if(!isset($id)) {
            http_response_code(510);
            echo json_encode(
                array("code" => 510, "message" => "No id was sent")
            );
        } else {
            if($course->delete($id)) {
                http_response_code(200);
                echo json_encode(
                    array("code" => 200, "message" => "Course deleted")
                );
            } else {
                http_response_code(503);
                echo json_encode(
                    array("code" => 503, "message" => "Sever error. Try again.")
                );
            }
        }
        break;
    


    // PUT
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        // set ID property of course to be edited
        $course->id = $data->id;
        
        // set course property values
        $course->code = $data->code;
        $course->name = $data->name;
        $course->progression = $data->progression;
        $course->link = $data->link;
        $course->credits = $data->credits;
        $course->icon = $data->icon;

        if($course->update()) {
            http_response_code(200);
            echo json_encode(
                array("code" => 200, "message" => "Course updated")
            );
        } else {
            http_response_code(503);
            echo json_encode(
                array("code" => 503, "message" => "Sever error. Try again.")
            );           
        }
        break;
}
