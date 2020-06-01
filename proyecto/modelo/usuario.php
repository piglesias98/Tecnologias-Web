<?php
class Usuario extends AbstractModel {
  function __construct(){
    parent::__construct();
    if(!$this->tableExists('usuarios'))
      $this->createTable();
  }

  public function get($email) {
    $r = $this->query('SELECT * FROM usuarios WHERE email=:email', ['email'=>$email]);
    return empty($r) ? null : $r;
  }

  public function createTable(){
    if (!this->tableExists('usuarios')) {
      $q = "COPIAR LA QUERY DE MYSQL WORKBENCH";
      $rr = $this->db->query($q);
    }
  }

}

 ?>
