<?php
class matricula{
	
	
	private $id;
	private $placa;
	private $marca;
	private $motor;
	private $chasis;
	private $combustible;
	private $anio;
	private $color;
	private $foto;
	private $avaluo;
	private $idMatri;
	private $agencia;
	private $anioM;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR VEHICULO<br><br>";
	}
	

	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->placa = NULL;
			$this->marca = NULL;
			$this->motor = NULL;
			$this->chasis = NULL;
			$this->combustible = NULL;
			$this->anio = NULL;
			$this->color = NULL;
			$this->foto = NULL;
			$this->avaluo =NULL;
			$this->agencia =NULL;
			$this->anioM = NULL;
			
			
			$flag = "enabled";
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM matricula WHERE id=$idMatri";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){
                $mensaje = "tratar de matricular el vehiculo con id= ".$idMatri . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				
				echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			
		
             // ATRIBUTOS DE LA CLASE MATRICULA   
                
                $this->anioM = $row['anioM'];
                $this->agencia = $row['agencia'];
				$flag = "enabled";
                $op = "matricula"; 
            }
	}
        
	if($bandera){
    
		$combustibles = ["Gasolina",
						 "Diesel",
						 "Eléctrico"
						 ];
		$html = '
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>
		<form name="Form_vehiculo" method="POST" action="vehiculo.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table table-striped">
				<tr>
					<th colspan="2">DATOS VEHÍCULO</th>
				</tr>
				<tr>
					<td>Placa:</td>
					<td><input type="text" size="6" name="placa" value="' . $this->placa . '"></td>
				</tr>
				<tr>
					<td>Marca:</td>
					<td>' . $this->_get_combo_db("marca","id","descripcion","marca",$this->marca) . '</td>
				</tr>
				<tr>
					<td>Motor:</td>
					<td><input type="text" size="15" name="motor" value="' . $this->motor . '"></td>
				</tr>	
				<tr>
					<td>Chasis:</td>
					<td><input type="text" size="15" name="chasis" value="' . $this->chasis . '"></td>
				</tr>
				
				<tr>
					<td>Combustible:</td>
					<td>' . $this->_get_radio($combustibles, "combustible",$this->combustible) . '</td>
				</tr>
				<tr>
					<td>Año:</td>
					<td>' . $this->_get_combo_anio("anio",1950,$this->anio) . '</td>
				</tr>
				<tr>
					<td>Color:</td>
					<td>' . $this->_get_combo_db("color","id","descripcion","color", $this->color) . '</td>
				</tr>
				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" ' . $flag . '></td>
				</tr>
				<tr>
					<td>Avalúo:</td>
					<td><input type="text" size="8" name="avaluo" value="' . $this->avaluo . '" ' . $flag . '></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
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
				<th colspan="8">Lista de Vehículos</th>
			</tr>
				<tr>
				<th>Placa</th>
				<th>Marca</th>
				<th>Color</th>
				<th>Año</th>
				<th>Avalúo</th>
				<th colspan="3">Acciones</th>
			</tr></div></div>';
		$sql = "SELECT v.id, v.placa, m.descripcion as marca, c.descripcion as color, v.anio, v.avaluo  
		        FROM vehiculo v, color c, marca m 
				WHERE v.marca=m.id AND v.color=c.id;";	
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
				
				// URL PARA EL MATRICULAR
				$d_matr = "matr/" . $row['id'];
				$d_matr_final = base64_encode($d_matr);	
				
					
				
				$html .= '
					<tr>
						<td>' . $row['placa'] . '</td>
						<td>' . $row['marca'] . '</td>
						<td>' . $row['color'] . '</td>
						<td>' . $row['anio'] . '</td>
						<td>' . $row['avaluo'] . '</td>
						<td><a class="btn btn-primary" href="matriculacion.php?d=' . $d_matr_final . '">Matricular</a></td>
						
					</tr>';
			 
		    }
		}else{
			$mensaje = "Tabla Vehiculo" . "<br>";
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
	 // _get_combo_db("agencia","id","descripcion","agencia", $this->agencia)
	 
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

public function get_detail_vehiculo($id){
		$sql = "SELECT v.placa, m.descripcion as marca, v.motor, v.chasis, v.combustible, v.anio, c.descripcion as color, v.foto, v.avaluo  
				FROM vehiculo v, color c, marca m 
				WHERE v.id=$id AND v.marca=m.id AND v.color=c.id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del vehiculo con id= ".$id . "<br>";
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
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th colspan="2">DATOS DEL VEHICULO</th>
			</tr>
			
			<tr>
				<td>Placa: </td>
				<td>'. $row['placa'] .'</td>
			</tr>
			<tr>
				<td>Marca: </td>
				<td>'. $row['marca'] .'</td>
			</tr>
			<tr>
				<td>Motor: </td>
				<td>'. $row['motor'] .'</td>
			</tr>
			<tr>
				<td>Chasis: </td>
				<td>'. $row['chasis'] .'</td>
			</tr>
			<tr>
				<td>Combustible: </td>
				<td>'. $row['combustible'] .'</td>
			</tr>
			<tr>
				<td>Anio: </td>
				<td>'. $row['anio'] .'</td>
			</tr>
			<tr>
				<td>Color: </td>
				<td>'. $row['color'] .'</td>
			</tr>
			<tr>
				<td>Avalúo: </td>
				<th>$'. $row['avaluo'] .' USD</th>
			</tr>
			<tr>
				<td>Valor Matrícula: </td>
				<th>$'. $this->_calculo_matricula($row['avaluo']) .' USD</th>
			</tr>			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
			</tr>	
			</div>
			<div>
			<form name="Form_matricula" method="POST" action="matriculacion.php" enctype="multipart/form-data">
			<table border="0" align="center" class="table table-striped">
			<tr> 
				<th colspan="2">MATRICULAR VEHÍCULO</th>
			</tr>
			<tr>
				<td>Agencia:</td>
				<td>' . $this->_get_combo_db("agencia","id","descripcion","agencia", $this->agencia) . '</td>
			</tr>

			<tr>
				<td>Anio:</td>
				<td>' . $this->_get_combo_anio("anio",2000,$this->anioM)  . '</td>
			</tr>
	
			<tr>
				<th colspan="1"><input class="btn btn-primary" type="submit" name="Registrar" value="REGISTRAR"></th>
				<th colspan="1"><a class="btn btn-danger" href="matriculacion.php">Cancelar</a></th>
			</tr>																						
		</table>
		</form>
		</div>
		</div>
		';
		
		return $html;
	}	
	
}

//****************Funcion Eliminar
	public function delete_vehiculo($id){
		
		//$mensaje = "PROXIMAMENTE SE ELIMINARA el vehiculo con id= ".$id . "<br>";
        //echo $this->_message_error($mensaje);
		$sql = "DELETE FROM vehiculo WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	}
//*******************Funcion Guardar
	public function save_vehiculo(){
			$this->placa = $_POST['placa'];
			$this->marca = $_POST['marca'];
			$this->motor = $_POST['motor'];
			$this->chasis = $_POST['chasis'];
			$this->combustible = $_POST['combustible'];
			$this->anio = $_POST['anio'];
			$this->color = $_POST['color'];
			
		
			$this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
			$path = "images/" . $this->foto;
   
			if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
				$mensaje="Cargar la imagen ";
				echo $this->_message_error($mensaje);
			}
   
			$this->avaluo = $_POST['avaluo'];


			$sql = "INSERT INTO vehiculo VALUES (NULL,
												'$this->placa',
												$this->marca,
												'$this->motor',
												'$this->chasis',
												'$this->combustible',
												'$this->anio',
												$this->color,
												'$this->foto',
												'$this->avaluo');";


			//echo $sql;

			
			if($this->con->query($sql)){
				echo $this->_message_ok("guardo");
			}else{
				echo $this->_message_error("Guardar<br>");
			}

}

	
	//******************Funcion Actualizar
	public function update_vehiculo(){
		/*
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";
		*/	
			$id = $_POST['id'];
		
		// ATRIBUTOS DE LA CLASE VEHICULO   
				//$this->id = $id;
                $this->placa = $_POST['placa'];
                $this->marca = $_POST['marca'];
                $this->motor = $_POST['motor'];
                $this->chasis = $_POST['chasis'];
                $this->combustible = $_POST['combustible'];
                $this->anio = $_POST['anio'];
                $this->color = $_POST['color'];
                $this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
			$path = "images/" . $this->foto;
   
			if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
				$mensaje="Cargar la imagen";
				echo $this->_message_error($mensaje);
			}
   
                $this->avaluo =  $_POST['avaluo'];
				
		$sql = "UPDATE vehiculo SET placa ='$this->placa',
									marca =$this->marca,
									motor ='$this->motor', 
									chasis ='$this->chasis',
									combustible ='$this->combustible',
									anio ='$this->anio', 
									color =$this->color,
									foto ='$this->foto',
									avaluo =$this->avaluo
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
	
//*************************FUNCION MATRICULAR***********
public function matricula_vehiculo(){
		/*
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";
		*/	
			$id = $_POST['id'];
		
		// ATRIBUTOS DE LA CLASE VEHICULO   
				//$this->id = $id;
                $this->agencia = $_POST['agencia'];
                $this->anioM = $_POST['andioM'];
                //$this->placa = $_POST['placa'];
		$sql = "INSERT INTO matricula(id, agencia, anio) 
				VALUES ('$this->agencia','$this->anioM')";
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
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="matriculacion.php">Regresar</a></th>
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
				<th><a href="matriculacion.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************

 
}
?>

