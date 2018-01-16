<?php
class Role {
  // database connection and table name
  private $conn;
  private $table_name = "role";

  // object properties
  public $id;
  public $rolename;
  public $update_date;
  public $create_date;

  // constructor con $db as database conexión
  public function __construct($db){
    $this->conn = $db;
  }

  /**
   * Metodo para crear el rol
   *
   * @return bool
   */
  function create() {
    // Armamos el query
    $query = "INSERT INTO ".$this->table_name." SET id=:id, rolename=:rolename, update_date=:updateDate, create_date=:createDate";

    // Preparamos el query a ejecutar
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->rolename=htmlspecialchars(strip_tags($this->rolename));

    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":rolename", $this->rolename);
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

  function findByRolename() {
    // Construimos el query
    $query = "SELECT id, rolename FROM " . $this->table_name . " WHERE UPPER(rolename) = :rolename LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam("rolename", strtoupper($this->rolename));

    // ejecutamos el query
    $stmt->execute();

    // Obtenemos el valor de la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cargamos los valores encontrados
    $this->rolename = $row['rolename'];
    $this->id = $row['id'];
    $this->status = (ord ( $row['status'] ) == 1) ? true : false;
    $this->update_date = $row['update_date'];
  }

  /**
   * Leemos los datos de la base de datos
   *
   * @return mixed
   */
  function findAll(){
    // Generamos el query
    $query = "SELECT id, rolename FROM " . $this->table_name . " ORDER BY rolename DESC";

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
    $query = "SELECT id, rolename, update_date FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(1, $this->id);

    // ejecutamos el query
    $stmt->execute();

    // Obtenemos el valor de la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cargamos los valores encontrados
    $this->rolename = $row['rolename'];
    $this->id = $row['id'];
    $this->update_date = $row['update_date'];
  }

  function update(){
    // Creamos el query
    $query = "UPDATE " . $this->table_name . " SET id=:id, rolename=:rolename, update_date=:updateDate WHERE id=:id";

    // Se inicializa la conexión
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->rolename=htmlspecialchars(strip_tags($this->rolename));

    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":rolename", $this->rolename);
    $stmt->bindParam(":updateDate", $this->update_date);
    $stmt->bindParam(":createDate", $this->create_date);

    try {
      // execute the query
      if ($stmt->execute()) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      echo $e.getMessage();
    }
  }
}
?>
