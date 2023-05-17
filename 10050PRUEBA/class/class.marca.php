<?php
class marca{
	
	private $id;
	private $descripcion;
	private $pais;
	
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR MARCA<br><br>";
	}
	

	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->descripcion = NULL;
			$this->pais = NULL;
			
			$flag = "enabled";
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM marca WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){
                $mensaje = "tratar de actualizar la marca con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				
				echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			
		
             // ATRIBUTOS DE LA CLASE marca   
                $this->descripcion = $row['descripcion'];
                $this->pais = $row['pais'];
                
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
			<form name="Form_marca" method="POST" action="marca.php" enctype="multipart/form-data">
			<input type="hidden" name="id" value="' . $id  . '">
			<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table table-striped">
				<tr>
					<th colspan="2">DATOS DE LA MARCA</th>
				</tr>
				<tr>
					<td>Descripcion:</td>
					<td><input type="text" size="6" name="descripcion" value="' . $this->descripcion . '"></td>
				</tr>
				<tr>
					<td>Pais:</td>
					<td><input type="text" size="15" name="pais" value="' . $this->pais . '"></td>
				</tr>	
				
				<tr>
					<th colspan="2"><input class="btn btn-primary" type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table>
			</div>
			</div>
			';
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
				<th colspan="5" style="text-align:center">Lista de Marcas</th>
			</tr>
			<tr>
				<th colspan="5"><div class="d-grid gap-2 col-1 mx-auto"><a class="btn btn-primary" href="marca.php?d=' . $d_new_final . '">Nuevo</a></div></th>
			</tr>
			<tr>
				<th style="text-align:center">Descripcion</th>
				<th style="text-align:center">Pais</th>
				<th style="text-align:center" colspan="3">Acciones</th>
			</tr>
			</div>
			</div>
			';
		$sql = "SELECT id, descripcion, pais FROM marca WHERE 1;";
		
		$res = $this->con->query($sql);
		
		
		
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
        if($num != 0){
		
		    while($row = $res->fetch_assoc()){
				/*
				echo "<br>VARIALE ROW ...... <br>";
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
						<td>' . $row['pais'] . '</td>
						<td><a class="btn btn-danger" href="marca.php?d=' . $d_del_final . '">Borrar</a></td>
						<td><a class="btn btn-warning" href="marca.php?d=' . $d_act_final . '">Actualizar</a></td>
						<td><a class="btn btn-success" href="marca.php?d=' . $d_det_final . '">Detalle</a></td>
					</tr>';
			 
		    }
		}else{
			$mensaje = "Tabla Marca" . "<br>";
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

public function get_detail_marca($id){
		$sql = "SELECT id, descripcion, pais  
				FROM marca 
				WHERE id=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del marca con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
	
	    /*echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";
		*/
		$html = '
		<div class="container">
		 <div class="row justify-content-md-center">
		 <br>
		<table border="0" class="table table-striped">
			<tr>
				<th colspan="2">DATOS DE LA MARCA</th>
			</tr>
			<tr>
				<td>Descripcion: </td>
				<td>'. $row['descripcion'] .'</td>
			</tr>
			<tr>
				<td>Pais: </td>
				<td>'. $row['pais'] .'</td>
			</tr>
			<tr>
				<th colspan="2"><a class="btn btn-primary" href="marca.php">Regresar</a></th>
			</tr>																						
		</table>
		</div>
		</div>';
		
		return $html;
	}	
	
}

//****************Funcion Eliminar
	public function delete_marca($id){
		
		//$mensaje = "PROXIMAMENTE SE ELIMINARA el marca con id= ".$id . "<br>";
        //echo $this->_message_error($mensaje);
		$sql = "DELETE FROM marca WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	}
//*******************Funcion Guardar
	public function save_marca(){
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";
		
			$this->descripcion = $_POST['descripcion'];
			$this->pais = $_POST['pais'];
			
		
			$sql = "INSERT INTO marca VALUES (NULL,
											'$this->descripcion',
											'$this->pais');";

			//echo $sql;

			
			if($this->con->query($sql)){
				echo $this->_message_ok("guardo");
			}else{
				echo $this->_message_error("Guardar<br>");
			}

}

	
	//******************Funcion Actualizar
	public function update_marca(){
		/*
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";
			*/
			$id = $_POST['id'];
		
		// ATRIBUTOS DE LA CLASE marca   
				//$this->id = $id;
                $this->descripcion = $_POST['descripcion'];
                $this->pais = $_POST['pais'];
                
		$sql = "UPDATE marca SET descripcion ='$this->descripcion',
									pais ='$this->pais'
				WHERE id=$id;";
		//echo $sql;	
		$bandera = $this->con->query($sql);
		if($bandera==NULL){
			echo "<br>la VARIABLE bandera NULL<br>";
		}else{
			echo "<br>VARIABLE bandera <br>";
				echo $bandera;
				echo "<br><br>";
		}
		
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


	
//***************************************************************************************	
	
	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	
//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="marca.php" class="btn btn-primary">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
	   $html = '
		<table border="0" align="center">
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
				<th><a href="marca.php" class="btn btn-primary">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************

 
}
?>

