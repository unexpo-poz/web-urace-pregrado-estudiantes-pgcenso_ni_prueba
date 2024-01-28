<html>
<head>
<script LANGUAGE="Javascript" SRC="inscni.js"></script>
<link href="inc/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php

/*foreach ($_POST as $nombre_campo => $valor){
	echo $nombre_campo ."=>". $valor;
	echo "<br>";
}*/
echo entre;
	$pais	= $_POST['pais'];
	$estado = $_POST['estado'];
	$ciudad = $_POST['ciudad'];
	
if ($pais == '232') //SÓLO SI EL PAIS ES VENEZUELA
{
	(isset($_POST['pais'])) ? $pais	= $_POST['pais'] : $pais = "";
	
	(isset($_POST['estado'])) ? $estado	= $_POST['estado'] : $estado = "";
	
	(isset($_POST['ciudad'])) ? $ciudad = $_POST['ciudad'] : $ciudad = "";
	
	include_once ('inc/odbcss_c.php');
	include_once ('inc/config.php');
	
	//CONEXION A LA BD DONDE ESTAN LAS TABLAS DE PAISES, ESTADOS Y CIUDADES
	$conexion = new ODBC_Conn($dsnPregrado, $IdUsuario, $ClaveUsuario);
	
	$sqlCiudad = "SELECT CODIGO, CD_NOMBRE ";
	$sqlCiudad.= "FROM CIUDADES ";
	$sqlCiudad.= "WHERE COD_PAIS='".$pais."' AND COD_EDO='".$estado."' ";
	$sqlCiudad.=" ORDER BY CD_NOMBRE ASC";
	$conexion->ExecSQL($sqlCiudad) or die ("No se ha podido realizar la consulta");
	$filas3 = $conexion->filas;
	$conex_ciudad = $conexion->result;
?>
	
<!--LISTA DESPLEGABLE DE CIUDADES-->
	<select name="telfp_eS" id="telfp_e_S_1" class="datospf" onChange="validar(this);">
	<option value="">-SELECCIONE-</option>
	 <?php
		//echo $option;
		for ($c = 0; $c < $filas3; $c++){
			$CODIGO 	= $conex_ciudad[$c][0];
			$CD_NOMBRE 	= $conex_ciudad[$c][1];
			
			//echo $CODIGO;
			 if ($ciudad == $CODIGO) { //valor de la BD
				?>
				<option value="<?php echo $CODIGO; ?>" selected="selected" ><?php echo utf8_encode($CD_NOMBRE); ?></option>
				<?php
			
			  } else {
				?>
				<option value="<?php echo $CODIGO; ?>"><?php echo utf8_encode($CD_NOMBRE); ?></option>
				<?php
			  }
				
			?>
	<?php
		}
	?>
		   
		  
    </select>
<?php
	
}
	?>


</body>

</html>