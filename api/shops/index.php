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
    include_once '../objects/shop.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $shop = new Shop($db);

    $stmt = $shop->getAll();
    $num = $stmt->rowCount();

    if ($num > 0) {
    
        $shops_arr = array();
        $shops_arr["shops"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $shop_item = array(
                "id"            => $id,
                "name"          => $name,
                "location"      => $location,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
                "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
            );
    
            array_push($shops_arr["shops"], $shop_item);
        }
    
        http_response_code(200);
    
        echo json_encode($shops_arr);
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "No shops found.")
        );
    }