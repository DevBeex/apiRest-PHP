<?php
require_once 'classes/auth.class.php';
require_once 'classes/response.class.php';

$_auth = new auth;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD']== 'POST'){
    //esto permite obtener lo que esta en el body en la variable
    $postBody = file_get_contents("php://input");
    $dataArray = $_auth->login($postBody);
    print_r(json_encode($dataArray));

}else {
    echo "metodo no permitido";
}


?>