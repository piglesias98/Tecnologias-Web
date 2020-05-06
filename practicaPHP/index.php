<?php
session_start();

ini_set("display_errors", "1");
ini_set("error_reporting", E_ALL);
require "pag_comun.php";
include "header.html";


// Obtener el query string

if ((!isset($_GET["p"])) || ($_GET["p"]<0 || $_GET["p"]>3)){
  $_GET['p'] = 0;
}
$opc = $_GET['p'];

//Identificación de los usuarios

HTMLwidgets();

//Según el query string resaltamos el menú
HTMLnav($opc);

//Según el query string insertamos el código HTML correspondiente
if (isset($_SESSION['identificado'])){
  switch($opc) {
    case 0: include "inicio.html"; break;
    case 1: include "listado.php"; break;
    case 2: include "contacto.php"; break;
    case 3: include "crear_receta.php"; break;
  }
}else{
  switch($opc) {
    case 0: include "inicio.html"; break;
    case 1: include "listado.php"; break;
    case 2: include "contacto.php"; break;
  }
}

include "footer.html";
 ?>
