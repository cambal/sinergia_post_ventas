var controladorTiempo = "";
var cantidad=0;
var posicion=[];
var arrayQR=[];

function codigoAJAX() {
    var codigo = $("#codigo").val();
    var numero = (cantidad-1) == 0 ? 1 : cantidad-1;

    inicio=0;
    for(i=0;i<numero;i++){
        codigobarra = codigo.substring(inicio,posicion[i]);
        inicio = posicion[i];
        arrayQR.push(codigobarra);
        
        console.log((i+1)+" "+codigobarra);
    }

    cantidad=0;
    posicion = [];
    $("#codigo").val('');
}

$("#codigo").on("keyup", function(e) {
    var codigo = $("#codigo").val();
    largo = codigo.length;

    if(e.which == 13){
        cantidad++;
        posicion.push(largo);
    }

    clearTimeout(controladorTiempo);
    controladorTiempo = setTimeout(codigoAJAX, 500);
});