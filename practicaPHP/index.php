<?php
require "pag_comun.php";
include "header.html";

// Obtener el query string

if ((!isset($_GET["p"])) || ($_GET["p"]<0 || $_GET["p"]>3)){
  $_GET['p'] = 0;
}

$opc = $_GET['p'];

$identificado = False;
//Según el query string resaltamos el menú
HTMLnav($opc);
//Según el query string insertamos el código HTML correspondiente
switch($opc) {
  case 0: include "inicio.html"; break;
  case 1: include "listado.html"; break;
  case 2: include "contacto.html"; break;
  case 3: include "pag_nueva.html"; break;
}
include "footer.html";
 ?>
