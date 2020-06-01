<?php
abstract class AbstractModel {
  protected $db;

  public function __construct(){
    $this -> db = Database::getInstance();
  }

  protected function query($select, $params=[]){
    try{
      if (empty($params))
        $pq = $this->db->query($select);
      else{
        $pq = $this->db->prepare($select);
        $pq->execute($params);
      }
      $result = $pq->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e){
      $result = null;
    }
    return $result;
  }
}
?>
