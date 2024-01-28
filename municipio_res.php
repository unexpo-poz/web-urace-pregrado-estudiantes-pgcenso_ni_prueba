<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<script src="asincronos.js"> </script>
</head>

<body>
<?php
	/*
	foreach ($_POST as $nombre_campo => $valor){
		echo $nombre_campo ."=>". $valor;
		echo "<br>";
	}
	*/
	$estadoBD 	 = $_POST['estadoBD'];
	$pais		 = $_POST['pais'];
	$estado 	 = $_POST['estado'];
	$codigo_mpio = $_POST['municipio'];
	
	//CUANDO REGRESE A CAMBIAR EL PAIS DE NACIMIENTO
	//PARA QUE NO DE ERROR CUANDO COMPARE CIUDADES EXTRANJERAS CON LOS CODIGOS D LA TABLA CIUDAES
if ($pais == '232')//SI EL PAIS ES VENEZUELA
{
	
	(isset($_POST['pais'])) ? $pais	= $_POST['pais'] : $pais = "";
	
	(isset($_POST['estado'])) ? $estado	= $_POST['estado'] : $estado = "";
	
	(isset($_POST['municipio'])) ? $codigo_mpio = $_POST['municipio'] : $codigo_mpio = "";
	
	
	include_once ('inc/odbcss_c.php');
	include_once ('inc/config.php');
	
	//CONEXION A LA BD DONDE ESTAN LAS TABLAS DE PAISES, ESTADOS Y CIUDADES
	$conexionM = new ODBC_Conn($dsnPregrado, $IdUsuario, $ClaveUsuario);
	
if ($estadoBD == $estado && $estadoBD != '')//AL CAMBIAR EL ESTADO DE DIR.MUESTRE -SELECCIONE- EN MUNICIPIO
{
	if ($_POST['municipio'] != '' and is_numeric($codigo_mpio)){
		//echo('hola');
		$codigo_mpio = $_POST['municipio'];		
		$sqlM = "SELECT mpio_nombre FROM municipios WHERE codigo='".$codigo_mpio."' and cod_pais='".$pais."' and cod_edo='".$estado."'";
		$conexionM->ExecSQL($sqlM) or die ("No se ha podido realizar la consulta");
		$nombre_mpio = $conexionM->result[0][0];
		$optionM = "<option value=\"".$codigo_mpio."\"selected> ".utf8_encode($nombre_mpio)." </option>";
		
		$var = " AND codigo != '".$codigo_mpio."' ";
	}else{
		$optionM = " ";
		$var = " ";
	}
}
	$sqlMpio = "SELECT CODIGO, MPIO_NOMBRE ";
	$sqlMpio.= "FROM MUNICIPIOS ";
	$sqlMpio.= "WHERE COD_PAIS='".$pais."' AND COD_EDO='".$estado."' ";
	$sqlMpio.= $var;
	$sqlMpio.=" ORDER BY MPIO_NOMBRE ASC";
	//echo  $sqlCiudad;
	$conexionM->ExecSQL($sqlMpio) or die ("No se ha podido realizar la consulta");
	$filas3 = $conexionM->filas;
	$conex_mpio = $conexionM->result;
?>
						
<!--LISTA DESPLEGABLE DE MUNICIPIOS-->

<input type="hidden" name="edo_nac_e" value="<?php echo $edo_nac_e; ?>">
<input type="hidden" name="estado_S_1" value="<?php echo $estado; ?>">
<input type="hidden" name="tot_hab_n" id="tot_hab_n" value="<?php echo $tot_hab_n; ?>">

<select name="tot_hab_nS" id="tot_hab_n_S_1" class="datospf" onChange="parroquia_residencia(estado_S_1.value, this.value, edo_nac_e.value, tot_hab_n.value);">
	<option value="">-SELECCIONE-</option>
	<?php
		echo $optionM;
		for ($c = 0; $c < $filas3; $c++){
			$CODIGO 	= $conex_mpio[$c][0];
			$MPIO_NOMBRE 	= $conex_mpio[$c][1];
	?>
		<option value="<?php echo $CODIGO;?>"><?php echo utf8_encode($MPIO_NOMBRE);?></option>
	<?php
		}
	?>
</select>
	<?php
	
}//fin if ($pais == '232')
	?>

</body>

</html>