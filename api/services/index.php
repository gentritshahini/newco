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
    include_once '../objects/service.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $service = new Service($db);

    $stmt = $service->getAll();
    $num = $stmt->rowCount();

    if ($num > 0) {
    
        $services_arr = array();
        $services_arr["services"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $service_item = array(
                "id"            => $id,
                "description"   => $description,
                "price"         => $price,
                "active"        => $active,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
                "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
            );
    
            array_push($services_arr["services"], $service_item);
        }
    
        http_response_code(200);
    
        echo json_encode($services_arr);
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "No service found.")
        );
    }