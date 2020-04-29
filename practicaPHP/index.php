<?php
require "pag_comun.php";

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"]>=0 || $_GET["p"]<=3)){
  &opc = $_GET['p'];
}

HTMLinicio("Mi sitio web");

HTMLnav_alternativo($opc);
switch($opc) {
  case 0: HTMLpag_inicio(); break;
  case 1: HTMLpag_listado(); break;
  case 2: HTMLpag_contacto(); break;
}

 ?>
