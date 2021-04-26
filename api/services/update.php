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
        $service_id = $data->id;
        if (!is_numeric($service_id)) {
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

    if (isset($data->description)) {
        $service_description = $data->description;
        if (!is_string($service_description)) {
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

    if (isset($data->price)) {
        $price = $data->price;
        if (!is_numeric($price)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Price must be float.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Price is required.")
        );
        die();
    }

    if (isset($data->active)) {
        $active = $data->active;
        if (!is_bool($active)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Active must be boolean.")
            );
            die();
        }

    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Active is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/service.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $service = new Service($db);

    $update = $service->update($data);
    $num = $update->rowCount();

    if ($num > 0) {
        http_response_code(200);

        echo json_encode(
            array("message" => "Service updated successfully.")
        );
    } else {
        http_response_code(400);

        echo json_encode(
            array("message" => "Something went wrong.")
        );
    }