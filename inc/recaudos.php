<?php
	include_once('inc/config.php');
	global $rangoInsc,$horaInsc, $fechaInsc, $num_ins;
	

	/*if ($num_ins <= '25'){$fec_hora=$fechaInsc[0].$horaInsc[0]}
	elseif ($num_ins > '25'){$fec_hora=$fechaInsc[0].$horaInsc[1].}
	elseif ($num_ins > '50'){$fec_hora=$fechaInsc[0].$horaInsc[2].}
	elseif ($num_ins > '75'){$fec_hora=$fechaInsc[0].$horaInsc[3].}
	elseif ($num_ins > '100'){$fec_hora=$fechaInsc[0].$horaInsc[4].}
	elseif ($num_ins > '125'){$fec_hora=$fechaInsc[1].$horaInsc[0].}
	elseif ($num_ins > '150'){$fec_hora=$fechaInsc[1].$horaInsc[1].}
	elseif ($num_ins > '175'){$fec_hora=$fechaInsc[1].$horaInsc[2].}
	elseif ($num_ins > '200'){$fec_hora=$fechaInsc[1].$horaInsc[3].}
	elseif ($num_ins > '200'){$fec_hora=$fechaInsc[1].$horaInsc[4].}*/


	print <<<R001
	<table id="recaudos" align="center" border="0" cellpadding="1" 
		cellspacing="2"	 width="740" 
		style="border-collapse:collapse;border-color:white; border-style:solid; background:white;">
		<tr class="instruc">
			<td>Estimado Bachiller:<br></td>
		</tr>
		<tr class="instruc">
			<td class="instruc" style="color:red;font-weight:bold;">
				Una vez completado el proceso de censo, debe entregar en la Direcci&oacute;n Acad&eacute;mica una copia del Certificado OPSU.<BR><BR>
			</td>
		</tr>
		<tr>
			<td class="instruc">Si resultas seleccionado debes acudir a la sede de la UNEXPO llevando los siguientes recaudos COMPLETOS en la fecha y hora que se indicar&aacute; oportunamente:
				<ul style="list-style:circle; background:white;">
					<li> T&iacute;tulo de Bachiller o T&eacute;cnico Medio (original y fondo negro n&iacute;tido).</li>
					
					<li>Partida de Nacimiento (original y fotocopia n&iacute;tida).</li>

					<li>Constancia Certificada de Calificaciones de Educaci&oacute;n
						Media (original y fotocopia n&iacute;tida).</li>

					<li>Original y Fotocopia n&iacute;tida de la C&eacute;dula de 
						Identidad VIGENTE. </li>

					<li>Una (1) fotograf&iacute;a de frente, tama&ntilde;o carnet 
						(NO instant&aacute;neas, sin perforaciones, recientes).</li>
					
					<li>Constancia Original de participaci&oacute;n en el proceso de seleccion del C.N.U. firmada y sellada.</li>

					<li>Una (1) Carpeta marr&oacute;n tama&ntilde;o Oficio nueva y con gancho.</li>
				</ul>
				Los resultados del censo ser&aacute;n publicados a trav&eacute;s de nuestra pagina web, mantente atento.
			</td>
		</tr>
	</table>
R001
;
?>