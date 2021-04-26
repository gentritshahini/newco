<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method != 'GET') {
        http_response_code(400);
        echo json_encode(
            array("message" => "Method ". $method ." not supported.")
        );
        die();
    }
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/service.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize object
    $service = new Service($db);

    // query products
    $stmt = $service->topSold();
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if ($num > 0) {

        $serv["services"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
 
            $top_sold = [
                "id"            => $id,
                "description"   => $description,
                "income"        => $income,
            ];

            array_push($serv["services"], $top_sold);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show top sold services data in json format
        echo json_encode($serv);
    } else {
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no service is sold
        echo json_encode(
            array("message" => "No service is sold.")
        );
    }