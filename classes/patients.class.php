<?php
require_once 'conection/conection.php';

class patient extends conection{
    
    private $table = 'pacientes';
    private $patient = "";
    private $pacienteId = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";
    private $token = "";
    //90138f9e05f75d6615bd64fc426d40f6

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

        if(!isset($data['token'])){
            return $_responses->error_401();
        }else{
            $this->token = $data['token'];
            $arrayToken = $this->searchToken();
            if ($arrayToken) {
                // El nombre que recibimos por parte del cliente es caseSensitive
                if (!isset($data['nombre']) || !isset($data['dni']) || !isset($data['correo'])) {
                    return $_responses->error_400();
                } else {
                    $this->nombre = $data['nombre'];
                    $this->dni = $data['dni'];
                    $this->correo = $data['correo'];
                    if (isset($data['telefono'])) {
                        $this->telefono = $data['telefono'];
                    }
                    if (isset($data['direccion'])) {
                        $this->direccion = $data['direccion'];
                    }
                    if (isset($data['codigoPostal'])) {
                        $this->codigoPostal = $data['codigoPostal'];
                    }
                    if (isset($data['genero'])) {
                        $this->genero = $data['genero'];
                    }
                    if (isset($data['fechaNacimiento'])) {
                        $this->fechaNacimiento = $data['fechaNacimiento'];
                    }
                    $resp = $this->insertPatient();
                    if ($resp) {
                        $respuesta = $_responses->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $resp
                        );
                        return $respuesta;
                    } else {
                        return $_responses->error_500();
                    }
                }

            }else{
                return $_responses->error_401("El token enviado es invalido o ha caducado");
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

    public function put($json){
        $_responses = new responses;
        $data = json_decode($json, true);
        //El nombre que recibimos por parte del cliente es caseSensitive

        if(!isset($data['token'])){
            return $_responses->error_401();
        }else{
            $this->token = $data['token'];
            $arrayToken = $this->searchToken();
            if($arrayToken){
                if(!isset($data['pacienteId'])){
                    return $_responses->error_400();
                }else{
                    $this->pacienteId = $data['pacienteId'];
                    if (isset($data['nombre'])){$this->nombre = $data['nombre'];}
                    if (isset($data['dni'])){$this->dni = $data['dni'];}
                    if (isset($data['correo'])){$this->correo = $data['correo'];}
                    if (isset($data['telefono'])){$this->telefono = $data['telefono'];}
                    if (isset($data['direccion'])){$this->direccion = $data['direccion'];}
                    if (isset($data['codigoPostal'])){$this->codigoPostal = $data['codigoPostal'];}
                    if (isset($data['genero'])){$this->genero = $data['genero'];}
                    if (isset($data['fechaNacimiento'])){$this->fechaNacimiento = $data['fechaNacimiento'];}
                    $resp = $this->updatePatient();
                    if($resp){
                        $respuesta = $_responses->response;
                        $respuesta["result"] = array(
                            "pacienteId"=> $this->pacienteId
                        );
                        return $respuesta;
                    }else{
                        return $_responses->error_500();
                    }   
                }

            }else{
                return $_responses->error_401("El token enviado es invalido o ha caducado");
            }
        }
    }
    private function updatePatient(){
        $query = "UPDATE $this->table SET Nombre = '$this->nombre', Direccion = '$this->direccion', DNI = '$this->dni', CodigoPostal = '$this->codigoPostal', Telefono = '$this->telefono', Genero = '$this->genero', FechaNacimiento = '$this->fechaNacimiento', Correo = '$this->correo' WHERE PacienteId = '$this->pacienteId'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    public function delete($json)
    {
        $_responses = new responses;
        $data = json_decode($json, true);

        if (!isset($data['token'])) {
            return $_responses->error_401();
        } else {
            $this->token = $data['token'];
            $arrayToken = $this->searchToken();
            if ($arrayToken) {
                //El id que recibimos por parte del cliente es caseSensitive
                if (!isset($data['pacienteId'])) {
                    return $_responses->error_400();
                } else {
                    $this->pacienteId = $data['pacienteId'];
                    $resp = $this->deletePatient();
                    if ($resp) {
                        $respuesta = $_responses->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $this->pacienteId
                        );
                        return $respuesta;
                    } else {
                        return $_responses->error_500();
                    }
                }
            } else {
                return $_responses->error_401("El token enviado es invalido o ha caducado");
            }
        }
    }

    private function deletePatient(){
        $query = "DELETE FROM $this->table WHERE PacienteId = '$this->pacienteId'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    private function searchToken(){
        $query = "SELECT TokenId, UsuarioId, Estado from usuarios_token WHERE Token = '$this->token' AND Estado = 'Activo' ";
        $resp = parent::getData($query);
        if ($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function updateToken($tokenId){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenId'";
        $resp = parent::nonQuery($query);
        if ($resp >=1 ){
            return $resp;
        }else{
            return 0;
        }
    }

}
