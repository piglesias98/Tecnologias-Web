<?php
require_once('database/database.php');
require_once('recetas/database_receta.php');
require_once('recetas/formulario_receta.php');

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);


if (isset($params['id'])){
  $db = dbConnection();
  $receta = dbGetReceta($db, $params['id']);
  //Obtenemos el usuario porque necesitaremos el autor
  $autor = 'Anonimo';
  if (isset($receta['idautor'])){
    $usuario = dbGetUsuario($db, $receta['idautor']);
    $autor = $usuario['nombre']." ".$usuario['apellidos'];
  }
  $receta['autor']=$autor;
  //Si ya tenemos la confirmación modificar usuario y mostrar final
  if (isset($params['confirmar'])){
    echo "entro en confirmaaaaaar";
    echo $params['titulo'];
    $msg = dbModificarReceta($db, $params['id'], $params);
    if ($msg == true){
      echo "<p>La receta ".$params['titulo']." ha sido atualizada</p>";
    }else{
      echo "<p class='error'> La receta no se ha podido actualizar </p>";
    }
    // hay que obtener de nuevo la receta porque ha sido actualizada
    // $receta = dbGetReceta($db, $params['id']);
    // // El autor no cambia
    // $receta['autor']=$autor;
    // showReceta($receta, $params['id']);
  //Si le hemos pulsado el botón de editar recuperamos los datos de $usuario
  //y ponemos un form editable
  }else if(isset($params['accion']) && $params['accion']=='Editar'){
    formEditable('Edita la receta',$receta, 'enviar', true);
  //Si ya hemos editado y todos los valores son correctos
  }else if (isset($params['enviado']) && $params['enviado']==true && $params['err_titulo']=='' && $params['err_descripcion']==''
        && $params['err_ingredientes']=='' && $params['err_preparacion'] ==''){
      //Pedir confirmación
      $params['editable']=false;
      echo "TITULOOOOOOOOOO";
      echo $params['titulo'];
      $accion = 'confirmar';
      showFormReceta($params, $accion, false);
  // Si hemos editado pero hay algunos errores
  }else if(isset($params['enviado']) && $params['enviado']==true){
    showFormReceta($params, 'enviar', true);
  }else{
    //Si no se han recibido parámetros
    showReceta($receta, $params['id']);
  }
}else{
  echo "NO está set";
}


?>
