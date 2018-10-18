<?php
include 'util/formatter.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
// header('Content-Type: application/json');

$result = null;

try {
    $params = $_REQUEST;
    $route = ucfirst(strtolower($params['route'])) . 'Controller';
    $controller = ucfirst(strtolower($params['route'])) . 'Controller';
   
    // remove o parametro da rota, pois senão vai tentar enviar isso nos insert/update
    unset($params['route']);

    $action = strtolower($_SERVER['REQUEST_METHOD']);

    if (file_exists("controller/{$controller}.php")) {
        include_once "controller/{$controller}.php";
    } else {
        throw new Exception('Não existe essa rota =P');
    }

    if ($action === 'patch') {
        $data = explode('&', urldecode(file_get_contents('php://input')));
        
        for ($i = 0; $i < count($data); $i++) {
            $keyValue = explode('=', $data[$i]);
            if (!array_key_exists($keyValue[0], $params)) {
                $params[$keyValue[0]] = $keyValue[1];
            }
        }
    }

    $controller = new $controller($params);
    
    if (method_exists($controller, $action) === false) {
        throw new Exception('Método requisitado é inválido.');
        exit();
    }

    $result = $controller->$action();
    
} catch (Exception $e) {
    throw new Error('Essa rota náo existe ou o método pedido é inválido!');
}

echo safe_json_encode($result);
exit();
