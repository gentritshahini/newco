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

    if ($method != 'DELETE') {
        http_response_code(400);
        echo json_encode(
            array("message" => "Method ". $method ." not supported.")
        );
        die();
    }

    // validation
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if (!is_numeric($id)) {
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

    include_once '../config/database.php';
    include_once '../objects/service.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $service = new Service($db);

    $stmt = $service->delete($id);
    $row = $stmt->rowCount();

    if ($row > 0) {
        http_response_code(200);

        echo json_encode(
            array("message" => "Service deleted successfully.")
        );
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "Service not found.")
        );
    }

    