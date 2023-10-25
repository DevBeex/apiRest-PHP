<?php
require_once 'classes/response.class.php';
require_once 'classes/patients.class.php';

$_responses = new responses;
$_patients = new patient;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $listPatients = $_patients->listPatients($page);
        header("Content-Type: application/json");
        echo json_encode($listPatients);
        http_response_code(200);
    }else if (isset($_GET['id'])){
        $pacientId = $_GET['id'];
        $patientData = $_patients->getPatient($pacientId);
        header("Content-Type: application/json");
        echo json_encode($patientData);
        http_response_code(200);
    }

}else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //recibir datos por json
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $dataArray = $_patients->post($postBody);
    //devolver una respuesta
    header('Content-Type: application/json');
    if(isset($dataArray["result"]["error_id"])){
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($dataArray);

}else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    //recibir datos por json
    $postBody = file_get_contents("php://input");
    //Enviar datos
    $dataArray = $_patients->put($postBody);
    //devolver una respuesta
    header('Content-Type: application/json');
    if(isset($dataArray["result"]["error_id"])){
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($dataArray);


}else if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    //recibir datos por json
    $postBody = file_get_contents("php://input");
    //Enviar datos
    $dataArray = $_patients->delete($postBody);
    // devolver una respuesta
    header('Content-Type: application/json');
    if(isset($dataArray["result"]["error_id"])){
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($dataArray);

}else{
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}

?>