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

    if ($method != 'POST') {
        http_response_code(400);
        echo json_encode(
            array("message" => "Method ". $method ." not supported.")
        );
        die();
    }

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // validations
    if (isset($data->name)) {
        $shop_name = $data->name;
        if (!is_string($shop_name)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Name must be string.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Name is required.")
        );
        die();
    }

    if (isset($data->description)) {
        $description = $data->description;
        if (!is_string($description)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Description must be string.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Description is required.")
        );
        die();
    }

    if (isset($data->validity)) {
        $validity = $data->validity;
        if (!is_string($validity)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Validity must be string.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Validity is required.")
        );
        die();
    }

    if (isset($data->state)) {
        $state = $data->state;
        if (!is_bool($state)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "State must be boolean.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "State is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/product.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $product = new Product($db);

    $stmt = $product->create($data);
    $num = $stmt->rowCount();
    
    if ($num > 0) {
        http_response_code(200);

        echo json_encode(
            array("message" => "Product created successfully.")
        );
    } else {
        http_response_code(400);

        echo json_encode(
            array("message" => "Something went wrong.")
        );
    }
    