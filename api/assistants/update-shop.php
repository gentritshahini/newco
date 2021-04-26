<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header("HTTP/1.1 200 OK");
        die();
    }

    if ($method != 'PUT') {
        http_response_code(400);
        echo json_encode(
            array("message" => "Method ". $method ." not supported.")
        );
        die();
    }

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // validations
    if (isset($data->id)) {
        $shop_id = $data->id;
        if (!is_numeric($shop_id)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Id must be number.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Id is required.")
        );
        die();
    }

    if (isset($data->shop_id)) {
        $shop_id = $data->shop_id;
        if (!is_numeric($shop_id)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Shop id must be number.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Shop is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/assistant.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $assistant = new Assistant($db);

    $update = $assistant->updateShop($data);
    $num = $update->rowCount();

    if ($num > 0) {
        http_response_code(200);

        echo json_encode(
            array("message" => "Assistant updated successfully.")
        );
    } else {
        http_response_code(400);

        echo json_encode(
            array("message" => "Something went wrong.")
        );
    }