<?php
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
    include_once '../config/database.php';
    include_once '../objects/location.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $location = new Location($db);

    $stmt = $location->getAll();
    $num = $stmt->rowCount();

    if ($num > 0) {
    
        $locations_arr = array();
        $locations_arr["locations"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $location_item = array(
                "id"            => $id,
                "city"          => $city,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
                "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
            );
    
            array_push($locations_arr["locations"], $location_item);
        }
    
        http_response_code(200);
    
        echo json_encode($locations_arr);
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "No location found.")
        );
    }