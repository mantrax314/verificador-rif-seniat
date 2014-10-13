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
			//aquí debería haber un código que verifique el rif desde la cedula
			alert("Es cedula");
		}else
			alert("Verifique el Rif Suministrado");
	}

}
//función que revisa el rif
function consultarif(rif){
	var data = {};
	data.rif=rif;
	//se usa corsproxy para que salte la restriccion de dominios cruzados
	$.get('http://www.corsproxy.com/contribuyente.seniat.gob.ve/getContribuyente/getrif', data, function(xml){
			var datosrif = $.xml2json(xml);
			var stDatos="<div><strong>Rif:</strong>"+$("#txtrif").val()+"</div>";
			stDatos+="<div><strong>Nombre:</strong>"+datosrif.Nombre+"</div>";
			stDatos+="<div><strong>Agente de Retencion:</strong>"+datosrif.AgenteRetencionIVA+"</div>";
			stDatos+="<div><strong>Tasa de Retencion:</strong>"+datosrif.Tasa+"</div>";
			stDatos+="<div><strong>Contribuyente Ordinario de IVA:</strong>"+datosrif.ContribuyenteIVA+"</div>";
			$("#datos").html(stDatos);
	}).fail(function(){ 
  		alert("Error en el Rif Buscado");
	});
}

function digitoVerificador(cedula){
	
}
</script>
</html>