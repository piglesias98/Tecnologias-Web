<?php
session_start();

ini_set("display_errors", "1");
ini_set("error_reporting", E_ALL);
require "pag_comun.php";
include "header.html";


// Obtener el query string

if (!isset($_GET["p"])){
  $_GET['p'] = 'index';
}
$opc = $_GET['p'];

//Identificación de los usuarios

HTMLwidgets();

//Según el query string resaltamos el menú
HTMLnav($opc);

//Según el query string insertamos el código HTML correspondiente
if (isset($_SESSION['identificado'])){
  switch($opc) {
    case 'index': include "inicio.php"; break;
    case 'listado': include "recetas/listado.php"; break;
    case 'contacto': include "contacto.php"; break;
    case 'crear': include "recetas/crear_receta.php"; break;
    case 'crud': include "recetas/crud.php"; break;
    case 'perfil': include "usuarios/perfil.php"; break;
    case 'mis_recetas': include "recetas/listado.php"; break;
  }
}else{
  switch($opc) {
    case 'index': include "inicio.html"; break;
    case 'listado': include "recetas/listado.php"; break;
    case 'contacto': include "usuario/contacto.php"; break;
    case 'crud': include "recetas/crud.php"; break;
    case 'crear': include "error.html"; break;
    case 'registro': include "usuarios/registro.php"; break;
  }
}

include "footer.html";
 ?>
