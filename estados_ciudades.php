<html>
<head>
<link href="inc/estilo.css" rel="stylesheet" type="text/css">
<script LANGUAGE="Javascript" SRC="inscni.js"></script>
</head>

<body>
<?php
	//echo "pasando por aqui";
	//print_r($_POST['pais']);
	$pais	= $_POST['pais'];
	$estado = $_POST['estado'];
	$codigo_ciudad = $_POST['ciudad'];
	
	//CUANDO REGRESE A CAMBIAR EL PAIS DE NACIMIENTO
	//PARA QUE NO DE ERROR CUANDO COMPARE CIUDADES EXTRANJERAS CON LOS CODIGOS D LA TABLA CIUDAES
	
if ($pais == '232'){//SI EL PAIS ES VENEZUELA

	(isset($_POST['pais'])) ? $pais	= $_POST['pais'] : $pais = "";
	
	(isset($_POST['estado'])) ? $estado	= $_POST['estado'] : $estado = "";
	
	(isset($_POST['ciudad'])) ? $codigo_ciudad = $_POST['ciudad'] : $codigo_ciudad = "";
	
	
	include_once ('inc/odbcss_c.php');
	include_once ('inc/config.php');
	
	//CONEXION A LA BD DONDE ESTAN LAS TABLAS DE PAISES, ESTADOS Y CIUDADES
	$conexion = new ODBC_Conn($dsnPregrado, $IdUsuario, $ClaveUsuario);
	
	if ($_POST['ciudad'] != '' and is_numeric($codigo_ciudad)){
		$codigo_ciudad = $_POST['ciudad'];		
		$sql = "SELECT cd_nombre FROM ciudades WHERE codigo='".$codigo_ciudad."' and cod_pais='".$pais."' and cod_edo='".$estado."'";
		$conexion->ExecSQL($sql) or die ("No se ha podido realizar la consulta");
		$nombre_ciudad = $conexion->result[0][0];
		$optionC = "<option value=\"".$codigo_ciudad."\" selected> ".utf8_encode($nombre_ciudad)." </option>";
		
		$var = " AND codigo != '".$codigo_ciudad."' ";
	}else{
		$optionC = " ";
		$var = " ";
	
	}
	$sqlCiudad = "SELECT CODIGO, CD_NOMBRE ";
	$sqlCiudad.= "FROM CIUDADES ";
	$sqlCiudad.= "WHERE COD_PAIS='".$pais."' AND COD_EDO='".$estado."' ";
	$sqlCiudad.= $var;
	$sqlCiudad.=" ORDER BY CD_NOMBRE ASC ";
	//echo  $sqlCiudad;
	$conexion->ExecSQL($sqlCiudad) or die ("No se ha podido realizar la consulta");
	$filas3 = $conexion->filas;
	$conex_ciudad = $conexion->result;
?>
		
		
<!--LISTA DESPLEGABLE DE CIUDADES-->
	
	<select name="l_nac_eS" id="lnace_S_1" class="datospf" onChange="validar(this);">
		<option value="">-SELECCIONE-</option>
	<?php
		echo $optionC;
		for ($c = 0; $c < $filas3; $c++){
			$CODIGO 	= $conex_ciudad[$c][0];
			$CD_NOMBRE 	= $conex_ciudad[$c][1];
	?>
		<option value="<?php echo $CODIGO;?>"><?php echo utf8_encode($CD_NOMBRE);?></option>
	<?php
		}
	?>
	</select>
	<?php
	
}//fin if ($pais == '232')
	?>

</body>

</html>