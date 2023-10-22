<?php
require_once 'conection/conection.php';
require_once 'response.class.php';

class auth extends conection{

    public function login ($json){
        $_responses = new responses;
        $data = json_decode($json, true);
        if (!isset($data["user"]) || !isset($data["password"])){
            //error
            return $_responses->error_400();
        }else{
            $user = $data["user"];
            $password = $data["password"];
            $data = $this->getUserData($user);
            if ($data){
                //existe
            }else{
                //no existe
                return $_responses->error_200("El usuario '$user' no existe");
            }
        }
    }

    private function getUserData($email){
        $query = "SELECT UsuarioId, Password, Estado FROM usuarios WHERE Usuario = '$email'";
        $data = parent::getData($query);
        if (isset($data[0]["UsuarioId"])){
            return $data;
        }else{
            return 0;
        }
    }

}

?>