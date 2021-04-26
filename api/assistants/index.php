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
    include_once '../objects/assistant.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $assistant = new Assistant($db);

    $stmt = $assistant->getAll();
    $num = $stmt->rowCount();

    if ($num > 0) {
    
        $assistants_arr = array();
        $assistants_arr["assistants"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $assistant_item = array(
                "id"            => $id,
                "name"          => $name,
                "surename"      => $surename,
                "shop"          => $shop,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
                "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
            );
    
            array_push($assistants_arr["assistants"], $assistant_item);
        }
    
        http_response_code(200);
    
        echo json_encode($assistants_arr);
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "No assistants found.")
        );
    }