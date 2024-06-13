function objectAjax() {
	var xmlhttp = false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
//Inicializo automaticamente la funcion read al entrar a la pagina o recargar la pagina;
addEventListener('load', read, false);

function read() {
	// $("#selUser").select2({
	$.ajax({
		type: 'POST',
		url: '?c=administrator&m=table_users',
		beforeSend: function () {
			$("#idArticles").html("Procesando, espere por favor...");
		},
		success: function (response) {
			$("#idArticles").html(response);
		}
	})
	// });
}