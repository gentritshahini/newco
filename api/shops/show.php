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
    
    // validation of parameter
    if (isset($_GET['id'])) {
        $shop_id = $_GET['id'];
        if (!is_numeric($shop_id)) {
            http_response_code(404);
            echo json_encode(
                array("message" => "Parameter id must be number.")
            );
            die();
        }

    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Parameter id is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/shop.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $shop = new Shop($db);

    $stmt = $shop->get($shop_id);
    $num = $stmt->rowCount();

    if ($num > 0) {
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        extract($row);
    
        $shop_item = array(
            "id"            => $id,
            "name"          => $name,
            "location_id"   => (int)$location_id,
            "location"      => $location,
            "created_at"    => date('H:i d M Y', strtotime($created_at)),
            "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
        );
    
        http_response_code(200);
    
        echo json_encode($shop_item);
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "No shops found.")
        );
    }