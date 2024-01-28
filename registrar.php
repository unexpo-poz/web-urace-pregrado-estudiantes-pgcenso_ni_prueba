<?php
    //  Este es registrar SIN transacciones 
    include_once('../inc/odbcss_c.php');
	include_once('inc/config.php');
	include_once ('../inc/activaerror.php');

    $datos_p = array();
    $asignat = array();
	$depo    = array();
    $errstr  = "";
	$sede    = "";

    $fecha  = date('Y-m-d', time() - 3600*date('I'));
    $hora   = date('h:i:s', time() - 3600*date('I'));
    $ampm   = date('A', time() - 3600*date('I'));
    $todoOK = true;
    $secc   =  "";
    $statusI = array();
    $inscrito = 0;

    function print_error($f,$sqlerr){
        print "<pre>".$f."\n".$sqlerr."</pre>";
    }
    
	function leer_datos_p($exp_e) {
        global $datos_p;
        global $errstr;
        global $E;
		global $sede;
		global $ODBCC_sinBitacora;
		global $masterID;
    
		if ($exp_e != ""){
            $Cusers = new ODBC_Conn("USERDOC","scael","c0n_4c4");
			$uSQL	= "SELECT userid FROM usuarios WHERE userid='".$exp_e."' ";
			$uSQL  .= "AND password='".$_POST['contra']."'";
			$Cusers->ExecSQL($uSQL);
			$clave_v = $Cusers->filas == 1; 
			if(!$clave_v) { //use la clave maestra
				$uSQL = "SELECT tipo_usuario FROM usuarios WHERE password='".$_POST['contra']."'";
				$Cusers->ExecSQL($uSQL);
				if ($Cusers->filas == 1) {
					$clave_v = (intval($Cusers->result[0][0],10) > 1000);
                }     
			}
			if ($clave_v) {		
				$Cdatos_p = new ODBC_Conn($sede,"c","c",$ODBCC_sinBitacora);
				$dSQL = " SELECT ci_e, exp_e, nombres, apellidos,c_uni_ca ";
				$dSQL = $dSQL."FROM DACE002 WHERE exp_e='".$exp_e."'";
				$Cdatos_p->ExecSQL($dSQL);
				$datos_p = $Cdatos_p->result[0];
				return ($Cdatos_p->filas == 1);
			}
            else return (false);
        }
        else return(false);      
    }
    
    function reportarError($errstr,$impmsg = true) {
	//global $errstr;
    if($impmsg) {
       print <<<E001
   
    <tr><td><pre> 
            Disculpe, Existen problemas con la conexi&oacute;n al servidor, 
            por favor contacte al personal de Control De Estudios e intente m&aacute;s tarde
    </pre></td></tr>
E001
;
    }
    $error_log=date('h:i:s A [d/m/Y]').":\n".$errstr."\n";
//    file_put_contents('errores.log', $error_log, FILE_APPEND);
}
    function consultarDatos($sinCupo) {
        
        global $ODBCSS_IP;
        global $datos_p; 
        global $asignat;
        global $errstr;
        global $lapso;
        global $inscribe;
        global $sede;
		global $Cmat;
		global $inscrito;
		global $depo;
        
		$actBitacora = (intval('0'.$inscrito) != 1 || intval('0'.$inscribe)==2 ); 
		//actualiza bitacora si no es solo reporte;
        $todoOK = true;       
        //$Cdep = new ODBC_Conn($sede,"usuario2","usuario2", $ODBCC_conBitacora, $laBitacora);
        $dSQL  = "SELECT A.c_asigna, asignatura, unid_credito, seccion, status FROM tblaca008 A, dace006 B ";
        $dSQL .= "WHERE exp_e='".$datos_p[1]."' AND lapso='$lapso' AND A.c_asigna = B.c_asigna ";
		$dSQL .= "AND NOT status IN('C','P') ORDER BY status desc, A.c_asigna"; 
        $Cmat->ExecSQL($dSQL,__LINE__); 
        if ($todoOK) {
            $asignat = $Cmat->result;
            if (!$sinCupo && $actBitacora) {
				// No actualizamos para no borrar condicion de problema con depositos
                //$dSQL = "UPDATE orden_inscripcion set inscrito='1'";
                //$dSQL = $dSQL." WHERE ord_exp='$datos_p[1]'";
                //$Cmat->ExecSQL($dSQL, __LINE__); 
				//actualizamos sexo y fecha de nacimiento:
                $dSQL = "UPDATE dace002 set sexo='".$_POST['sexo']."', ";
				$dSQL = $dSQL."f_nac_e='".$_POST['f_nac_e']."'"; 
                $dSQL = $dSQL." WHERE exp_e='$datos_p[1]'";
                $Cmat->ExecSQL($dSQL, __LINE__,$actBitacora); 
            }
         }
        return($todoOK);        
    }

    function reportarInscripcion() {
        
        global $asignat, $datos_p, $depo;
        $tot_dep = 0;
		$firma = "";        
        $tot_uc = 0;
        $total = count($asignat);
        for ($i=0; $i<$total;$i++){
            $tot_uc += intval($asignat[$i][2]);
		}

        print <<<R001
    <tr><td>&nbsp;</td>
    </tr>
        <tr><td width="750">
        <TABLE align="center" border="1" cellpadding="3" cellspacing="1" width="550"
				style="border-collapse: collapse;">
        <TR><TD>
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550">
            <tr>
                <td style="width: 60px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">C&Oacute;DIGO</div></td>
                <td style="width: 300px;" bgcolor="#FFFFFF">
                    <div class="matB">ASIGNATURA</div></td>
                <td style="width: 60px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">U.C.</div></td>
                <td style="width: 60px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">SECCI&Oacute;N</div></td>
                <td style="text-align:center; width: 70px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">ESTATUS</div></td>
            </tr>

R001
;
        $total=count($asignat);
        for ($i=0;$i<$total;$i++) {
            $sEstatus = array(2=>'RETIRADA', 7=>'INSCRITA', 9=>'INCLUIDA','C'=>'CENSADA', 'P' =>'PREINSCR');
			if ($asignat[$i][4] !='C' || $asignat[$i][4] !='C'){
				$firma .= $asignat[$i][0].$asignat[$i][3].$asignat[$i][4]." ";
				if ($asignat[$i][3] == '') {
					$asignat[$i][3] = '-';
				}
				print <<<R002
            <tr>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][0]}</div></td>
                <td bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][1]}</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][2]}</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][3]}</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$sEstatus[$asignat[$i][4]]}</div></td>
            </tr>

R002
;
			}
        }
        print <<<R0031
        </table>
        </TR></TD></TABLE>
R0031
;
		$key = substr(md5("320c6711"),0,16);
		srand();
		$td = mcrypt_module_open(MCRYPT_TRIPLEDES, '', MCRYPT_MODE_ECB, '');
		if(!$td) { print 'fallo';}
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$firma3D = mcrypt_generic($td, $firma);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td); 
		$firmaMD5 = strtoupper(md5($firma3D));
		$firma1 = substr($firmaMD5,0,16);
		$firma2 = substr($firmaMD5,16,32);
		$msgI = ''; //mensaje con instrucciones adicionales para el estudiante
		global $mensajeExtra;
		if($mensajeExtra) {
			include_once('inc/msgExtra.php');
		}
        print <<<R003
		</td><tr>
		<tr><td>
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550">
          <tr style="font-size: 2px;">
             <td colspan="2" > &nbsp; </td>
          </tr>
          <tr><form name="imprime" action="">
               <td valign="bottom"><p align="left">
                    <input type="button" value=" Imprimir " name="bimp"
                         style="background:#FFFF33; color:black; font-family:arial; font-weight:bold;" onclick="imprimir(document.imprime)"></p> 
               </td>
               <td valign="bottom"><p align="left">
                       <input type="button" value="Finalizar" name="bexit"
                        onclick="verificarSiImprimio()"></p> 
                </td></form>
          </tr>
          <tr style="font-size: 2px;">
             <td>&nbsp;</td>
             <td>&nbsp;<br>
                </td></tr>
		<tr>
                <td colspan="2" class="nota">
					<b>$msgI</b></td>
		</tr>
		<tr>
                <td colspan="2" class="nota"><br>
                La carga acad&eacute;mica inscrita por  el estudiante en esta
                planilla est&aacute; sujeta a control posterior por parte de Control de Estudios
                en relaci&oacute;n al cumplimiento de los prerrequisitos y 
                correquisitos sustentados en los pensa vigentes y a las cargas
                acad&eacute;micas m&aacute;ximas establecidas en el
                Reglamento de Evaluaci&oacute;n y Rendimiento Estudiantil vigente.
                La violaci&oacute;n de los requisitos y normativas antes mencionados
                conllevar&aacute; a la eliminaci&oacute;n de las asignaturas que no
                los cumplan.
                </td>
          </tr>
		  <tr><td colspan="2" class="matB"><br>C&Oacute;DIGO DE VALIDACI&Oacute;N:<br></td></tr>
		  <tr><td colspan="2" class="mat"><br>$firmaMD5<br></td></tr>
		  <tr><td colspan="2" class="matB">
			<IMG SRC="/inc/barcode.php?barcode={$firma1}&width=350&height=25&text=0" align="center">
		    </td>
		  </tr>
		  <tr><td colspan="2" class="nota">&nbsp;</td></tr>
          <tr><td colspan="2" class="matB">
			<IMG SRC="/inc/barcode.php?barcode={$firma2}&width=350&height=25&text=0" align="center">
		    </td>
		  </tr>
          </table>
        </tr>
        </table>
    </td>
    </tr>

R003
;
       
    }
       
    function asignaturasCorrectas() {
    // Revisa si las asignaturas que pretende inscribir son legales
	// es decir, si estan en su lista de materias_inscribir
		global $lapso, $datos_p;
		$correctas = true;       
        $asig	= array();
        $asig	= explode(" ",$_POST['asignaturas']);
        array_pop($asig);
        $total_a = count($asig);
		$total_mat = 0;
		if ($total_a > 0) {
			$listaAsig = '';
			$i = 0;
			while ($i<$total_a) {
				$listaAsig .= $asig[$i] . "','";
				$i=$i+4;
				$total_mat++;
			}
			$listaAsig = "('".$listaAsig."')";
            $Cdep  = new ODBC_Conn($_POST['sede'],"c","c",true);
            $dSQL  = "SELECT  c_asigna FROM materias_inscribir WHERE c_asigna in ".$listaAsig;
			$dSQL .= " AND exp_e='$datos_p[1]'";
            $Cdep->ExecSQL($dSQL,__LINE__,true);
            $correctas = ($Cdep->filas == $total_mat); 
		}            
		return ($correctas);
	}

    function asigYaInscrita($asig, $lapso, $i, $deshacer){
            
        global $Cmat;
        global $todoOK;
        global $datos_p;
        global $errstr;
        global $secc;
        global $statusI;
		global $sedeActiva;
           
		//$Cdep  = new ODBC_Conn($_POST['sede'],"usuario2","usuario2",true);
		if ($sedeActiva == "POZ") {
			$dSQL   = "SELECT A.seccion, status from dace006 A, ";
			$dSQL   = $dSQL . "tblaca004 B WHERE A.exp_e='$datos_p[1]' AND A.c_asigna='$asig' AND ";
			$dSQL   = $dSQL . "A.c_asigna=B.c_asigna AND A.seccion=B.seccion ";
			$dSQL   = $dSQL . "AND A.lapso=B.lapso AND A.lapso='$lapso' AND NOT status in('C', 'P') AND ";
			$dSQL   = $dSQL."COD_CARRERA LIKE '%$datos_p[4]%' ";
		}
		else {
			$dSQL   = "SELECT A.seccion, status from dace006 A, ";
			$dSQL   = $dSQL . "tblaca004 B WHERE A.exp_e='$datos_p[1]' AND A.c_asigna='$asig' AND ";
			$dSQL   = $dSQL . " A.c_asigna=B.c_asigna AND A.seccion=B.seccion  AND NOT status in('C', 'P') AND ";
			$dSQL   = $dSQL . " A.lapso=B.lapso AND A.lapso='$lapso'";
		}
		$Cmat->ExecSQL($dSQL,__LINE__);
        $Yainsc = ($Cmat->filas == 1);
        if ($Yainsc) {
            $secc   = $Cmat->result[0][0];
            if (!$deshacer){
                $statusI[$i] = $Cmat->result[0][1];
            }                              
        }
        else {
            if (!$deshacer) {
                $statusI[$i] = '0'; //No inscrita;
            }
            $secc = '';
        }
        return $Yainsc;            
    }
    
    function eliminarAsignatura($asig, $secc, $lapso, $status, $retiro){
            
        global $Cmat;
        global $todoOK;
        global $datos_p;
        global $errstr; 
            
        $sm ='';
        if ($retiro || $status != '0') {
            // la marcamos como retirada o con el estatus anterior
            if ($retiro) { 
                $sm = '2';
            }
            else {
                $sm = $status;
            }
            $dSQL   = "UPDATE dace006 SET status='$sm' WHERE c_asigna='$asig' ";
            $dSQL   = $dSQL . "AND exp_e='$datos_p[1]' AND lapso='$lapso'";
            $Cmat->ExecSQL($dSQL,__LINE__, true);
        }
        else {// lo borramos de la seccion ...
            
            $dSQL   = "DELETE FROM dace006 where c_asigna='$asig' ";
            $dSQL   = $dSQL . "AND exp_e='$datos_p[1]' AND lapso='$lapso'";
            $Cmat->ExecSQL($dSQL,__LINE__,true);
        }
        // Luego actualizamos los inscritos...
        if (($sm == '7') || ($sm == '9')) {
            $actInscritos='inscritos+1'; //hemos deshecho un retiro
            $condInscritos='inscritos>=0';
        }
        else {
            $actInscritos='inscritos-1'; //hemos deshecho una inscripcion o inclusion
            $condInscritos='inscritos>0';
        }
        if ($todoOK && ($Cmat->fmodif == 1)){
            if ($status !='2') {
				if ($sedeActiva == "POZ") {
					$dSQL = "UPDATE tblaca004 SET inscritos=$actInscritos WHERE ";
					$dSQL = $dSQL."c_asigna='$asig' AND seccion='$secc' AND lapso='$lapso' AND $condInscritos AND ";
					$dSQL = $dSQL."COD_CARRERA LIKE '%$datos_p[4]%' ";
				}
				else {
					$dSQL   = "UPDATE tblaca004 SET inscritos=$actInscritos WHERE ";
					$dSQL   = $dSQL."c_asigna='$asig' AND seccion='$secc' AND lapso='$lapso' AND $condInscritos";
				}
             $Cmat->ExecSQL($dSQL,__LINE__,true);
            }
        }
    }

 	function asignaturaCensada($asig, $lapso, $exp) {
		global $Cmat;
		//$Cdep  = new ODBC_Conn($_POST['sede'],"usuario2","usuario2",true);
		$pSQL  = "SELECT exp_e from dace006 where c_asigna='$asig' AND ";
		$pSQL .= "lapso='$lapso' AND exp_e='$exp' and status='C'";
        $Cmat->ExecSQL($pSQL,__LINE__,true);
        return ($Cmat->filas == 1);
	}

    function deshacerTodo($dAsig, $i, $lapso){
        
        global $datos_p;
        global $secc;
        global $statusI;

        $secc = "";
        $k=0;
        while ($k<$i) {
            $asig = $dAsig[$k];
            $iSec = $dAsig[$k+1];
            $iRep = $dAsig[$k+2];
            if (asigYaInscrita($asig, $lapso, $k, true)) {
                eliminarAsignatura($asig, $secc, $lapso, $statusI[$k], false);
            }
            $k=$k+4;
        }    
    }

	function inscribirAsignatura($asig, $iSecc, $repite, $lapso){
            
        global $Cmat;
        global $datos_p;
        global $errstr;
        global $E;
        global $inscribe; 
        global $fecha, $sedeActiva;
        
        $inscrita = false;
        //Buscar nro de acta
		//$Cdep  = new ODBC_Conn($_POST['sede'],"usuario2","usuario2",true);
		if ($sedeActiva == "POZ") {
			$dSQL   = "SELECT acta FROM tblaca004 WHERE c_asigna='$asig' ";
			$dSQL   = $dSQL . "AND seccion='$iSecc' AND lapso='$lapso' AND ";
			$dSQL   = $dSQL."COD_CARRERA LIKE '%$datos_p[4]%' ";
		}
		else {
			$dSQL   = "SELECT acta FROM tblaca004 WHERE c_asigna='$asig' ";
			$dSQL   = $dSQL . "AND seccion='$iSecc' AND lapso='$lapso'";
		}
        $Cmat->ExecSQL($dSQL,__LINE__,true);
        $acta = $Cmat->result[0][0];
        if ($inscribe == 1) {
			$iStatus = '7'; //modo inscripcion
        }
        else {
			$iStatus = '9';//modo inclusion
        }   
 		//Sumar un inscrito y si lo hace entonces proceder a insertar
		if ($sedeActiva == "POZ") {
			$dSQL   = "UPDATE tblaca004 SET inscritos=inscritos+1 WHERE ";
			$dSQL   = $dSQL."c_asigna='$asig' AND seccion='$iSecc' AND lapso='$lapso'";
			$dSQL   = $dSQL. " AND inscritos<tot_cup AND ";
			$dSQL   = $dSQL."COD_CARRERA LIKE '%$datos_p[4]%' ";
		}
		else {
			$dSQL   = "UPDATE tblaca004 SET inscritos=inscritos+1 WHERE ";
			$dSQL   = $dSQL."c_asigna='$asig' AND seccion='$iSecc' AND lapso='$lapso'";
			$dSQL   = $dSQL. " AND inscritos<tot_cup";
		}
        $Cmat->ExecSQL($dSQL,__LINE__,true);
        if ($Cmat->fmodif == 1){ //se sumo un inscrito, proceder a insertarlo
			if (asignaturaCensada($asig, $lapso, $datos_p[1])){
				$dSQL  = "UPDATE dace006 SET acta='$acta', seccion='$iSecc', ";
				$dSQL .= "status='$iStatus', status_c_nota='$repite', ";
				$dSQL .= "fecha='$fecha' WHERE lapso='$lapso' ";
				$dSQL .= "AND c_asigna='$asig' AND exp_e='$datos_p[1]'";
			}
			else {
				$dSQL  = "INSERT INTO dace006 (acta, lapso, c_asigna, seccion, exp_e, status, ";
				$dSQL .= "status_c_nota, fecha) VALUES ('$acta','$lapso','$asig', ";
				$dSQL .= "'$iSecc','$datos_p[1]','$iStatus','$repite','$fecha')";
			}
			$Cmat->ExecSQL($dSQL,__LINE__,true);
			$inscrita = ($Cmat->fmodif == 1);
        }
 		return($inscrita);
    }
    
    function registrar_asig() {
        
        global $ODBCSS_IP;
        global $datos_p;
        global $errstr;
        global $lapso;
        global $todoOK;
        global $secc;
        global $inscribe;
        global $Cmat;

        $todoOK    = true;
        $aInscrita = false; 
        $dAsig     = array();
        // $_POST['asignaturas'] trae : CODIGO1 SECCION1 condREP1 CODIGO2 SECCION2 condREP2...    
        $dAsig   = explode(" ",$_POST['asignaturas']);
        array_pop($dAsig);
        $total_a = count($dAsig);
        $secc    = "";
        $cupo    = 0;
        $acta    = "";
        $noInscritas ="";
        $i = 0;
		//$Cmat->iniciarTransaccion("Inicio Transaccion");
		while ($i<$total_a) {
            $asig = $dAsig[$i];
            $iSec = $dAsig[$i+1];
            $iRep = $dAsig[$i+2];
            //print_r($dAsig);
            $retiro = ($iSec == '-1');
            if (asigYaInscrita($asig, $lapso, $i, false)){//ojo: en asigYaInscrita se actualiza $secc
                if ($iSec != $secc) {
                    //eliminar la asignatura con status='0' (borrarla completa)
                    eliminarAsignatura($asig, $secc, $lapso,'0', $retiro);
                    //print "ya inscrita y eliminada $asig $secc<br>";
                }
            }
            if ($todoOK) {
                $aInscrita = ($iSec == $secc);
                if (!$aInscrita && !$retiro) {
                    $aInscrita = inscribirAsignatura($asig, $iSec, $iRep, $lapso);
                    //print "Inscrita $asig $secc<br>";
    
                    if (!$aInscrita) {
                        deshacerTodo($dAsig, $i, $lapso);
                        return array($todoOK, true, $asig, $iSec);
                    }
                }
            }
            $i=$i+4;
        }
		//if ($Cmat->finalizarTransaccion("Fin Transaccion")) {
			return array($todoOK, false, '','');
		//}
		//else {
		//	$Cmat->deshacerTransaccion("Rollback Transaccion");
        //    return array($todoOK, true, $asig, $iSec);
		//}
    }


     
	 function imprimeH() {
        
        global $hora;
        global $ampm;
        global $datos_p;
        global $tLapso;
        global $inscribe;
        
        $fecha = date('d/m/Y', time() - 3600*date('I'));
        if ($inscribe == '1') {
            $titulo = "Inscripci&oacute;n";
        }
        else if ($inscribe == '2'){
            $titulo = "Inclusi&oacute;n y Retiro";
        }
        print <<<TITULO
    <tr><td class="dp">&nbsp;</td><tr> 
    <tr>
        <td width="750">
        <p class="tit14">
        Planilla de $titulo $tLapso</p></td>
    </tr>
TITULO
;
?>
    <tr><td width="750">
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550">
            <tr><td class="dp">&nbsp;</td><tr> 
            <tr><td class="dp" style="text-align: right;"> 
<?php 
        print "Fecha:&nbsp; $fecha &nbsp; Hora: $hora $ampm </td></tr>";
?>   
            <tr><td class="dp">&nbsp;</td><tr> 
 	   </table>
       </td>
    </tr>
    <tr>
		<td width="750" class="tit14">
        Datos del Estudiante
		</td>
	</tr>
    <tr><td class="dp">&nbsp;</td><tr> 
	<tr>
		<td>
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="570"
				style="border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="width: 250px;" bgcolor="#FFFFFF">
                        <div class="dp">Apellidos:</div></td>
                    <td style="width: 250px;" bgcolor="#FFFFFF">
                        <div class="dp">Nombres:</div></td>
                    <td style="width: 110px;" bgcolor="#FFFFFF">
                        <div class="dp">C&eacute;dula:</div></td>
                    <td style="width: 114px;" bgcolor="#FFFFFF">
                        <div class="dp">Expediente:</font></td>
                </tr>

                <tr>
                    <td bgcolor="#FFFFFF">
                        
<?php
        print <<<P002
                       <div class="dp">{$datos_p[3]}</div></td>
                    <td bgcolor="#FFFFFF">
                       <div class="dp">{$datos_p[2]}</div></td>
                    <td bgcolor="#FFFFFF">
                       <div class="dp">{$datos_p[0]}</div></td>
                    <td style="width: 114px;" bgcolor="#FFFFFF">
                       <div class="dp">{$datos_p[1]}</div></td>
                </tr>
            </tbody>
        </table>
    </td>
    </tr>
    <tr>
    <td width="750">
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="570">
            <tbody>
                <tr>
                    <td style="width: 570px;" bgcolor="#FFFFFF">
                        <div class="dp">Especialidad: {$_POST['carrera']} </div></td>
                </tr>
            </tbody>
        </table>
    </td>
    </tr>
P002
; 
    } //imprime_h   
?>
    
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
<?php    
    $formOK = false;
	$inscribeN = 0;
	if (isset($_SERVER['HTTP_REFERER'])) {
		$formOK = ($_SERVER['HTTP_REFERER'] == $raizDelSitio .'planilla_r.php');
	}

    if (isset($_POST['inscribe'])){
       $inscribe = $_POST['inscribe'];
       $inscribeN = intval('0'.$inscribe);
    }
    if($formOK && isset($_POST['exp_e']) && ($inscribeN>0)) {
		$lapso		= $_POST['lapso'];    
		$inscrito	= intval($_POST['inscrito']);
		$sede		= $_POST['sede'];
	    $Cmat		= new ODBC_Conn($sede,"usuario2","usuario2",$ODBCC_conBitacora, $laBitacora);
		$formOK		= leer_datos_p($_POST['exp_e']);
		if ($formOK) {
			$formOK	= asignaturasCorrectas();
		}
	}
	if ($formOK) {
?>  

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<?php
			print $noCache; 
			print $noJavaScript; 
		?>
		<title>Planilla de Inscripci&oacute;n <?php print $tLapso; ?></title>
		<script languaje="Javascript">
		<!--
<?php
        print "Estudiante = '$datos_p[2]';";
?>
        var Imprimio = false;
        
        function imprimir(fi) {
            with (fi) {
                bimp.style.display="none";
                bexit.style.display="none";
                window.print();
                Imprimio = true;
                msgI = Estudiante + ':\nSi mandaste a imprimir tu planilla\n';
                msgI = msgI + "pulsa el botón 'Finalizar' y ve a retirar tu planilla por la impresora,\n";
                msgI = msgI + 'de lo contrario vuelve a pulsar Imprimir\n';
                //alert(msgI);
                bimp.style.display="block";
                bexit.style.display="block";
            }
        }
        function verificarSiImprimio(){
            window.status = Estudiante + ': NO TE VAYAS SIN IMPRIMIR TU PLANILLA';
            if (Imprimio){
                window.close();
            }
            else {
                msgI = '            ATENCION!\n' + Estudiante;
                alert(msgI +':\nNo te vayas sin imprimir tu planilla');
				Imprimio = true;
            }
        }
		<!--
        document.writeln('</font>');
		//-->
        </script>
		<style type="text/css">
		<!--
		.titulo {
			text-align: center; 
			font-family:Arial; 
			font-size: 13px; 
			font-weight: normal;
			margin-top:0;
			margin-bottom:0;	
		}
		.tit14 {
			text-align: center; 
			font-family: Arial; 
			font-size: 13px; 
			font-weight: bold;
			letter-spacing: 1px;
			font-variant: small-caps;
		}

		.nota {
			text-align: justify; 
			font-family: Arial; 
			font-size: 11px; 
			font-weight: normal;
			color: black;
		}
		.mat {
			text-align: center; 
			font-family: Arial; 
			font-size: 11px; 
			font-weight: normal;
			color: black;
			vertical-align: top;
		}
		.matB {
			font-family:Arial; 
			font-size: 11px; 
			font-weight: bold;
			color: black; 
			text-align: center;
			vertical-align: top;
			height:20px;
			font-variant: small-caps;
		}
		.dp {
			text-align: left; 
			font-family: Arial; 
			font-size: 11px;
			font-weight: normal;
			background-color: #FFFFFF; 
			font-variant: small-caps;
		}
		.depo {
			text-align: center; 
			width: 150px;
			background-color: #FFFFFF;
            font-size: 12px;
			color: black;
			font-family: courier;
		}
		-->
		</style>
		</head>
        <body  <?php global $botonDerecho; echo $botonDerecho; ?> onload="javascript:self.focus();" 
		      onclose="return false">
		<table align="left" border="0" width="750" id="table1" cellspacing="1" cellpadding="0" 
			   style="border-collapse: collapse">
    <tr><td>
		<table border="0" width="750" cellpadding="0">
		<tr>
		<td width="125">
		<p align="right" style="margin-top: 0; margin-bottom: 0">
		<img border="0" src="imagenes/unex1bw.jpg" 
		     width="50" height="50"></p></td>
		<td width="500">
		<p class="titulo">
		Universidad Nacional Experimental Polit&eacute;cnica</p>
		<p class="titulo">
		Vicerrectorado <?php echo $vicerrectorado; ?></font></p>
		<p class="titulo">
		<?php echo $nombreDependencia ?></font></td>
		<td width="125">&nbsp;</td>
		</tr><tr><td colspan="3" style="background-color:#D0D0D0;">
		<font style="font-size:1pt;"> &nbsp;</font></td></tr>
	    </table></td>
    </tr>
<?php
        if (intval('0'.$inscrito) != 1 || $inscribeN=2){
            list ($inscOK, $sinCupo, $asig, $seccion) = registrar_asig();
        }
        else {
            $inscOK = true;
            $sinCupo = false;
        }
        if ($inscOK){
            $datosOK = consultarDatos($sinCupo);
            if (!$sinCupo){
                imprimeH();
                reportarInscripcion();
                reportarError($errstr,false);
            print <<<FINAL0
        </td></tr>
        </table>
        </body>
        </html>
FINAL0
;        
            }
            else if (!$datosOK) {
                imprimeH();
                reportarError($errstr);
                print <<<FINAL1
        </td></tr>
        </table>
        </body>
        </html>
FINAL1
;
                exit;
            }
            if ($sinCupo) { //reportar el error de sin cupo
            reportarError($errstr,false);    
            print <<<ERRORSC
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>Asignatura sin cupo : $asig, Secci&oacute;n: $seccion</title>
        </head>
        <body   onload="javascript:self.focus()">
        <form name ="sincupo" method="POST" action="planilla_r.php">
            <input type="hidden" name="cedula" value="{$_POST['cedula']}">
            <input type="hidden" name="contra" value="{$_POST['contra']}">
            <input type="hidden" name="asignaturas" value="{$_POST['asignaturas']}">
            <input type="hidden" name="asigSC" value="$asig">
            <input type="hidden" name="seccSC" value="$seccion">
        </form>
        <script languaje="Javascript">
        <!--
        with (document){
           sincupo.submit();
        }
        -->
        </script>
        </body>
</html>

ERRORSC
;        
            } //if($sinCupo)
        
        }//if insc_ok
        else {
            imprimeH();
            reportarError($errstr);
            print <<<FINAL2
        </td></tr>
        </table>
        </body>
        </html>
FINAL2
;        
        }
    } //if $formOK
    else {
?>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <META HTTP-EQUIV="Refresh"
        CONTENT="0;URL=<?php echo $raizDelSitio; ?>">
        </head>
        <body>
        </body>
        </html>
<?php
    }

?>
