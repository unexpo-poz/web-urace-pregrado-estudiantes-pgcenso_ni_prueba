<?php
$enProduccion		= true;
$raizDelSitio		= 'http://'.$_SERVER['SERVER_NAME'].'/web/urace/pregrado/estudiantes/pgcenso_ni_prueba/';
$urlDelSitio		= 'http://www.poz.unexpo.edu.ve/web/urace/';
$tProceso			= 'Censo para Nuevo Ingreso';

$lapsoProceso		= '2017-1';
$tLapso				= ' Lapso '.$lapsoProceso;

$laBitacora			= $_SERVER[DOCUMENT_ROOT].'/log/pregrado/estudiantes/nuevo_ingreso/pgcenso_ni_prueba_'.$lapsoProceso.'.log';

$inscHabilitada		= true;
$fecha_i = '01-08-2016'; // Fecha de inicio del censo (DD-MM-AAAA).

//Si se requiere imprimir en planilla un mensaje extra, activar esto y colocarlo
// en inc/msgExtra.php
$mensajeExtra		= false; 
//Las distintas sedes de UNEXPO - actualizar de acuerdo a la necesidad
$sedesUNEXPO = array (	'BQTO' => array('BQTO','CARORA'), 
						'CCS'  => array('DACECCS'),
						'POZ'  => array('NINGRESO')
				);

// DSN, usuario y clave de la base de datos de PREGRADO
$dsnPregrado = "centura-dace";
$IdUsuario = "c";
$ClaveUsuario = "c";
// DSN, usuario y clave de la base de datos de PREGRADO				
//$sedeActiva = 'BQTO';
//$sedeActiva = 'CCS';
$sedeActiva = 'POZ';

$nucleos = $sedesUNEXPO[$sedeActiva];

//$vicerrectorado		= "Luis Caballero Mej&iacute;as";
//$vicerrectorado		= "Barquisimeto";
 $vicerrectorado		= "Puerto Ordaz";
$nombreDependencia = 'Unidad Regional de Admisi&oacute;n y Control de Estudios';

// * * * * * OJO OJO OJO OJO * * * * * 
// Cambiar esto manualmente de acuerdo a la jornada.
// Tipo de jornada
//	0 : deshabilitado 
//	1 : solo preinscritos en las materias preinscritas.
//	2 : solo preinscritos, pero pueden cambiar las materias.
//	3 : todos preinscritos o no preinscritos
$tipoJornada = 3;
$tablaOrdenInsc = 'ORDEN_INSCRIPCION';

//Unidad Tributaria:
$unidadTributaria	= 15051; //SUELDO MINIMO ACTUAL
// Los rangos de sueldo minimo en UT a mostrar para elegir el ingreso familiar:
$rangosIngresoFamiliar = array(1, 2, 3, 4, 5);


// Los rangos y fechas para la inscripcicion y de documentos:

$fechaInsc = array( 0=>'Lunes 13 de Octubre de 2008.',
					1=>'Martes 14 de Octubre de 2008.'
					);

$horaInsc = array(	0=>' De 08:30 AM a 05:00 PM',
					1=>' De 09:30 AM a 10:30 AM',
					2=>' De 10:30 AM a 11:30 AM',
					3=>' De 11:30 PM a 12:30 PM',
					4=>' De 12:30 PM a 01:30 PM',
					5=>' De 01:30 PM a 02:30 PM',
					6=>' De 02:30 PM a 03:30 PM',
					7=>' De 03:30 PM a 04:30 PM',
					8=>' De 04:30 PM a 05:30 PM',
					);

$rangoInsc = array(	0=>' 25',
					1=>' 50',
					2=>' 75',
					3=>' 100',
					4=>' 125',
					5=>' 150',
					6=>' 175',
					7=>' 200',
					8=>' 225',
					9=>' 250',
					);
					
					
					
// Proteccion de las paginas contra boton derecho, no javascript y navegadores no soportados:
if ($enProduccion){
	$botonDerecho = 'oncontextmenu="return false"';
	$noJavaScript = '<noscript><meta http-equiv="REFRESH" content="0;URL=no-javascript.php"></noscript>';
	$noCache	  = "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
	$noCache	 .= '<meta http-equiv="Expires" content="-1">';
	$noCacheFin	  = '<head><meta http-equiv="Pragma" content="no-cache"></head>';
}
else {
	$botonDerecho = '';
	$noJavaScript = '';
	$noCache	  = '';
	$noCacheFin	  = '';
}
?>