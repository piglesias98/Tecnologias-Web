<?php

/*
Fichero principal que gestiona las distintas partes de la página web en función
 del parámetro GET p y del tipo de usuario, identificado, no identificado o admin.
*/


DEFINE('DESPLIEGUE', 'void');
// DEFINE('DESPLIEGUE', 'local');

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
if (isset($_SESSION['admin'])){
  switch($opc) {
    case 'index': include "inicio.php"; break;
    case 'listado': include "recetas/listado.php"; break;
    case 'crear': include "recetas/crud_recetas.php"; break;
    case 'crud_recetas': include "recetas/crud_recetas.php"; break;
    case 'perfil': include "usuarios/crud_usuarios.php"; break;
    case 'crud_usuarios': include "usuarios/crud_usuarios.php"; break;
    case 'mis_recetas': include "recetas/listado.php"; break;
    case 'gestion': include "admin/gestion.php"; break;
    case 'log': include "admin/log.php"; break;
  }
}else if (isset($_SESSION['identificado'])){
  switch($opc) {
    case 'index': include "inicio.php"; break;
    case 'listado': include "recetas/listado.php"; break;
    case 'crear': include "recetas/crud_recetas.php"; break;
    case 'crud_recetas': include "recetas/crud_recetas.php"; break;
    case 'perfil': include "usuarios/crud_usuarios.php"; break;
    case 'mis_recetas': include "recetas/listado.php"; break;
    case 'gestion': include "error.html"; break;
    case 'log': include "error.html"; break;
  }
}else{
  switch($opc) {
    case 'index': include "inicio.php"; break;
    case 'listado': include "recetas/listado.php"; break;
    case 'crud_recetas': include "recetas/crud_recetas.php"; break;
    case 'registro': include "usuarios/crud_usuarios.php"; break;
    case 'perfil': include "error.html"; break;
    case 'crear': include "error.html"; break;
    case 'mis_recetas': include "error.html"; break;
    case 'gestion': include "error.html"; break;
    case 'log': include "error.html"; break;
    case 'confirmacion': include "usuarios/crud_usuarios.php"; break;
  }
}

include "footer.html";
 ?>
