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
            $password = parent::encript($password);
            $data = $this->getUserData($user);
            if ($data){
                //verificar si la contrasena es igual
                if ($password == $data[0]["Password"]){
                    //la contrasena es igual
                    if ($data[0]['Estado'] == "Activo"){
                        //crear token
                        $verify = $this->insertToken($data[0]['UsuarioId']);
                        if($verify){
                            // se guardo
                            $result = $_responses->response;
                            $result['result'] = array(
                                "token" => $verify
                            );
                            return $result;
                        }else{
                            return $_responses->error_500("Error interno del servidor, no hemos podido");
                        }
                    }else{
                        //El usuario esta inactivo
                        return $_responses->error_200("El usuario esta inactivo");
                    }
                }else{
                    //la contrasena no es igual
                    return $_responses->error_200("Password invalido");
                }

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

    private function insertToken($userId){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha)values('$userId', '$token', '$estado', '$date')";
        $verify = parent::nonQuery($query);
        if ($verify){
            return $token;
        }else{
            return 0;
        }
    }

}

?>