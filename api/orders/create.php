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
    if (isset($data->product_id)) {
        $product_id = $data->product_id;
        if (!is_numeric($product_id)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Product id must be number.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Product id is required.")
        );
        die();
    }

    if (isset($data->customer_id)) {
        $customer_id = $data->customer_id;
        if (!is_numeric($customer_id)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Customer id must be number.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Customer id is required.")
        );
        die();
    }

    if (isset($data->assistant_id)) {
        $assistant_id = $data->assistant_id;
        if (!is_numeric($assistant_id)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Assistant id must be number.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Assistant id is required.")
        );
        die();
    }

    if (isset($data->amount)) {
        $amount = $data->amount;
        if (!is_numeric($amount)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Amount must be number.")
            );
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array("message" => "Amount is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/order.php';
    include_once '../objects/product.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $product = new Product($db);

    $stmt = $product->price($product_id);
    $num = $stmt->rowCount();

    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        extract($row);
        
        $order = new Order($db);

        $stmt = $order->create($data, $price);
        $num = $stmt->rowCount();
        
        if ($num > 0) {
            http_response_code(200);

            echo json_encode(
                array("message" => "Order created successfully.")
            );
        } else {
            http_response_code(400);

            echo json_encode(
                array("message" => "Something went wrong.")
            );
        }
    } else {
        http_response_code(400);

        echo json_encode(
            array("message" => "No Product found.")
        );
    }

    
    