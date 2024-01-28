<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="asincronos.js"> </script><!-- PARA GENERAR LOS ESTADOS,CIUDADES MUNICIPIOS Y PARROQUIAS QUE FALTAN-->
<script LANGUAGE="Javascript" SRC="inscni.js"></script>
</head>

<body>
<?php
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	/*
	foreach ($_POST as $nombre_campo => $valor){
		echo $nombre_campo ."=>". $valor;
		echo "<br>";
	}
	*/
	$estado  = $_POST['estado'];
	$municipio = $_POST['municipio'];
	$parroquia = $_POST['parroquia'];
	$municipioBD = $_POST['municipioBD'];
	
	
   if ($estado != "")//SI EL estado existe
	   {
		
		(isset($_POST['estado'])) ? $estado	= $_POST['estado'] : $estado = "";
	
	    (isset($_POST['municipio'])) ? $municipio	= $_POST['municipio'] : $municipio = "";
	
	    (isset($_POST['parroquia'])) ? $parroquia = $_POST['parroquia'] : $parroquia = "";
	
	 		
	 include_once ('inc/odbcss_c.php');
	 include_once ('inc/config.php');
	
	//CONEXION A LA BD DE PARROQUIA
	 $conexionP = new ODBC_Conn($dsnPregrado,$IdUsuario,$ClaveUsuario);
	 $sqlPquia = "SELECT CDO_PQUIA,PQUIA_NOMBRE,COD_MPIO ";
	 $sqlPquia.= "FROM PARROQUIA ";
	 $sqlPquia.= "WHERE COD_EDO='".$estado."' AND COD_MPIO='".$municipio."' ";
	 $sqlPquia.=" ORDER BY PQUIA_NOMBRE ASC";
	 //echo  $sqlPquia;
	
	 $conexionP->ExecSQL($sqlPquia) or die ("No se ha podido realizar la consulta");
	 $filas3 = $conexionP->filas;
	 $conex_pquia = $conexionP->result;
	
?>
		
<!--LISTA DESPLEGABLE DE PARROQUIA-->
    <input type="hidden" name="edo_nac_e" id="edo_nac_e" value="{$_d['edo_nac_e']}">
    <select name="edo_nac_eS" id="edo_nac_e_S_1" class="datospf">
        <option value="">-SELECCIONE-</option>
	    <?php
			for ($p = 0; $p < $filas3; $p++){
			  $CODIGO_PQUIA 	= $conex_pquia[$p][0];
			  $PQUIA_NOMBRE 	= $conex_pquia[$p][1];
			 
	          if ($parroquia == $CODIGO_PQUIA) { //valor de la BD
				($municipio != $municipioBD) ? $seleccionado = "" : $seleccionado = "selected";
				?>
				<option value="<?php echo $CODIGO_PQUIA; ?>" <?php echo $seleccionado;?>><?php echo utf8_encode($PQUIA_NOMBRE); ?></option>
				<?php
			
			  }
			 else {
				?>
				<option value="<?php echo $CODIGO_PQUIA; ?>"><?php echo utf8_encode($PQUIA_NOMBRE); ?></option>
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