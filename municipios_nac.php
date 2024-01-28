<html>
<head>
<link href="inc/estilo.css" rel="stylesheet" type="text/css">

<script LANGUAGE="Javascript" SRC="inscni.js"></script>
<script LANGUAGE="Javascript" SRC="asincrono.js"></script>
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
	//PARA QUE NO DE ERROR CUANDO COMPARE CIUDADES EXTRANJERAS CON LOS CODIGOS D LA TABLA CIUDADES
if ($pais == '232'){//SI EL PAIS ES VENEZUELA
	
	(isset($_POST['pais'])) ? $pais	= $_POST['pais'] : $pais = "";
	
	(isset($_POST['estado'])) ? $estado	= $_POST['estado'] : $estado = "";
	
	(isset($_POST['municipio'])) ? $codigo_mpio = $_POST['municipio'] : $codigo_mpio = "";
	
	
	include_once ('inc/odbcss_c.php');
	include_once ('inc/config.php');
	
	//CONEXION A LA BD DONDE ESTAN LAS TABLAS DE PAISES, ESTADOS Y CIUDADES
	$conexionM = new ODBC_Conn($dsnPregrado, $IdUsuario, $ClaveUsuario);
	
if ($estadoBD == $estado && $estadoBD != '')//AL CAMBIAR EL ESTADO DE NACIMIENTO Q MUESTRE -SELECCIONE- EN MUNICIPIO
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
	//echo  $sqlMpio;
	$conexionM->ExecSQL($sqlMpio) or die ("No se ha podido realizar la consulta");
	$filas3 = $conexionM->filas;
	$conex_mpio = $conexionM->result;
?>
						
<!--LISTA DESPLEGABLE DE MUNICIPIOS-->
<input type="hidden" name="estadoBD" id="estadoBD" value="<?php echo $estadoBD; ?>">
<input type="hidden" name="ent_fed_S_1" value="<?php echo $estado; ?>">
<input type="hidden" name="tot_raz_v" value="<?php echo $tot_raz_v; ?>"><!--PARROQUIA DE NACIMIENTO-->
<input type="hidden" name="depend_p_e" value="<?php echo $depend_p_e; ?>">

<select name="depend_p_eS" id="depend_p_e_S_1" class="datospf" onChange="parroquia_naci(ent_fed_S_1.value, this.value, tot_raz_v.value, depend_p_e.value, estadoBD.value);">
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