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

    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        if (!is_numeric($product_id)) {
            echo json_encode(
                array("message" => "Parameter id must be number.")
            );
            die();
        }

    } else {
        echo json_encode(
            array("message" => "Parameter id is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/product.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $product = new Product($db);

    $stmt = $product->sales($product_id);
    $num = $stmt->rowCount();

    if ($num > 0) {

        $sales = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            $order_item = array(
                "customer"      => $customer_name . " " . $customer_surename,
                "assistant"     => $assistant_name . " " . $assistant_surename,
                "price"         => $price,
                "amount"        => $amount,
                "total"         => $total
            );

            array_push($sales, $order_item);
        }
    
        http_response_code(200);

        echo json_encode($sales);
    } else {
        http_response_code(200);
    
        echo json_encode([]);
    }