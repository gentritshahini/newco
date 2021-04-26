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

    if (isset($data->surename)) {
        $surename = $data->surename;
        if (!is_string($surename)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Surename must be string.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Surename is required.")
        );
        die();
    }

    if (isset($data->address)) {
        $address = $data->address;
        if (!is_string($address)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Address must be string.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Address is required.")
        );
        die();
    }

    if (isset($data->phone_number)) {
        $phone_number = $data->phone_number;
        if (!is_string($phone_number)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Phone number must be string.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Phone number is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/customer.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $customer = new Customer($db);

    $stmt = $customer->create($data);
    $num = $stmt->rowCount();
    
    if ($num > 0) {
        http_response_code(200);

        echo json_encode(
            array("message" => "Customer created successfully.")
        );
    } else {
        http_response_code(400);

        echo json_encode(
            array("message" => "Something went wrong.")
        );
    }
    