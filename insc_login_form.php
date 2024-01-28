<?php
include_once('inc/vImage.php'); 
include_once('inc/config.php'); 

imprima_enc();
if ($inscHabilitada){
	imprima_form();
}
else {
	print <<<x001
		<font style="font-family:arial; font-size:14px; color:red;">
		Disculpe, en este momento el proceso no est&aacute; habilitado.
		</font>
x001
;
}
imprima_final();

function imprima_enc(){
	global $tProceso, $lapsoProceso, $enProduccion;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
	global $noCache;
	print $noCache;
?>
<title><?php echo $tProceso . $lapsoProceso; ?></title>

<script languaje="Javascript">
<!--
	if ((navigator.userAgent.indexOf("Opera")>=0) || (navigator.userAgent.indexOf("Safari")>=0)){
		alert("Disculpe, su cliente http no esta soportado en este sistema. Use Mozilla, Netscape o Internet Explorer"); 
		location.replace("no-soportado.php");	//	return; 
	}
// -->
</script>

  <script language="Javascript" src="md5.js">
   <!--
    alert('Error con el fichero js');
    // -->
  </script>
  <script languaje="Javascript">
<!--

  function validar(f) {
	if ((f.cedula_v.value == "")||(f.contra_v.value == "")) {
		alert("Por favor, escriba su cédula y apellido antes de pulsar el botón Entrar");
		return false;
	} 
	else {
		f.contra.value = f.contra_v.value.toUpperCase();
		f.contra_v.value = "";
		f.cedula.value = f.cedula_v.value;
		f.cedula_v.value = "";
		f.vImageCodP.value = f.vImageCodC.value;
		f.vImageCodC.value = "";
		window.open("","planillab","left=0,top=0,width=790,height=500,scrollbars=1,resizable=1,status=1");
		<?php if ($enProduccion){ ?>
		setTimeout("location.reload()",90000);
		<?php } ?>
		return true;
	}

}
//-->
  </script>          
<style type="text/css">
<!--
#prueba {
  overflow:hidden;
  color:#00FFFF;
  background:#F7F7F7;
}

.instruc {
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  background-color: #FFFF99;
}
.normal {
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color: white;
}
.boton {
  text-align: center; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#e0e0e0; 
  font-variant: small-caps;
  height: 20px;
  padding: 0px;
}

-->
</style>  

</head>


<body <?php global $botonDerecho; echo $botonDerecho; ?>>

<table id="table1" style="border-collapse: collapse;" border="0" cellpadding="0" cellspacing="1" width="750">

  <tbody>
  <tr>
    <td width="750">
          <p align="center" style="font-family:arial; font-weight:bold; font-size:20px;">
<?php			echo $tProceso .' '. $lapsoProceso; 
?>		  </p>
    </td>
  </tr>

  <tr>

       <td width="750" align="center">
<?php
}
function imprima_form(){
?>

	   <font class="normal"><br>Por
favor escribe tus datos y el c&oacute;digo de seguridad, luego pulsa el bot&oacute;n "Entrar" para
          poder acceder a la preinscripci&oacute;n</font></td>
   </tr>
  <tr>
      <td width="777" align="center">
      <form method="post" name="chequeo" onsubmit="return validar(this)" 
            action="planilla_r.php" target="planillab" >
          <p class="normal">&nbsp; C&eacute;dula:&nbsp;
        <input name="cedula_v" size="15" tabindex="1" type="text">&nbsp; &nbsp;
		Apellido:&nbsp;<input name="contra_v" size="20" tabindex="2" type="text">&nbsp;&nbsp;  
  &nbsp; C&oacute;digo de la derecha:&nbsp;
  <input name="vImageCodC" size="5" tabindex="3" type="text">&nbsp;
  <img src="inc/img.php?size=4" height="30" style="vertical-align: middle;">
  <input value="Entrar" name="b_enviar" tabindex="3" type="submit"> 
  <input value="x" name="cedula" type="hidden"> 
  <input value="x" name="contra" type="hidden">
  <input value="" name="vImageCodP" type="hidden"> 
</p>
      </form>

<?php //imprima_form
}

function imprima_final(){
?>
	  </td>
    </tr>
    <tr>
      <td class ="instruc"><b>NOTAS:</b>
		<ul style="list-style-type: square;">
			<li>Si en la lista de ingreso tus datos no son correctos, acude a 
				Control de Estudios para solucionar tu problema lo antes posible.
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
        </ul>
</td>
    </tr>
  </tbody>
</table>
</body>
<?php
//Evitar que la pagina se guarde en cache del cliente
global $noCacheFin;
print $noCacheFin;
?>
</html>
<?php
} //imprima_final	 
?>
