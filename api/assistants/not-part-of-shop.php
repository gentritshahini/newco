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
    include_once '../objects/assistant.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $assistant = new Assistant($db);

    $stmt = $assistant->getNotPartOfShop($shop_id);
    $num = $stmt->rowCount();

    $assistants_arr = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $assistant_item = array(
            "id"            => $id,
            "name"          => $name,
            "surename"      => $surename,
        );

        array_push($assistants_arr, $assistant_item);
    }

    http_response_code(200);

    echo json_encode($assistants_arr);