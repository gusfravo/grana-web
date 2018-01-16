<?php

error_reporting(0);

// Volcamos a un objeto nativo de PHP
$data = json_decode(file_get_contents("php://input"));

include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();
//Instancia al objeto user
include_once './object.php';
include_once '../role/object.php';

$user = new User($db);

// Validamos si exite un usuario con el mismo username
if ($user->isUser($data->username, $data->password)) {
  // iniciamos sessión en PHP
  session_start();
  $_SESSION['token'] = uniqid('grana_');
  $_SESSION['username'] = $user->username;
  $_SESSION['id'] = $user->id;
  //traemos de vuelta el rol del usuario.
  $role = new Role($db);
  // Enviamos el parametro a obtener los datos
  $role->id = $user->role_id;
  // Invocamos el obtener los datos de la base de datos
  $role->get();

  $_SESSION['role'] = $role->rolename;
  //Si es un proveedor enviamos el uuid del proveedor
  if($role->rolename == "administrator"){
    echo json_encode(array("transaction" => "ok", "object" => array("token" => $_SESSION['token'], "username" => $_SESSION['username'], "role" => array("id"=>$user->role_id,"rolename" =>$_SESSION['role']) , "id" => $_SESSION['id']), "message" => "Acceso al sistema correctamente", "code" => "login:succes:002"));
  }else{
    echo json_encode(array("transaction" => "ok", "object" => array("token" => $_SESSION['token'], "username" => $_SESSION['username'], "role" => array("id"=>$user->role_id,"rolename" =>$_SESSION['role']), "id" => $_SESSION['id']), "message" => "Acceso al sistema correctamente", "code" => "login:succes:001"));
  }
}else {
  echo json_encode(array("transaction" => "bad", "message" => "Error el usuario ó contraseña son incorrectos", "code" => "login:error:001"));
  //print "error";
}

?>
