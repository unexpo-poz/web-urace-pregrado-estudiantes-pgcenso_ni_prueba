<php?
include_once ('../inc/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title><?php echo 'Instrucciones '.$tProceso . $tLapso; ?></title>
<style type="text/css">
<!--
.instruc {
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  background-color: #FFFFCC;
}
.act { 
  text-align: center; 
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color:#99CCFF;
}
-->
</style>  
</head>


<?php

    print <<<P001
<body onload="javascript:self.focus();">
<table border="0" width="680">
	<tr>
		<td class="act" style="font-size:14px; font-weight:bold;">
			INSTRUCCIONES
		</td>
	</tr>
	<tr>
		<td class="instruc">
		<ul style="list-style-type: square;">
			<li>
				Si presentas alg&uacute;n inconveniente para procesar tu solicitud, acude a la Unidad Regional de Admisi&oacute;n y Control de Estudios para solucionar tu problema lo antes posible o comun&iacute;cate por los telfs. <b>(0286) 9229520 (0286) 9626378 </b>.
			</li>
            <li>
				PUEDES VOLVER A INGRESAR las veces que lo requieras dentro del 
				periodo de preinscripciones para cambiar los datos o imprimir tu planilla.
			</li>
			<li>Completa correctamente los campos de la planilla. 
				<b>Todos los datos son importantes</b>.
			<li>Solo puedes dejar en
				blanco los campos correspondientes a "Direcci&oacute;n de Residencia" 
				si &eacute;sta es igual a tu direcci&oacute;n permanente (de tu hogar).
				Estos datos son muy importantes en caso de emergencias.
			</li>
			<li>Tus datos personales se usar&aacute;n en el registro acad&eacute;mico
				para elaborar tu t&iacute;tulo, por eso debes llenarlos tal y como aparecen en
				tus documentos.
			</li>
			<li>Si en la lista de ingreso tus datos no son correctos, acude a 
				Control de Estudios para solucionar tu problema lo antes posible.
			</li>
			<li>
				Cuando termines de llenar tu planilla pulsa el bot&oacute;n 'Procesar'.
			</li>
			<li>
				Pulsa el bot&oacute;n 'Borrar Formulario' para borrar todos los campos.
			</li>

        </ul>
        </td>
    </tr>
	<tr><td align="center">
		<input type="button" value="Cerrar" onclick="javascript:self.close();"></td></tr>
</table>
P001;
?>
</body>
</html>