<?php
$opcion = $_GET['opc']; 
// En esta variable voy a obtener de la variable opc el valor de la opcion que se selecciona en el menu

switch ($opcion) {
    case 1:
        include('../public/Login/Quienessomos.html');
        break;
 
}