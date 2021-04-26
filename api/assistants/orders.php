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
        $assistant_id = $_GET['id'];
        if (!is_numeric($assistant_id)) {
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

    $orders = $assistant->getSales($assistant_id);

    if ($orders->rowCount() > 0) {

        $orders_arr = array();
        $orders_arr = array();

        while ($row = $orders->fetch(PDO::FETCH_ASSOC)){

            extract($row);
            
            $order_item = array(
                "id"            => $id,
                "customer_id"   => $customer_id,
                "customer"      => $customer_name . ' ' . $customer_surename,
                "product_id"    => $product_id,
                "product"       => $product,
                "price"         => $price,
                "amount"        => $amount,
                "total"         => $total,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
            );
    
            array_push($orders_arr, $order_item);
        }
    
        http_response_code(200);
    
        echo json_encode($orders_arr);
    } else {
        http_response_code(404);
    
        echo json_encode(
            array("message" => "No order found.")
        );
    }