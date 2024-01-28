<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php

include_once('inc/odbcss_c.php');
include_once ('inc/config.php');
include_once ('inc/activaerror.php');

$_d = $_REQUEST;

$conex = new ODBC_Conn($DNS,$USER,$PASS,$ODBCC_conBitacora,$laBitacora);
$preinscrito = false;

function envioValido() {
	global $raizDelSitio;

	$formOK = false;
	if (isset($_SERVER['HTTP_REFERER'])) {
		$formOK = ($_SERVER['HTTP_REFERER'] == $raizDelSitio .'planilla_r.php');
	}
    $formOK = $formOK && isset($_REQUEST['ci_e']) && isset($_REQUEST['conducta']);
	return $formOK;
}

function generarSQL($tabla, $nCampo, $aValores) {

	// OJO OJO OJO OJO:
	// traducimos las comillas simples (') a dos comillas ('') 
	// para poder insertarla en los campos apellidos y nombres
	// con la funcion addslashes(), pero para ello
	// la opcion 'magic_quotes_sybase' debe ser '1' de lo
	// contrario, las traduce a (\')
	ini_set('magic_quotes_sybase','1'); //para Centura SQLBase Server


    $campos = "";
    foreach ($nCampo as $campo) {
		$campos .= "".trim($campo).",";
    }
    
    $campos = trim(substr($campos,0,-1));                   //Metto tutti i campi in un'unica variabile
    $valores = "";
	foreach ($nCampo as $campo) {
		$val=trim($aValores[$campo]);
		if (@eregi("NULL",$val) == 0) $valores .= "'".addslashes($val)."',";
       else
          $valores .= "NULL,";
      }
      $valores = trim(substr($valores,0,-1));    //Inserisco tutti i valori in un'unica variabili distinguentoli dai
                                                //dai valori nulli e non nulli
      $query = "INSERT INTO ".$tabla."(".$campos.") VALUES (".trim($valores).")";    //creo la query
	  return $query;
}

function crearEjecutarSQL($exped) {
	global $_d, $conex, $preinscrito;
	
	//OJO: Modificar el valor '09' para cada año con el valor correspondiente
	// optimizar año de ingreso con funcion date
	//$exped = date("y")."-".$_d['ci_e'];
	$_d['exp_e'] = $exped;
	/*$ind=explode(',',$_d['ind_cnu']);
	$ind_cnu = "$ind[0]"."."."$ind[1]";
	$_d['ind_cnu'] = substr($ind_cnu, 0, strlen($ind_cnu) - 1);*/

	$ind1= substr($_d['ind_cnu'], 0, strlen($_d['ind_cnu']) - 3);
	$ind2= substr($_d['ind_cnu'], 3, strlen($_d['ind_cnu']));
	$_d['ind_cnu'] = $ind1.".".$ind2;
	

	$varCDACE002 = array('nac_e','ci_e','res_extraj','doc_ident','pasaporte_nro',
				'apellidos','apellidos2','nombres','nombres2','f_nac_e','exp_e',
				'p_nac_e','ent_fed','l_nac_e','depend_p_e','tot_raz_v','edo_c_e','sexo','correo1','correo2','avenida',
				'urbanizacion','manzana','nrocasa','estado','ciudad','tot_hab_n','edo_nac_e','telefono1',
				'telefono2','telefono3','dirp_e','telfp_e','proc_e','tot_prom_nts','c_uni_ca','conducta','becario','c_ingreso', 
				'opcion_cnu','ind_cnu','organismo','etnia_indigena','sit_e','estrato_social');
				# faltaba incluir el campo 'organismo' entre los campos a cargar en CDACE002.
				# Lo puse al final.
	
	$delCDACE002 = "DELETE FROM CDACE002 WHERE EXP_E='$exped'";

	$varDobeAcad = array('exp_e','plantel','tipo_plantel','costo_mensual','ano_egre_cole',
		'sistema_estudio','turno_estudio','titulo_b','promedio','codigo_c','codigo_e','codigo_p','codigo_m','codigo_pquia','promedio_mate',
		'promedio_fisi','promedio_quim','promedio_cast');
	$delDobeAcad = "DELETE FROM CDOBE_ACADEMICO WHERE EXP_E='$exped'";
		
	$varDobeSocE = array('exp_e','trabaja','turno_trabajo','instr_padre',
		'ocup_padre','instr_madre','ocup_madre','tipo_vivienda',
		'monto_alq','ingreso_f');
	$delDobeSocE = "DELETE FROM CDOBE_SOCIOECONOMI WHERE EXP_E='$exped'";
	
	if($preinscrito) {
		$conex->ExecSQL($delCDACE002,__LINE__, true);
	}
	$conex->ExecSQL(generarSQL('CDACE002', $varCDACE002, $_d),__LINE__, true);
	if ($conex->fmodif == 0) return false;
	
	if($preinscrito) {
		$conex->ExecSQL($delDobeAcad,__LINE__, true);
	}
	$conex->ExecSQL(generarSQL('CDOBE_ACADEMICO', $varDobeAcad, $_d),__LINE__, true);
	if ($conex->fmodif == 0) return false;
	
	if($preinscrito) {
		$conex->ExecSQL($delDobeSocE,__LINE__, true);
	}
	$conex->ExecSQL(generarSQL('CDOBE_SOCIOECONOMI', $varDobeSocE, $_d),__LINE__, true);
	if ($conex->fmodif == 0) return false;
	return true;
}

function obtenerExpediente($cedula) {
	global $conex, $sedeActiva, $preinscrito;

	$dSQL = "SELECT exp_e,fecha_solicitud from CDACE002 where ci_e='$cedula'";
	$conex->ExecSQL($dSQL);
	if ($conex->filas == 1) {
		$preinscrito = true;
		$fecha_sol = $conex->result[0][1];
//		return $conex->result[0][0];
		return array($conex->result[0][0],$fecha_sol);
	}else {//obtener un nuevo expediente:
		$cEXP = $exped = date("y")."-".$cedula;

		$preinscrito = false;
		$fecha_sol = date('Y-m-d'); // HOY
			
		//return $cEXP;
		return array($cEXP,$fecha_sol);
	}
}

function guardarDatos() {
	global $conex,$fecha_i;

	$conex->iniciarTransaccion("\nInicio Transaccion");
	$retorno = obtenerExpediente($_REQUEST['ci_e']);

	$exped = $retorno[0];
	$fecha_sol = $retorno[1];

	if($exped !='0') {
		if(crearEjecutarSQL($exped)) {

/* Rutina para determinar si actualiza la fecha de solicitud */
			$fecha_inicio = strtotime($fecha_i);

			$fecha_s = strtotime(implode('-',array_reverse(explode('-',$fecha_sol))));
			

			if ($fecha_s < $fecha_inicio) { // Si la solicitud es de un proceso de censo anterior
				$fecha_sol = date("Y-m-d");
			}
/* Fin rutina */

			$uSQL = " UPDATE CDACE002 SET FECHA_SOLICITUD='".$fecha_sol."' WHERE exp_e='".$exped."' ";
			$conex->ExecSQL($uSQL,__LINE__,true);
						
			if ($conex->finalizarTransaccion("Fin Transaccion - fecha_s: ".$fecha_s." - fecha_inicio: ".$fecha_inicio)) {
				return true;
			}
			else {
				$conex->deshacerTransaccion("Rollback Transaccion");
				return false;
			}
		}
		else {
			$conex->deshacerTransaccion("Rollback Transaccion");
			return false;
		}
	}
	else if($exped =='0') {
		$conex->deshacerTransaccion("Rollback Transaccion");
        return false;
	}
}

function reportar($exped) {
	global $noCache, $noJavaScript, $lapsoProceso, $_d;
?>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<?php
			print $noCache; 
			print $noJavaScript;

			

		?>
		<title>Planilla de Registro de Datos - Lapso <?php print $lapsoProceso; ?></title>
		<script languaje="Javascript">
		<!--
        function imprimir(fi) {
            with (fi) {
                bimp.style.display="none";
                bexit.style.display="none";
                window.print();
                //alert(msgI);
                bimp.style.display="block";
                bexit.style.display="block";
            }
        }
		//-->

		function alcerrar(){
			alert('Recuerde entregar en la Direcci&oacute;n Acad&eacute;mica una copia del Certificado OPSU, desde el 21/01/2016 hasta el 19/02/2016 en horario de 8:00 am a 11:30 am y de 1:00 pm a 3:30 pm.');
		} 
        </script>
		<link href="inc/estilo.css" rel="stylesheet" type="text/css">
		</head>
        <body  <?php global $botonDerecho; echo $botonDerecho; ?> onload="javascript:self.focus();" onbeforeunload="alert('Recuerde entregar en la Direcci&oacute;n Acad&eacute;mica una copia del Certificado OPSU, desde el 21/01/2016 hasta el 19/02/2016 en horario de 8:00 am a 11:30 am y de 1:30 pm a 3:30 pm.');" onclose="return false"> 
				<?php include_once('inc/reporte_e.php'); ?>
		<form name="datos_p" method="POST" action="planilla_r.php">
				<?php generarFormDatos(); ?>
		</form>
		</body>
	</html>
<?php
}

function generarFormDatos() {
	global $_d;

	print <<<C000
	<input type="hidden" name="cedula" value="{$_d['ci_e']}">
	<input type="hidden" name="contra" value="{$_d['apellidos']}">

C000
;
	reset($_d);
	while (list($nombre, $valor) = each($_d)) {
		print <<<C001
	<input type="hidden" name="$nombre" value="$valor">

C001
;
	}
}

function regresar() {
	global $raizDelSitio;
?>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language='javascript'>
	<!--
	function irAtras() {
		msg = 'En este momento el servidor esta ocupado \n';
		msg = msg + 'y no se pudo realizar la operacion.\n';
		msg = msg + 'Por favor, pulse \'Aceptar\' para \n';
		msg = msg + 'regresar a la planilla y volver a intentar.\n';
		alert(msg);
		document.datos_p.submit();
	}
	//-->
	</script>
    </head>
    <body onload="irAtras();">
	<form name="datos_p" method="POST" action="planilla_r.php">
	<?php generarFormDatos(); ?>
	</form>
    </body>
    </html>
<?php

}

function volveraIndice() {
	global $raizDelSitio;
?>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <META HTTP-EQUIV="Refresh"
        CONTENT="500;URL=<?php echo $raizDelSitio; ?>">
    </head>
    <body>
    </body>
    </html>
<?php
}

// programa principal;
if(envioValido()) {
	if(guardarDatos()) {
		reportar($_d['exp_e']);
	}
	else {
		regresar();
	}
}
else volveraIndice();
?>