<html>
<head>
	<title>Consulta de Rif</title>
</head>

<body>
	<input id="txtrif" type="text" />
	<input type="button" value="BUSCAR RIF" onclick="buscarif();"/>
	<div id="datos"></div> 
</body>
<script src="http://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript" language="javascript"></script>
<script src="https://jquery-xml2json-plugin.googlecode.com/svn/trunk/jquery.xml2json.js" type="text/javascript" language="javascript"></script>
<script>
function buscarif(){
	//verifico que tenga una forma similar al rif
	var regEsRif = new RegExp("^([VEJPG]{1})([0-9]{9})$");
	if(regEsRif.test($("#txtrif").val())){
		consultarif($("#txtrif").val());
	}else{
		//si no lo es verifico si se parece a una cedula
		var regEsCedula = new RegExp("^([0-9]{8})$");
		if(regEsCedula.test($("#txtrif").val())){
			var documento = $("#txtrif").val();
			//Se obtiene el digito verificador para hacer una búsqueda de un rif que comience por V
			var stDigito = digitoVerificador(documento, "V");
			consultarif("V"+documento+stDigito);
		}else
			alert("Verifique el Rif Suministrado");
	}

}
//función que revisa el rif
/*
	rif: El documento completo sin espacios ej V168885551
*/
function consultarif(rif){
	var data = {};
	data.rif=rif;
	//se usa corsproxy para que salte la restriccion de dominios cruzados
	$.get('http://www.corsproxy.com/contribuyente.seniat.gob.ve/getContribuyente/getrif', data, function(xml){
			var datosrif = $.xml2json(xml);
			var stDatos="<div><strong>Rif:</strong>"+rif+"</div>";
			stDatos+="<div><strong>Nombre:</strong>"+datosrif.Nombre+"</div>";
			stDatos+="<div><strong>Agente de Retencion:</strong>"+datosrif.AgenteRetencionIVA+"</div>";
			stDatos+="<div><strong>Tasa de Retencion:</strong>"+datosrif.Tasa+"</div>";
			stDatos+="<div><strong>Contribuyente Ordinario de IVA:</strong>"+datosrif.ContribuyenteIVA+"</div>";
			$("#datos").html(stDatos);
	}).fail(function(){ 
  		alert("Error en el Rif Buscado");
	}).load(function(){ 
		$("#datos").html("<h1>Buscando</h1>");
	});
}

/*
	documento: string con la cadena de numeros del documento
	caracter: prefijo (V hasta el momento)

	Función desarrollada basado en la elaborada por @joseayram en php (https://github.com/joseayram/utils/blob/master/Rif.php)
			Y en información obtenida en Digito Verificador del RIF - Serrano Cid Asesores (http://www.serranocid.com/documentos/Digito%20Verificador%20RIF.xls)
*/
function digitoVerificador(documento, caracter){
	var arrnumeros= [];
	var digitoEspecial;
	var resultado;
	arrnumeros[7] = parseInt(documento[7])*2;
	arrnumeros[6] = parseInt(documento[6])*3;
	arrnumeros[5] = parseInt(documento[5])*4;
	arrnumeros[4] = parseInt(documento[4])*5;
	arrnumeros[3] = parseInt(documento[3])*6;
	arrnumeros[2] = parseInt(documento[2])*7;
	arrnumeros[1] = parseInt(documento[1])*2;
	arrnumeros[0] = parseInt(documento[0])*3;
	var suma=0;
	for (var i = 0; i < arrnumeros.length; i++) {
		suma+=arrnumeros[i];
	};
	if (caracter=="V"){
		digitoEspecial = 1;
		suma += 4;
	}
	resultado = 11-(suma%11);
	return (resultado);
}
</script>
</html>