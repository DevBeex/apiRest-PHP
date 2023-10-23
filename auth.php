<?php
require_once 'classes/auth.class.php';
require_once 'classes/response.class.php';

$_auth = new auth;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD']== 'POST'){
    //recibir datos
    $postBody = file_get_contents("php://input");
    //Enviamos los datos al metodo login
    $dataArray = $_auth->login($postBody);
    //Devolvemos respuestas
    header('Content-Type: application/json');
    if(isset($dataArray["result"]["error_id"])){
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($dataArray);

}else {
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}


?>