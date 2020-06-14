<?php
require_once('database/database.php');

function formEditable($titulo, $usuario, $accion, $editable){
  echo "<div class='contenido_formulario'>";
  echo "<h3>".$titulo."</h3>";
  showFormUsuario($usuario, $accion, $editable);
  echo "</div>";
  echo "</div>";
}

function getParams($p, $f){
  if(isset($p['id'])){
    $result['id'] = strip_tags($p['id']);
  }
  if(isset($p['accion'])){
    $result['accion']=strip_tags($p['accion']);
  }
  if (isset($p['form'])){
    // FORMULARIO RECETA
    if ($p['form'] == 'usuario'){
      $result['form'] = 'usuario';

      // Validación de resultados
      // -> nombre
      $result['err_nombre'] = '';
      if (empty($p['nombre'])){
        $result['err_nombre'] = 'El nombre no puede estar vacío';
      }else{
        $result['nombre'] = strip_tags($p['nombre']);
      }
      // -> apellidos
      $result['err_apellidos'] = '';
      if (empty($p['apellidos'])){
        $result['err_apellidos'] = 'Los apellidos no pueden estar vacíos';
      }else{
        $result['apellidos'] = strip_tags($p['apellidos']);
      }
      // -> email
      $result['err_email'] = '';
      if (empty($p['email'])){
        $result['err_email'] = 'El email no puede estar vacío';
      }else if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)){
        $result['err_email'] = 'Debe ser un email válido';
      }else{
        $db = dbConnection();
        $en_uso = dbCheckEmail($db, $p['email']);
        dbDisconnection($db);
        if ($en_uso){
          $result['err_email'] = 'Ese email ya está en uso, introduce otro, por favor';
        }else{
          $result['email'] = strip_tags($p['email']);
        }
      }
      // -> contraseñas (no queremos que sean editables)
      $result['err_clave1'] = '';
      $result['err_clave2'] = '';
      if ($result['accion']=='Editar' or $result['accion']=='Confirmar'){
        if (isset($p['clave1']) and !empty($p['clave1'])){
          $result['clave1'] = strip_tags($p['clave1']);
          // -> confirmar segunda contraseña
          if (empty($p['clave2'])){
            $result['err_clave2'] = 'Debe confirmar la contraseña';
          }else if ($result['clave1'] != strip_tags($p['clave2'])){
            $result['err_clave2'] = 'Las contraseñas deben coincidir';
          }else{
            $result['clave2'] = strip_tags($p['clave2']);
          }
        }
      }else if($result['accion']=='Registro'){
        if (!isset($p['clave1'])){
          $result['err_clave1'] = 'Debe introducir una contraseña';
        }else{
          $result['clave1'] = strip_tags($p['clave1']);
          // -> confirmar segunda contraseña
          if (empty($p['clave2'])){
            $result['err_clave2'] = 'Debe confirmar la contraseña';
          }else if ($result['clave1'] != $p['clave2']){
            $result['err_clave2'] = 'Las contraseñas deben coincidir';
          }else{
            $result['clave2'] = strip_tags($p['clave2']);
          }
        }
      }
      // -> fotografía
      $result['err_foto']='';
      // Si ya hemos subido la imagen
      if (isset($p['foto_perfil_src'])){
        $result['foto_perfil_src'] = strip_tags($p['foto_perfil_src']);
      // Si no hemos subido ninguna imagen
      }else if(empty($f['foto_perfil']['name'])){
        $result['err_foto'] = 'Debe incluir una fotografía';
      }else{
        $name = uniqid();
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
    }
 }else {
    //El formulario aún no ha sido enviado
    $result['form'] = 'nada';
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
        ?>
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
    <input type="hidden" name = 'form' value='usuario'/>
    <?php if (isset($params['id'])) echo "<input type='hidden' name='id' value='".$params['id']."'/>";?>
    <input type="submit" name='accion' value=<?php echo $accion ?> >
  </form>
<?php
}


function enviarFormulario($params){
  ?>
  <div class="mensaje_simple">
    <p>El usuario <?php echo $params['nombre'] ?> ha quedado registrado en el sistema</p>
  </div>
  <?php
  $params['editable']=false;
  $accion = 'confirmar';
  // showFormUsuario($params, $accion, false);
  $db = dbConnection();
  dbCrearUsuario($db, $params);
  dbDisconnection($db);
}

function verificacionEmail($params){
  ?>
  <div class="mensaje_simple">
    <p>Muy bien <?php echo $params['nombre'] ?>!</p>
    <p>Se ha enviado un correo a la dirección <?php echo $params['email'] ?>
    donde debes verificar tu cuenta y podrás registrarte</p>
  </div>
  <?php
}

function showUsuario($usuario, $id){
  ?>
  <div class="contenido_usuario">
    <div class="superior">
      <div class="datos">
        <h1><?php echo $usuario['nombre'] ?></h1>
        <h2><?php echo $usuario['apellidos'] ?></h2>
        <p>Correo electrónico: <?php echo $usuario['email'] ?></p>
        <p>Rol: <?php echo $usuario['tipo'] ?></p>
      </div>
      <div class="foto_perfil">
        <?php echo "<img src='uploads/".$usuario['foto_perfil_src']."' /><br>"; ?>
      </div>
    </div>
    <section class="navegacion_inferior">
      <?php
      if (isset($_SESSION['admin'])){
        echo "<form action='index.php?p=crud_usuarios&id=$id' method='POST'>";
        echo "<input type='submit' name = 'accion' value='Borrar'/>";
      }else{
        echo "<form action='index?p=perfil' method='POST'>";
      }
      echo "<input type='hidden' name='id' value='{$id}' />";
      echo "<input type='submit' name = 'accion' value='Editar'/>";
      ?>
    </section>
  </div>
  </div>
  <?php
}


?>
