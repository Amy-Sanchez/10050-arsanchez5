<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Matriculas Vehículos - PARTE II</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
	<?php
	    include_once("constantes.php");
		require_once("class/class.agencia.php");
		
		$cn = conectar();
		$v = new agencia($cn);
		//agencia::MetodoEstatico();
		
		
//2.1 URL para la petición GET
//$URL = "http://localhost:8088/agencia_CRUD/agencia_PARTE_II/index.php?d=act/0";	
//$URL = "http://localhost:8088/agencia_CRUD/agencia_PARTE_II/index.php?d=act/5";	

//$URL = "http://localhost/DESARROLLO_WEB/I_PARCIAL/Conexion_%20BD_PHP/agencia_PARTE_II/index.php?d=act/5";	

//$URL = "http://localhost:8088/agencia_CRUD/agencia_PARTE_II/index.php?d=det/0";	
//$URL = "http://localhost:8088/agencia_CRUD/agencia_PARTE_II/index.php?d=det/5";		
		
    // Codigo necesario para realizar pruebas.
		if(isset($_GET['d'])){
		  /*
			echo "<br>PETICION GET <br>";
			echo "<pre>";
				print_r($_GET);
			echo "</pre>";
		  */
			// 2.1 PETICION GET
			// $dato = $_GET['d'];
			
			// 2.2 DETALLE id
			$dato = base64_decode($_GET['d']);
			$tmp = explode("/", $dato);
			
			
			/*echo "<br>VARIABLE TEMP <br>";
			echo "<pre>";
				print_r($tmp);
			echo "</pre>";
			*/
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "det"){
				echo $v->get_detail_agencia($id);
			}elseif($op == "act"){
				echo $v->get_form($id);
			}elseif($op == "new"){
				echo $v->get_form();
			}elseif($op == "del"){
				echo $v->delete_agencia($id); // BORRAR TODOS LOS REGISTROS DE LA BASE DE DATOS
			}
			
        }else{
			   /*
				echo "<br>PETICION POST <br>";
				echo "<pre>";
					print_r($_POST);
				echo "</pre>";
		      */
			if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$v->save_agencia();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$v->update_agencia();
			}else{
				echo $v->get_list();
			}	
		}
		
		
		
//*******************************************************
		function conectar(){
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
			}else{
				echo "<div class='container'>
						<nav class='navbar navbar-expand-lg navbar-dark bg-primary'>
						<div class='container-fluid'>
						<a class='navbar-brand' href='index.html'>Home</a>
						<a class='navbar-brand' href='vehiculo.php'>Vehiculo</a>
						<a class='navbar-brand' href='agencia.php'>Agencia</a>
						<a class='navbar-brand' href='marca.php'>Marca</a>
						</div>
						</nav>
						</div>
				";
			}
			
			$c->set_charset("utf8");
			return $c;
		}
//**********************************************************
		
		
	?>	
	
<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>
</html>
