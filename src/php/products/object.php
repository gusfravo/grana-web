<?php
class Product{
  // database connection and table name
  private $conn;
  private $table_name = "product";

  // object properties
  public $id;
  public $name;
  public $description;
  public $technique;
  public $measurements;
  public $town;
  public $region;
  public $color;
  public $file;
  public $create_date;
  public $update_date;

  // constructor con $db as database conexión
  public function __construct($db){
    $this->conn = $db;
  }

  /**
   * Metodo para crear categorias
   *
   * @return bool
   */
  function create() {
    // Armamos el query
    $query = "INSERT INTO ".$this->table_name." SET id=:id, name=:name, description=:description,  technique=:technique, measurements=:measurements, town=:town, region=:region, color=:color, file=:file, create_date=:createDate, update_date=:updateDate";

    // Preparamos el query a ejecutar
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->technique=htmlspecialchars(strip_tags($this->technique));
    $this->measurements=htmlspecialchars(strip_tags($this->measurements));
    $this->town=htmlspecialchars(strip_tags($this->town));
    $this->region=htmlspecialchars(strip_tags($this->region));
    $this->color=htmlspecialchars(strip_tags($this->color));
    $this->file=htmlspecialchars(strip_tags($this->file));

    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":technique", $this->technique);
    $stmt->bindParam(":measurements", $this->measurements);
    $stmt->bindParam(":town", $this->town);
    $stmt->bindParam(":region", $this->region);
    $stmt->bindParam(":color", $this->color);
    $stmt->bindParam(":file", $this->file);
    $stmt->bindParam(":createDate", date("Y-m-d H:i:s", strtotime($this->create_date)), PDO::PARAM_STR);
    $stmt->bindParam(":updateDate", date("Y-m-d H:i:s", strtotime($this->update_date)), PDO::PARAM_STR);

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
    $stmt->bindParam(1, $this->m_uuid);

    // execute query
    if($stmt->execute()){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Leemos los datos de la base de datos
   *
   * @return mixed
   */
  function findAll(){
    // Generamos el query
    $query = "SELECT id, name, description, technique, measurements, town, region, color, file, create_date, update_date FROM " . $this->table_name . " ORDER BY name DESC";

    // Instanciamos la conexión
    $stmt = $this->conn->prepare( $query );

    // Ejecutamos el query
    $stmt->execute();

    return $stmt;
  }


  /**
   * Obtenemos el elemento a partir de su uuid
   */
  function get(){
    // Construimos el query
    $query = "SELECT id, name, description, technique, measurements, town, region, color, file, create_date, update_date FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(1, $this->m_uuid);

    // ejecutamos el query
    $stmt->execute();

    // Obtenemos el valor de la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cargamos los valores encontrados
    $this->id = $row['id'];
    $this->name = $row['name'];
    $this->description = $row['description'];
    $this->technique = $row['technique'];
    $this->measurements = $row['measurements'];
    $this->town = $row['town'];
    $this->region = $row['region'];
    $this->color = $row['color'];
    $this->file = $row['file'];
    $this->create_date = $row['create_date'];
    $this->update_date = $row['update_date'];
  }

  function update(){
    // Creamos el query
    $query = "UPDATE " . $this->table_name . " SET id=:id, name=:name, description=:description,  technique=:technique, measurements=:measurements, town=:town, region=:region, color=:color, file=:file, create_date=:createDate, update_date=:updateDate WHERE id=:id";

    // Se inicializa la conexión
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->technique=htmlspecialchars(strip_tags($this->technique));
    $this->measurements=htmlspecialchars(strip_tags($this->measurements));
    $this->town=htmlspecialchars(strip_tags($this->town));
    $this->region=htmlspecialchars(strip_tags($this->region));
    $this->color=htmlspecialchars(strip_tags($this->color));
    $this->file=htmlspecialchars(strip_tags($this->file));

    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":technique", $this->technique);
    $stmt->bindParam(":measurements", $this->measurements);
    $stmt->bindParam(":town", $this->town);
    $stmt->bindParam(":region", $this->region);
    $stmt->bindParam(":color", $this->color);
    $stmt->bindParam(":file", $this->file);
    $stmt->bindParam(":createDate", date("Y-m-d H:i:s", strtotime($this->create_date)), PDO::PARAM_STR);
    $stmt->bindParam(":updateDate", date("Y-m-d H:i:s", strtotime($this->update_date)), PDO::PARAM_STR);

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
