<?php
require_once 'conection/conection.php';

class patient extends conection{
    
    private $table = 'pacientes';

    public function listPatients($page = 1){
        $start = 0;
        $quantity = 100;
        if($page > 1){
            $start = ($quantity*($page-1)) +1;
            $quantity = $quantity * $page;
        }
        $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " . $this->table. " limit $start, $quantity ";
        $data = parent::getData($query);
        return $data;
    }

    public function getPatient($id){
        $query = "SELECT * FROM $this->table WHERE PacienteId = $id ";
        return parent::getData($query);
    }
}

?>