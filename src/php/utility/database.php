<?php
header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Allow-Headers: *");
class Database{
  // specify your own database credentials
  private $host = "localhost";
  /*Especifica el puerto para el caso que se necesite especificar un puerto diferente*/
  private $port = "3306";
  private $db_name = "grana";
  private $username = "root";
  private $password = "thavs";
  public $conn;
  /*Especifica la codificacion de la base para evitar problemas de codificacion*/
  private $charset ="utf8";
  // get the database connection
  public function getConnection(){ $this->conn = null;

    try{
      /*soporte a charset*/
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=". $this->charset, $this->username, $this->password);
      /*Aseguramos el seteo de el la codificacion para verciones legadas de php*/
      $this->conn->exec("set names " . $this->charset);
    }catch(PDOException $exception){
      echo "Connection error: " . $exception->getMessage();
    }

    return $this->conn;
  }
}
?>
