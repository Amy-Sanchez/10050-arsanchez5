<?php
class agencia{
	
	
	private $id;
	private $descripcion;
	private $direccion;
	private $horarioAp;
	private $horarioCr;
	private $foto;
	private $telf;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR agencia<br><br>";
	}
	

	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->descripcion = NULL;
			$this->direccion = NULL;
			$this->horarioAp = NULL;
			$this->horarioCr = NULL;
			$this->telf = NULL;
			$this->foto = NULL;
			
			$flag = "enabled";
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM agencia WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){
                $mensaje = "tratar de actualizar el agencia con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				
				echo "<br>REGISTRO A MODIFICAR: <br>";
					/*echo "<pre>";
						print_r($row);
					echo "</pre>";*/
			
		
             // ATRIBUTOS DE LA CLASE agencia   
                $this->descripcion = $row['descripcion'];
                $this->direccion = $row['direccion'];
                $this->horarioAp = $row['horarioAp'];
                $this->horarioCr = $row['horarioCr'];
                $this->telf = $row['telf'];
                $this->foto = $row['foto'];
				
                //$flag = "disabled";
				$flag = "enabled";
                $op = "update"; 
            }
	}
        
	if($bandera){
    
		
		$html = '
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>
		<form name="Form_agencia" method="POST" action="agencia.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table table-striped">
				<tr>
					<th colspan="2">DATOS AGENCIA</th>
				</tr>
				<tr>
					<td>Descripcion:</td>
					<td><input type="text" size="6" name="descripcion" value="' . $this->descripcion . '"></td>
				</tr>
				<tr>
					<td>direccion:</td>
					<td><input type="text" size="6" name="direccion" value="' . $this->direccion . '"></td>
				</tr>
				<tr>
					<td>Horario Apertura:</td>
					<td><input type="time" size="15" name="horarioAp" value="' . $this->horarioAp . '"></td>
				</tr>	
				<tr>
					<td>Horario Cierre:</td>
					<td><input type="time" size="15" name="horarioCr" value="' . $this->horarioCr . '"></td>
				</tr>
				<tr>
					<td>Telefono:</td>
					<td><input type="text" size="6" name="telf" value="' . $this->telf . '"></td>
				</tr>
				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" ' . $flag . '></td>
				</tr>
				
				<tr>
					<th colspan="2"><input class="btn btn-primary" type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table></div></div>';
		return $html;
		}
	}
	
	
	
	public function get_list(){
		$d_new = "new/0";                           //Línea agregada
        $d_new_final = base64_encode($d_new);       //Línea agregada
				
		$html = ' 
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>
			<table border="0" align="center" class="table table-striped">
			<tr>
				<th colspan="8" style="text-align:center">Lista de Agencias</th>
			</tr>
			<tr>
				<th colspan="8"><a class="btn btn-primary" href="agencia.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
				<th>Descripcion</th>
				<th>Direccion</th>
				<th>Horario Apertura</th>
				<th>Horario Cierre</th>
				<th>Telefono</th>
				<th colspan="3">Acciones</th>
			</tr></div></div>';
		$sql = "SELECT `id`,`descripcion`, `direccion`, `horarioAp`, `horarioCr`, `telf` FROM `agencia` ";	
		$res = $this->con->query($sql);
		
		
		
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
        if($num != 0){
		
		    while($row = $res->fetch_assoc()){
			
				/*echo "<br>VARIALE ROW ...... <br>";
				echo "<pre>";
						print_r($row);
				echo "</pre>";
			*/
		    		
				// URL PARA BORRAR
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				
				// URL PARA ACTUALIZAR
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				
				// URL PARA EL DETALLE
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);	
				
				$html .= '
					<tr>
						<td>' . $row['descripcion'] . '</td>
						<td>' . $row['direccion'] . '</td>
						<td>' . $row['horarioAp'] . '</td>
						<td>' . $row['horarioCr'] . '</td>
						<td>' . $row['telf'] . '</td>
						<td><a class="btn btn-danger" href="agencia.php?d=' . $d_del_final . '">Borrar</a></td>
						<td><a class="btn btn-warning" href="agencia.php?d=' . $d_act_final . '">Actualizar</a></td>
						<td><a class="btn btn-success" href="agencia.php?d=' . $d_det_final . '">Detalle</a></td>
					</tr>';
			 
		    }
		}else{
			$mensaje = "Tabla Agencia" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
		
	}
	
	
//********************************************************************************************************
	/*
	 $tabla es la tabla de la base de datos
	 $valor es el nombre del campo que utilizaremos como valor del option
	 $etiqueta es nombre del campo que utilizaremos como etiqueta del option
	 $nombre es el nombre del campo tipo combo box (select)
	 * $defecto es el valor para que cargue el combo por defecto
	 */ 
	 
	 // _get_combo_db("marca","id","descripcion","marca",$this->marca)
	 // _get_combo_db("color","id","descripcion","color", $this->color)
	 
	 /*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		//$num = $res->num_rows;
		
			
		while($row = $res->fetch_assoc()){
		
		/*
			echo "<br>VARIABLE ROW <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
		*/	
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	//_get_combo_anio("anio",1950,$this->anio)
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($defecto == $i)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	
	//_get_radio($combustibles, "combustible",$this->combustible) 
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto=NULL){
		$html = '
		<table border=0 align="left">';
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>':'<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
			
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}
	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_agencia($id){
		$sql = "SELECT `descripcion`, `direccion`, `horarioAp`, `horarioCr`, `telf`, `foto` FROM `agencia` WHERE `id`=$id";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del agencia con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
		/*
	
	    echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";
		*/
		$html = '
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th colspan="2">DATOS DE LA AGENCIA</th>
			</tr>
			<tr>
				<td>descripcion: </td>
				<td>'. $row['descripcion'] .'</td>
			</tr>
			<tr>
				<td>Direccion: </td>
				<td>'. $row['direccion'] .'</td>
			</tr>
			<tr>
				<td>Horario Apertura: </td>
				<td>'. $row['horarioAp'] .'</td>
			</tr>
			<tr>
				<td>Horario Cierre: </td>
				<td>'. $row['horarioCr'] .'</td>
			</tr>
			<tr>
				<td>telefono: </td>
				<td>'. $row['telf'] .'</td>
			</tr>
			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
			</tr>	
			<tr>
				<th colspan="2"><a class="btn btn-primary" href="agencia.php">Regresar</a></th>
			</tr>																						
		</table></div></div>';
		
		return $html;
	}	
	
}

//****************Funcion Eliminar
	public function delete_agencia($id){
		
		//$mensaje = "PROXIMAMENTE SE ELIMINARA el agencia con id= ".$id . "<br>";
        //echo $this->_message_error($mensaje);
		$sql = "DELETE FROM agencia WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	}
//*******************Funcion Guardar
	public function save_agencia(){
			$this->descripcion = $_POST['descripcion'];
			$this->direccion = $_POST['direccion'];
			$this->horarioAp = $_POST['horarioAp'];
			$this->horarioCr = $_POST['horarioCr'];
			$this->telf = $_POST['telf'];
			
		
			$this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
			$path = "images/" . $this->foto;
   
			if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
				$mensaje="Cargar la imagen ";
				echo $this->_message_error($mensaje);
			}

			$sql = "INSERT INTO agencia VALUES (NULL,
												'$this->descripcion',
												'$this->direccion',
												'$this->horarioAp',
												'$this->horarioCr',
												'$this->telf',
												'$this->foto');";


			//echo $sql;

			
			if($this->con->query($sql)){
				echo $this->_message_ok("guardo");
			}else{
				echo $this->_message_error("Guardar<br>");
			}

}

	
	//******************Funcion Actualizar
	public function update_agencia(){
		
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";
			
			$id = $_POST['id'];
		
		// ATRIBUTOS DE LA CLASE agencia   
                $this->descripcion = $_POST['descripcion'];
                $this->direccion = $_POST['direccion'];
                $this->horarioAp = $_POST['horarioAp'];
                $this->horarioCr = $_POST['horarioCr'];
                $this->telf = $_POST['telf'];
                $this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
			$path = "images/" . $this->foto;
   
			if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
				$mensaje="Cargar la imagen";
				echo $this->_message_error($mensaje);
			}
   
				
		$sql = "UPDATE `agencia` SET descripcion ='$this->descripcion',
									direccion ='$this->direccion',
									horarioAp ='$this->horarioAp', 
									horarioCr ='$this->horarioCr',
									telf ='$this->telf',
									foto ='$this->foto'
				WHERE id=$id;";
		/*echo $sql;	
		$bandera = $this->con->query($sql);
		if($bandera==NULL){
			echo "<br>la VARIABLE bandera NULL<br>";
		}else{
			echo "<br>VARIABLE bandera <br>";
				echo $bandera;
				echo "<br><br>";
		}*/
		
		if($this->con->query($sql)){
			echo $this->_message_ok("actualizo");
		}else{
			echo $this->_message_error("actualizar<br>");
		}
		
	}

//*******************************FUNCION GET NAME FILE**********************************************************


private function _get_name_file($nombre_original, $tamanio){
			$tmp = explode(".",$nombre_original);
			$numElm = count($tmp);
			$ext = $tmp[$numElm-1];
			$cadena = "";
					for($i=1;$i<=$tamanio;$i++){
						$c = rand(65, 122);
						if(($c >= 91) && ($c <=96)){
							$c = NULL;
								$i--;
						}else{
							$cadena .= chr($c);
						}
					}
	return $cadena.".".$ext;
}



//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a class="btn btn-primary" href="agencia.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
	   $html = '
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th> NO existen registros en la ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
	
		</table>';
		return $html;
	
	
	}
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a class="btn btn-primary" href="agencia.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************

 
}
?>

