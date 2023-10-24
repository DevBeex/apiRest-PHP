<?php
require_once 'conection/conection.php';

class patient extends conection{
    
    private $table = 'pacientes';
    private $patient = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";

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

    public function post($json){
        $_responses = new responses;
        $data = json_decode($json, true);
        //El nombre que recibimos por parte del cliente es caseSensitive
        if(!isset($data['nombre']) || !isset($data['dni']) || !isset($data['correo'])){
            return $_responses->error_400();
        }else{
            $this->nombre = $data['nombre'];
            $this->dni = $data['dni'];
            $this->correo = $data['correo'];
            if (isset($data['telefono'])){$this->telefono = $data['telefono'];}
            if (isset($data['direccion'])){$this->direccion = $data['direccion'];}
            if (isset($data['codigoPostal'])){$this->codigoPostal = $data['codigoPostal'];}
            if (isset($data['genero'])){$this->genero = $data['genero'];}
            if (isset($data['fechaNacimiento'])){$this->fechaNacimiento = $data['fechaNacimiento'];}
            $resp = $this->insertPatient();
            if($resp){
                $respuesta = $_responses->response;
                $respuesta["result"] = array(
                    "pacienteId"=> $resp
                );
                return $respuesta;
            }else{
                return $_responses->error_500();
            }   
        }
    }

    private function insertPatient(){
        $query = "INSERT INTO ". $this->table." (DNI, Nombre, Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo)
        values('".$this->dni."', '".$this->nombre."', '".$this->direccion."', '".$this->codigoPostal."', '".$this->telefono."', '".$this->genero."', '".$this->fechaNacimiento."', '".$this->correo."') ";
        $resp = parent::nonQueryId($query);
        if ($resp){
            return $resp;
        }else{
            return 0;
        }
    }
}
?>