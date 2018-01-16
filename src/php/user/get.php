<?php

// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
include_once '../role/object.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object User
include_once 'object.php';
$user = new User($db);
// Enviamos el parametro a obtener los datos
$user->id = $objDatos->id;

// Invocamos el obtener los datos de la base de datos
$user->get();

//Instancia a object role
$role = new Role($db);
// Enviamos el parametro a obtener los datos
$role->id = $user->s_role_id;
// Invocamos el obtener los datos de la base de datos
$role->get();

// Retornamos los datos
echo json_encode(array("transaction" => "ok", "object" => array("main" => array("id" => $user->id, "username" => $user->username, "nickname" => $user->nickname, "password" => $user->password), "system" => array("role" => array("main" => array("id" => $role->id, "rolename" => $role->rolename)))), "message" => "Se obtuvieron los datos correctamente", "code" => "user:succes:003"));
?>
