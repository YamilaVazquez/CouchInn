//Recibe un campo fecha con el formato aaaa-mm-dd
function validarFecha(campoFecha){
	if(campoFecha != '') {
		if(campoFecha.value.match(/^(0[1-9]|[12][0-9]|3[01])[-](0[1-9]|1[012])[-](19|20)\d\d$/)) {
			var arrayfecha = campoFecha.value.split("-"); /*[0] -> año; [1] -> mes; [2] -> dia*/
			var fecha_ing = new Date(arrayfecha[0],arrayfecha[1]-1,arrayfecha[2]); /*los meses van de 0 a 11 (por ej Mayo seria 4) por eso le resta 1*/
			var fecha = new Date();
			fecha.setHours(0,0,0,0);
			if(fecha_ing >= fecha ){				
				/*window.alert(arrayfecha);*/
				if ((arrayfecha[0] == 31) && (arrayfecha[1] == 4 || arrayfecha[1] == 6 || arrayfecha[1] == 9 || arrayfecha[1] == 11)) {
					alert('La fecha ingresada no es válida, ese mes tiene 30 días'); /*para meses de 30 días el día 31 no es válido*/
					return false;
				} else if((arrayfecha[0] >= 30) && (arrayfecha[1] == 2)){
					alert('La fecha ingresada no es válida. Febrero sólo tiene 28 días'); /*para febrero, los días 30 y 31 no son válidos*/
					return false;
				} else if((arrayfecha[0] == 29 & arrayfecha[1] == 2) && !(((arrayfecha[2] % 4) == 0) && ((arrayfecha[2] % 100) != 0 || (arrayfecha[2] % 400) == 0))){
					alert('La fecha ingresada no es válida. Febrero sólo tiene 28 días'); /*febrero 29 no es válido, salvo que sea año bisiesto*/
					return false;
				} else {
					return true;
				}
			}
			else{
				alert('La fecha ingresada es anterior a la fecha actual');
				return false;
			}			
		} else {
			alert('La fecha ingresada no es una fecha válida');
			return false;
		}
	}
};
//Recibe dos campo fecha con el formato aaaa-mm-dd
function validarPeriodo (fechaini, fechafin) {
	var arrayfechaini = fechaini.value.split("-"); /*[0] -> año; [1] -> mes; [2] -> dia*/
	var fecha_ini = new Date(arrayfechaini[0],arrayfechaini[1]-1,arrayfechaini[2]); /*los meses van de 0 a 11 (por ej Mayo seria 4) por eso le resta 1*/
	var arrayfechafin = fechafin.value.split("-"); /*[0] -> año; [1] -> mes; [2] -> dia*/
	var fecha_fin = new Date(arrayfechafin[0],arrayfechafin[1]-1,arrayfechafin[2]); /*los meses van de 0 a 11 (por ej Mayo seria 4) por eso le resta 1*/
	if(fecha_fin >= fecha_ini ){
		return true;
	} else {
		alert('La fecha de fin es anterior a la fecha de inicio');
		return false;
	}
};
/*function enviar(){ <!-- funcion que valida si el formulario es correcto -->
	var nombre = document.getElementById("nombre").value;
	var apellido = document.getElementById("apellido").value;
	var fecha = document.getElementById("fecha").value;
	var correo = document.getElementById("email").value;
	var contraseña= document.getElementById("contraseña").value;
	var cvalido=/[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/; <!-- para validar que sea un correo -->
	if(nombre == "") {
		alert("Completar el campo");
		return;
	}
	if(apellido == "") {
		alert("Completar el campo");
		return;
	}
	if (fecha == "") {
		alert("Completar el campo");
	}
	if(correo == "") {
		alert("Completar el campo");
		return;
	}
};*/
//Valida que los campos del login no esten vacios
function validarsesion() {
	var form = document.inisesion;
	if(campoMail(form.email)) {
		if(campoObligatorio(form.pass)) {
			return true;
		}
		else {
			alert('Debe completar su contraseña');
			return false;
		}
	}
	else {
		alert('Debe completar su eMail');
		return false;
	}
};
//	Funciones de validacion varias
// esperan campos (input) de formulario
function campoMail(campo){
	return ((campo.value.length > 4)  && (campo.value.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/g)) )
};
function campoURL(campo){
	return ((campo.value.length > 3)  && (campo.value.match(/^(http:\/\/)?(www.)?[0-9a-zA-ZñÑ-]+.(com|net|tur|org)(.[a-zA-Z]{2})?\/?$/g)))
};
// Longitud > 2
function campoObligatorio(campo){
	return (campo.value.length > 2 );
};
//Acepta letras y espacios
function campoLetras(campo){
	return ((campo.value.length > 3)  && (campo.value.match(/^[a-zA-ZÑñ ]+$/g)) );
};
//acepta string alfanumericos (letras,numeros y espacios)
function campoAlfanumerico(campo){
	return ((campo.value.length > 3)  && (campo.value.match(/^[a-zA-ZÑñ0-9 ]+$/g)) );
};
//acepta letras y/o numeros, sin espacios
function campoUsuario(campo){
	return ((campo.value.length > 3)  && (campo.value.match(/^[a-zA-ZÑñ0-9]+$/g)) );
};