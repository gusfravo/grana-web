<?php
error_reporting(0);

class User {
  // database connection and table name
  private $conn;
  private $table_name = "user";

  // object properties
  public $id;
  public $username;
  public $nickname;
  public $password;
  public $physical_person_id;
  public $role_id;
  public $update_date;
  public $create_date;

  // constructor con $db as database conexión
  public function __construct($db){
    $this->conn = $db;
  }

  /**
   * Metodo para crear physical person
   *
   * @return bool
   */
  function create() {
    // Armamos el query
    $query = "INSERT INTO ".$this->table_name." SET id=:id, username=:username, nickname=:nickname, password=:password, physical_person_id=:physicalPersonId, role_id=:roleId, update_date=:updateDate, create_date=:createDate";

    // Preparamos el query a ejecutar
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->nickname=htmlspecialchars(strip_tags($this->nickname));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->physical_person_id=htmlspecialchars(strip_tags($this->physical_person_id));
    $this->role_id=htmlspecialchars(strip_tags($this->role_id));


    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":nickname", $this->nickname);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":physicalPersonId", $this->physical_person_id);
    $stmt->bindParam(":roleId", $this->role_id);
    $stmt->bindParam(":updateDate", $this->update_date);
    $stmt->bindParam(":createDate", $this->create_date);

    // Ejecutamos el query
    if($stmt->execute()) {
      return true;
    } else {
      echo print_r($stmt->errorInfo());
      return false;
    }
  }

  /**
   * Elimina el registro indicado
   *
   * @return bool
   */
  function delete(){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // bind id of record to delete
    $stmt->bindParam(1, $this->id);

    // execute query
    if($stmt->execute()){
      return true;
    }else{
      return false;
    }
  }

  function findByUsername() {
    // Construimos el query
    $query = "SELECT id, username, nickname, password, s_role_id FROM " . $this->table_name . " WHERE UPPER(username) = :username LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(":username", strtoupper($this->username));

    // ejecutamos el query
    $stmt->execute();

    // Obtenemos el valor de la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cargamos los valores encontrados
    $this->username = $row['username'];
    $this->id = $row['id'];
    $this->nickname = $row['nickname'];
    $this->password = $row['password'];
    $this->physical_person_id = $row['physical_person_id'];
    $this->s_role_id = $row['role_id'];
    $this->s_update_date = $row['update_date'];
  }

  /**
   * Para buscar por rol
   *
   * @return mixed
   */
  function findAllByRole($roleId){
    // Generamos el query
    $query = "SELECT id, username, nickname, password, physical_person_id, role_id FROM " . $this->table_name . " WHERE role_id = :roleId ORDER BY role_id DESC";

    // Instanciamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(":roleId", $roleId);

    // Ejecutamos el query
    $stmt->execute();

    return $stmt;
  }


  /**
   * Leemos los datos de la base de datos
   *
   * @return mixed
   */
  function findAll(){
    // Generamos el query
    $query = "SELECT id, username, nickname, password, validate_contact, physical_person_id, role_id FROM " . $this->table_name . " ORDER BY username DESC";

    // Instanciamos la conexión
    $stmt = $this->conn->prepare( $query );

    // Ejecutamos el query
    $stmt->execute();

    return $stmt;
  }


  /**
   * Obtenemos el elemento a partir de su id
   */
  function get(){
    // Construimos el query
    $query = "SELECT id, username, nickname, password, physical_person_id, role_id, FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(1, $this->id);

    // ejecutamos el query
    $stmt->execute();

    // Obtenemos el valor de la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cargamos los valores encontrados
    $this->username = $row['username'];
    $this->id = $row['id'];
    $this->nickname = $row['nickname'];
    $this->password = $row['password'];
    $this->physical_person_id = $row['physical_person_id'];
    $this->role_id = $row['role_id'];
    $this->update_date = $row['update_date'];
  }

  function isUser($user, $password) {
    // Construimos el query
    $query = "SELECT id, username, nickname, password, physical_person_id, role_id FROM " . $this->table_name . " WHERE username =:username AND password = :password LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(":username", $user);
    $stmt->bindParam(":password", $password);

    // ejecutamos el query
    $stmt->execute();
    // Ejecutamos el query
    if($stmt->execute()) {
      // Obtenemos el valor de la base de datos
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ( empty($row['id'])) {
        return false;
      } else {
        // Cargamos los valores encontrados
        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->nickname = $row['nickname'];
        $this->password = $row['password'];
        $this->physical_person_id = $row['physical_person_id'];
        $this->role_id = $row['role_id'];
        $this->s_update_date = $row['update_date'];
        return true;
      }
    } else {
      echo print_r($stmt->errorInfo());
    }
    return false;
  }

  function update(){
    // Creamos el query
    $query = "UPDATE " . $this->table_name . " SET id=:id, username=:username, nickname=:nickname, password=:password, physical_person_id=:physicalPersonId, role_id=:roleId, update_date=:updateDate WHERE id=:id";

    // Se inicializa la conexión
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->nickname=htmlspecialchars(strip_tags($this->nickname));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->physical_person_id=htmlspecialchars(strip_tags($this->physical_person_id));
    $this->role_id=htmlspecialchars(strip_tags($this->role_id));



    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":username", $this->m_username);
    $stmt->bindParam(":nickname", $this->m_nickname);
    $stmt->bindParam(":password", $this->m_password);
    $stmt->bindParam(":physicalPersonId", $this->physical_person_id);
    $stmt->bindParam(":roleId", $this->role_id);
    $stmt->bindParam(":sUpdateDate", $this->s_update_date);
    $stmt->bindParam(":sCreateDate", $this->s_create_date);

    try {
      // execute the query
      if ($stmt->execute()) {
        return true;
      } else {
        echo print_r($stmt->errorInfo());
        return false;
      }
    } catch (Exception $e) {
      echo $e.getMessage();
    }
  }
}
?>
