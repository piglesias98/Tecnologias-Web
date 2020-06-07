<?php
require_once('database.php');

function formEditable($titulo, $usuario, $accion, $editable){
  echo "<div class='contenido'>";
  echo "<h3>".$titulo."</h3>";
  echo "<h3>".$accion."</h3>";
  showFormUsuario($usuario, $accion, $editable);
  echo "</div>";
  echo "</div>";
}

function getParams($p, $f){
  if (isset($p['nombre']) or isset($p['apellidos']) or isset($p['email']) or isset($p['clave1'])
      or isset($p['clave2']) or isset($p['foto_perfil']) or isset($p['accion'])){
    $result['enviado'] = true;
    // Validación de resultados
    if(!empty($p['accion'])){
      $result['accion']=$p['accion'];
    }
    // -> nombre
    $result['err_nombre'] = '';
    if (empty($p['nombre'])){
      $result['err_nombre'] = 'El nombre no puede estar vacío';
    }else{
      $result['nombre'] = $p['nombre'];
    }
    // -> apellidos
    $result['err_apellidos'] = '';
    if (empty($p['apellidos'])){
      $result['err_apellidos'] = 'Los apellidos no pueden estar vacíos';
    }else{
      $result['apellidos'] = $p['apellidos'];
    }
    // -> email
    $result['err_email'] = '';
    if (empty($p['email'])){
      $result['err_email'] = 'El email no puede estar vacío';
    }
    else if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)){
      $result['err_email'] = 'Debe ser un email válido';
    }else{
      $result['email'] = $p['email'];
    }
    // -> contraseñas
    $result['err_clave1'] = '';
    if (empty($p['clave1'])){
      $result['err_clave1'] = 'Debe introducir una contraseña';
    }else{
      $result['clave1'] = $p['clave1'];
      // -> confirmar segunda contraseña
      $result['err_clave2'] = '';
      if (empty($p['clave2'])){
        $result['err_clave2'] = 'Debe confirmar la contraseña';
      }else if ($result['clave1'] != $p['clave2']){
        $result['err_clave2'] = 'Las contraseñas deben coincidir';
      }else{
        $result['clave2'] = $p['clave2'];
      }
    }
    // -> fotografía
    $result['err_foto']='';
    // Si ya hemos subido la imagen
    if (isset($p['foto_perfil_src'])){
      $result['foto_perfil_src'] = $p['foto_perfil_src'];
    // Si no hemos subido ninguna imagen
    }else if(empty($f['foto_perfil']['name'])){
      $result['err_foto'] = 'Debe incluir una fotografía';
    }else{
      $name = $f['foto_perfil']['name'];
      $target_dir = "uploads/";
      $target_file = $target_dir . basename($f['foto_perfil']['name']);
      // Tipo del archivo
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $validExtensions = array('jpg', 'jpeg', 'png', 'gif');
      // Comprobar que se trata de verdad de una imagen
      if (!getimagesize($f['foto_perfil']['tmp_name'])){
        $result['err_foto'] = "El archivo no es una fotografía";
      // Comprobar el tamaño del archivo
      }else if ($f['foto_perfil']["size"] > 500000){
          $result['err_foto'] = 'Lo siento, el archivo es demasiado grande';
      // Sólo permitimos JPG, JPEG y PNG
      }else if (!in_array($imageFileType, $validExtensions)){
        $result['err_foto'] = 'Lo siento, solo se permiten archivos PNG, JPG o JPEG';
      }else{
        // Guardamos la imagen
        $result['foto_perfil'] = $f['foto_perfil']['tmp_name'];
        $result['foto_perfil_src'] = $name;
        move_uploaded_file($f['foto_perfil']['tmp_name'], $target_dir.$name);
      }
    }

    //confirmar
    if(isset($p['confirmar'])){
      $result['confirmar'] = true;
    }
 }else {
    //El formulario aún no ha sido enviado
    $result['enviado'] = false;
  }
  return $result;
}


//accion = Enviar
function showFormUsuario($params, $accion, $editable){
  if ($editable == false){
    $disabled = 'readonly="readonly"';
    $disabledPic = 'disabled="disabled"';
  }else{
    $disabled = '';
    $disabledPic = '';
  }
  ?>
  <form class="login_form" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
    <label for="imagen">Foto de perfil:
      <input type="file" name="foto_perfil" <?php echo $disabledPic ?>
      <?php if (isset($params['foto_perfil_src'])){
        echo " value='".$params['foto_perfil_src']."'/><br>";
        echo "<input type='hidden' name='foto_perfil_src' value='".$params['foto_perfil_src']."'/>";
        echo "<img src='uploads/".$params['foto_perfil_src']."' /><br>";
        echo "<p> ESTA FOTO PERFIL SRC</p>";?>
      <?php }else echo "/><br>";
      if (isset($params['err_foto'])) echo "<p class = 'error'>".$params['err_foto']."</p><br>";?>
    </label>
    <label for="nombre">Nombre:
      <input type="text" name="nombre" <?php echo $disabled ?>
      <?php if (isset($params['nombre'])) echo " value='".$params['nombre']."'";?>/>
      <?php if (isset($params['err_nombre'])) echo "<p class = 'error'>".$params['err_nombre']."</p>";?><br>
    </label>
    <label for="apellidos">Apellidos:
      <input type="text" name="apellidos" <?php echo $disabled ?>
      <?php if (isset($params['apellidos'])) echo " value='".$params['apellidos']."'";?>/>
      <?php if (isset($params['err_apellidos'])) echo "<p class = 'error'>".$params['err_apellidos']."</p>";?><br>
    </label>
    <label for="email">Email:
      <input type="text" name="email" <?php echo $disabled ?>
      <?php if (isset($params['email'])) echo " value='".$params['email']."'";?>/><br>
      <?php if (isset($params['err_email'])) echo "<p class = 'error'>".$params['err_email']."</p>";?>
    </label>
    <label for="clave1">Contraseña:
      <input type="password" name="clave1" <?php echo $disabled ?>
      <?php if (isset($params['clave1'])) echo " value='".$params['clave1']."'";?>/><br>
      <?php if (isset($params['err_clave1'])) echo "<p class = 'error'>".$params['err_clave1']."</p>";?>
    </label>
    <label for="clave2">Introduzca de nuevo la contraseña:
      <input type="password" name="clave2" <?php echo $disabled ?>
      <?php if (isset($params['clave2'])) echo " value='".$params['clave2']."'";?>/><br>
      <?php if (isset($params['err_clave2'])) echo "<p class = 'error'>".$params['err_clave2']."</p>";?>
    </label>
    <?php if (isset($params['id'])) echo "<input type='hidden' name='id' value='".$params['id']."'/>";?>
    <input type="submit" name = <?php echo $accion ?> value=<?php echo $accion ?> >
  </form>
<?php
}


function enviarFormulario($params){
  ?>
  <p>Enhorabuena, <?php echo $params['nombre'] ?>!</p>
  <?php
  $params['editable']=false;
  $accion = 'confirmar';
  // showFormUsuario($params, $accion, false);
  $db = dbConnection();
  dbCrearUsuario($db, $params);
  dbDisconnection($db);
}

function showUsuario($usuario, $id){
  ?>
  <div class="contenido">
    <div class="superior">
      <div class="datos">
        <h1><?php echo $usuario['nombre'] ?></h1>
        <h2><?php echo $usuario['apellidos'] ?></h2>
        <p>Correo electrónico: <?php echo $usuario['email'] ?></p>
      </div>
      <div class="foto_perfil">
        <?php echo "<img src='uploads/".$usuario['foto_perfil_src']."' /><br>"; ?>
      </div>
    </div>
    <section class="navegacion_inferior">
      <?php
      echo "<form action='index?p=perfil' method='POST'>
            <input type='hidden' name='id' value='{$id}' />";
      echo "<input type='submit' name = 'accion' value='Editar'/>";
      ?>
    </section>
  </div>
  </div>
  <?php
}


?>
