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
    include_once '../objects/customer.php';

    $database = new Database();
    $db = $database->getConnection();
    
    $customer = new Customer($db);

    $stmt = $customer->getAll();
    $num = $stmt->rowCount();
    
    if ($num > 0) {
    
        $customers_arr = array();
        $customers_arr["customers"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $customer_item = array(
                "id"            => $id,
                "name"          => $name,
                "surename"      => $surename,
                "address"       => $address,
                "phone_number"  => $phone_number,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
                "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
            );
    
            array_push($customers_arr["customers"], $customer_item);
        }
    
        http_response_code(200);
    
        echo json_encode($customers_arr);
    } else {
        http_response_code(404);
    
        echo json_encode(
            array("message" => "No customers found.")
        );
    }