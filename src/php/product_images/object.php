<?php

class ProductImages {
  // database connection and table name
  private $conn;
  private $table_name = "product_images";

  // object properties
  public $id;
  public $product_id;
  public $image;

  // constructor con $db as database conexión
  public function __construct($db){
    $this->conn = $db;
  }

  /**
   * Crea una relacion entre proveedor y clasificacion
   *
   * @return bool
   */
  function create() {
    // Armamos el query
    $query = "INSERT INTO ".$this->table_name." SET id=:id, product_id=:productId, image=:image";

    // Preparamos el query a ejecutar
    $stmt = $this->conn->prepare($query);

    // Valores enviados por el post
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->image=htmlspecialchars(strip_tags($this->image));
    $this->product_id=htmlspecialchars(strip_tags($this->product_id));

    // Bindeamos los valores
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":image", $this->image);
    $stmt->bindParam(":productId", $this->product_id);

    // Ejecutamos el query
    if($stmt->execute()) {
      return true;
    } else {
      echo print_r($stmt->errorInfo());
      return false;
    }
  }

  /**
   * Elimina todos las imagenes de un producto
   * @return bool
   */
  function deleteAllByProduct($category){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE product_id = ?";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // bind id of record to delete
    $stmt->bindParam(1, $category);

    // execute query
    if($stmt->execute()){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Leemos las categorias de un producto
   *
   * @return mixed
   */
  function findAllByProduct($product){
    // Generamos el query
    $query = "SELECT id, image, product_id FROM " . $this->table_name . " WHERE product_id = :productId ORDER BY product_id DESC";

    // Instanciamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(":productId", $product);

    // Ejecutamos el query
    $stmt->execute();

    return $stmt;
  }


  /**
   * Obtenemos el elemento a partir de su id
   */
  function get(){
    // Construimos el query
    $query = "SELECT id, image, product_id FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

    // inicializamos la conexión
    $stmt = $this->conn->prepare( $query );

    // bind el parametro al query
    $stmt->bindParam(1, $this->id);


    // ejecutamos el query
    $stmt->execute();

    // Obtenemos el valor de la base de datos
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cargamos los valores encontrados
    $this->id = $row['id'];
    $this->image = $row['image'];
    $this->product_id = $row['product_id'];
  }

}
?>
