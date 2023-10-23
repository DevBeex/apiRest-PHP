<?php
require_once 'classes/response.class.php';
require_once 'classes/patients.class.php';

$_responses = new responses;
$_patients = new patient;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $listPatients = $_patients->listPatients($page);
        echo json_encode($listPatients);
    }else if (isset($_GET['id'])){
        $pacientId = $_GET['id'];
        $patientData = $_patients->getPatient($pacientId);
        echo json_encode($patientData);
    }

}else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo 'hola post';
}else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    echo 'hola put';
}else if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    echo 'hola delete';
}else{
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}

?>