// JavaScript Document
       
    //CIUDADES DE NACIMIENTO
	function ciudadesNac(estado,pais,ciudad)
	{
		//alert(pais);
		fajax('estados_ciudades.php','capaN','estado='+estado+'&pais='+pais+'&ciudad='+ciudad,'post','0');
	}
    
    //MUNICIPIOS DE NACIMIENTO
	function municipios_naci(estado,pais,municipio,estadoBD)
	{
		//alert(estadoBD);
		fajax('municipios_nac.php','capampio','estado='+estado+'&pais='+pais+'&municipio='+municipio+'&estadoBD='+estadoBD,'post','0');
	}
	//PARROQUIA DE NACIMIENTO
	function parroquia_naci(estado,municipio,parroquia,municipioBD,estadoBD)
	{
		//alert(municipio);
		fajax('parroquia_nac.php','capapquia','&estado='+estado+'&municipio='+municipio+'&parroquia='+parroquia+'&municipioBD='+municipioBD+'&estadoBD='+estadoBD,'post','0');
	}
    //CIUDAD RESIDENCIA PERMANENTE
	function ciudadesresidencia(estado,ciudad)
	{
		var pais = '232';// Codigo para Venezuela
		//alert(ciudad);
		fajax('estados_ciudad_res.php','capa_re','estado='+estado+'&pais='+pais+'&ciudad='+ciudad,'post','0');
	}
	//MUNICIPIO RESIDENCIA PERMANENTE
	function municipios_residencia(estado, municipio, estadoBD)
	{
		var pais = '232';// Codigo para Venezuela
		fajax('municipio_res.php','capa_mu','estado='+estado+'&pais='+pais+'&municipio='+municipio+'&estadoBD='+estadoBD,'post','0');
	}
	//PARROQUIA RESIDENCIA PERMANENTE
	function parroquia_residencia(estado,municipio,parroquia, municipioBD)
	{
		//alert(parroquia);
		fajax('parroquia_res.php','capa_par','estado='+estado+'&municipio='+municipio+'&parroquia='+parroquia+'&municipioBD='+municipioBD,'post','0');
	}
	//CIUDAD RESIDENCIA
    function ciudadresi(estado,ciudad)
	{
		var pais = '232';// Codigo para Venezuela
		//alert(estado);
		fajax('ciudad_resi.php','capa_res','estado='+estado+'&pais='+pais+'&ciudad='+ciudad,'post','0');
	}
	//MUNICIPIO RESIDENCIA
	function municipios_resi(estado, municipio, estadoBD)
	{
		var pais = '232';// Codigo para Venezuela
		fajax('municipio_residencia.php','capa_mun','estado='+estado+'&pais='+pais+'&municipio='+municipio+'&estadoBD='+estadoBD,'post','0');
	}
	//PARROQUIA RESIDENCIA 
	function parroquia_resi(estado,municipio,parroquia, municipioBD)
	{
		//alert(parroquia);
		fajax('parroquia_residencia.php','capa_parr','estado='+estado+'&municipio='+municipio+'&parroquia='+parroquia+'&municipioBD='+municipioBD,'post','0');
	}
	
	
	
	//CIUDAD DE LICEO
	function ciudades(estado,pais,ciudad,estadoBD)
	{
		//alert(estadoBD.value);
		fajax('estados_ciudadesPlantel.php','capaCdP','estado='+estado+'&pais='+pais+'&ciudad='+ciudad+'&estadoBD='+estadoBD,'post','0');
	}
    //MUNICIPIO DEL LICEO
    function municipios(estado,pais,municipio,estadoBD)
	{
		//alert(municipio);
		fajax('estados_municipios.php','capa2','estado='+estado+'&pais='+pais+'&municipio='+municipio+'&estadoBD='+estadoBD,'post','0');
	}
	//PARROQUIA DE LICEO
	function parroquia_lic(estado, municipio, parroquia, municipioBD, estadoBD)
	{
		//alert(parroquia);
		fajax('parroquia_liceo.php','capapl','estado='+estado+'&municipio='+municipio+'&parroquia='+parroquia+'&municipioBD='+municipioBD+'&estadoBD='+estadoBD,'post','0');
	}	
	function AJAXCrearObjeto(){
	 var obj; 
	 
	 if(window.XMLHttpRequest) 
		{ // no es IE 
		obj = new XMLHttpRequest(); 
		} 
		else 
		{ // Es IE o no tiene el objeto 
			try { 
				 obj = new ActiveXObject("Microsoft.XMLHTTP"); 
				} 
			catch (e) { 
						alert('El navegador utilizado no est soportado'); 
					  } 
		} 
	 //alert ("objeto creado");
	 return obj; 
	} 
	
	function fajax(url,capa,valores,metodo,xml) //xml=1 (SI) xml=0 (NO)
	{
		//alert(capa);
		var ajax=AJAXCrearObjeto();
		var capaContenedora=document.getElementById(capa);
		//alert('capa '+capaContenedora);
		if (capaContenedora.type == "text"){
			texto = true;
		}else{
			texto = false;
		}
		//texto = true;
		var contXML;
		/* Creamos y ejecutamos la instancia si el metodo elegido es POST */
		if (metodo.toUpperCase()=='POST')
		{
	
			ajax.open ('POST', url, true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState==1) 
				{
					capaContenedora.innerHTML="<img src='loader.gif'>";
				}
				else if (ajax.readyState==4)
				{
					if (ajax.status==200)
					{
						if (xml==0)
						{	
							if (texto){
								document.getElementById(capa).value=ajax.responseText;
							}
							document.getElementById(capa).innerHTML=ajax.responseText;
						}
						if (xml==1)
						{
	
							var Contxml  = ajax.responseXML.documentElement;
							var items = Contxml.getElementsByTagName('nota')[0];
							var txt = items.getElementsByTagName('destinatario')[0].firstChild.data; 
							document.getElementById(capa).innerHTML=txt;
							
							
						}
					}
					else if (ajax.readyState==404)
					{
						capaContenedora.innerHTML = "Recargue nuevamente el estado";
					}
					else
					{
						capaContenedora.innerHTML="Error: "+ajax.status;
					}
				}
			}
		
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send(valores);
			return;
		}
		/* Creamos y ejecutamos la instancia si el metodo elegido es GET */
		if (metodo.toUpperCase()=='GET')
		{
			ajax.open ('GET', url, true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState==1) 
				{
					capaContenedora.innerHTML="<img src='loader.gif'>";
				}
				else if (ajax.readyState==4)
				{
					if (ajax.status==200)
					{
						if (xml==0)
						{
							document.getElementById(capa).innerHTML=ajax.responseText;
						}
						if (xml==1)
						{
							alert(ajax.responseXML.getElementsByTagName("nota")[0].childNodes[1].nodeValue); 
						}
					}
					else if (ajax.readyState==404)
					{
						capaContenedora.innerHTML = "Recargue nuevamente la P\u00e1gina(Tecla F5)";
					}
					else
					{
						capaContenedora.innerHTML="Error: "+ajax.status;
					}
				}
			}
		
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send(null);
			return;
		}
	}
	<!--FIN DEL SCRIPT--><!--SCRIPT QUE GENERAR EL LISTADO DE LAS CIUDADES CORRESPONDIENTE AL ESTADO SELECCIONADO-->