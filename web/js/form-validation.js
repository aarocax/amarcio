jQuery.validator.addMethod("iban", function(value, element) {
	// some quick simple tests to prevent needless work
	if (this.optional(element)) {
		return true;
	}
	if (!(/^([a-zA-Z0-9]{4} ){2,8}[a-zA-Z0-9]{1,4}|[a-zA-Z0-9]{12,34}$/.test(value))) {
		return false;
	}

	// check the country code and find the country specific format
	var iban = value.replace(/ /g,'').toUpperCase(); // remove spaces and to upper case
	var countrycode = iban.substring(0,2);
	var bbancountrypatterns = {
		'AL': "\\d{8}[\\dA-Z]{16}",
		'AD': "\\d{8}[\\dA-Z]{12}",
		'AT': "\\d{16}",
		'AZ': "[\\dA-Z]{4}\\d{20}",
		'BE': "\\d{12}",
		'BH': "[A-Z]{4}[\\dA-Z]{14}",
		'BA': "\\d{16}",
		'BR': "\\d{23}[A-Z][\\dA-Z]",
		'BG': "[A-Z]{4}\\d{6}[\\dA-Z]{8}",
		'CR': "\\d{17}",
		'HR': "\\d{17}",
		'CY': "\\d{8}[\\dA-Z]{16}",
		'CZ': "\\d{20}",
		'DK': "\\d{14}",
		'DO': "[A-Z]{4}\\d{20}",
		'EE': "\\d{16}",
		'FO': "\\d{14}",
		'FI': "\\d{14}",
		'FR': "\\d{10}[\\dA-Z]{11}\\d{2}",
		'GE': "[\\dA-Z]{2}\\d{16}",
		'DE': "\\d{18}",
		'GI': "[A-Z]{4}[\\dA-Z]{15}",
		'GR': "\\d{7}[\\dA-Z]{16}",
		'GL': "\\d{14}",
		'GT': "[\\dA-Z]{4}[\\dA-Z]{20}",
		'HU': "\\d{24}",
		'IS': "\\d{22}",
		'IE': "[\\dA-Z]{4}\\d{14}",
		'IL': "\\d{19}",
		'IT': "[A-Z]\\d{10}[\\dA-Z]{12}",
		'KZ': "\\d{3}[\\dA-Z]{13}",
		'KW': "[A-Z]{4}[\\dA-Z]{22}",
		'LV': "[A-Z]{4}[\\dA-Z]{13}",
		'LB': "\\d{4}[\\dA-Z]{20}",
		'LI': "\\d{5}[\\dA-Z]{12}",
		'LT': "\\d{16}",
		'LU': "\\d{3}[\\dA-Z]{13}",
		'MK': "\\d{3}[\\dA-Z]{10}\\d{2}",
		'MT': "[A-Z]{4}\\d{5}[\\dA-Z]{18}",
		'MR': "\\d{23}",
		'MU': "[A-Z]{4}\\d{19}[A-Z]{3}",
		'MC': "\\d{10}[\\dA-Z]{11}\\d{2}",
		'MD': "[\\dA-Z]{2}\\d{18}",
		'ME': "\\d{18}",
		'NL': "[A-Z]{4}\\d{10}",
		'NO': "\\d{11}",
		'PK': "[\\dA-Z]{4}\\d{16}",
		'PS': "[\\dA-Z]{4}\\d{21}",
		'PL': "\\d{24}",
		'PT': "\\d{21}",
		'RO': "[A-Z]{4}[\\dA-Z]{16}",
		'SM': "[A-Z]\\d{10}[\\dA-Z]{12}",
		'SA': "\\d{2}[\\dA-Z]{18}",
		'RS': "\\d{18}",
		'SK': "\\d{20}",
		'SI': "\\d{15}",
		'ES': "\\d{20}",
		'SE': "\\d{20}",
		'CH': "\\d{5}[\\dA-Z]{12}",
		'TN': "\\d{20}",
		'TR': "\\d{5}[\\dA-Z]{17}",
		'AE': "\\d{3}\\d{16}",
		'GB': "[A-Z]{4}\\d{14}",
		'VG': "[\\dA-Z]{4}\\d{16}"
	};
	var bbanpattern = bbancountrypatterns[countrycode];
	// As new countries will start using IBAN in the
	// future, we only check if the countrycode is known.
	// This prevents false negatives, while almost all
	// false positives introduced by this, will be caught
	// by the checksum validation below anyway.
	// Strict checking should return FALSE for unknown
	// countries.
	if (typeof bbanpattern !== 'undefined') {
		var ibanregexp = new RegExp("^[A-Z]{2}\\d{2}" + bbanpattern + "$", "");
		if (!(ibanregexp.test(iban))) {
			return false; // invalid country specific format
		}
	}

	// now check the checksum, first convert to digits
	var ibancheck = iban.substring(4,iban.length) + iban.substring(0,4);
	var ibancheckdigits = "";
	var leadingZeroes = true;
	var charAt;
	for (var i =0; i<ibancheck.length; i++) {
		charAt = ibancheck.charAt(i);
		if (charAt !== "0") {
			leadingZeroes = false;
		}
		if (!leadingZeroes) {
			ibancheckdigits += "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ".indexOf(charAt);
		}
	}

	// calculate the result of: ibancheckdigits % 97
    var cRest = '';
    var cOperator = '';
	for (var p=0; p<ibancheckdigits.length; p++) {
		var cChar = ibancheckdigits.charAt(p);
		cOperator = '' + cRest + '' + cChar;
		cRest = cOperator % 97;
    }
	return cRest === 1;
}, "Por favor introduzca un número IBAN válido");

jQuery.validator.addMethod("dniCheck", function(value, element) {
  if(/^([0-9]{8})*[a-zA-Z]+$/.test(value)){
    var numero = value.substr(0,value.length-1);
    var let = value.substr(value.length-1,1).toUpperCase();
    numero = numero % 23;
    var letra='TRWAGMYFPDXBNJZSQVHLCKET';
    letra = letra.substring(numero,numero+1);
    if (letra==let) return true;
    return false;
  }
  return this.optional(element);
}, "Por favor introduzca un número DNI/NIF válido");

jQuery.validator.addMethod("lettersonlys", function(value, element) {
  return this.optional(element) || /^[a-zA-Z\'\-\sàèìòùáéíóúñÑÁÉÍÓÚüÜ]+$/i.test(value);
}, "Solo letras");

jQuery.validator.addMethod("ifReceiveCorrespondenceSocioCheck", function(value, element) {
  if ($('#appbundle_hazte_socio_correspondence').prop('checked')) {
    if (value == "") {
      return false;  
    }
  } 
  return true;  
}, "El si desea recibir correspondencia, el campo no puede estar vacio...");

jQuery.validator.addMethod("ifReceiveCorrespondenceDonativoCheck", function(value, element) {
  if ($('#appbundle_haz_un_donativo_correspondence').prop('checked')) {
    if (value == "") {
      return false;  
    }
  } 
  return true;  
}, "El si desea recibir correspondencia, el campo no puede estar vacio...");

jQuery.validator.addMethod("ifDonationPositive", function(value, element) {
  if (value <= 0) {
    return false;  
  } 
  return true;  
}, "El importe no pueden ser negativo o cero");



$(function() {
	
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("#hazteSocioForm").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      "appbundle_hazte_socio[contribution]": {
      	required: true,
      	number:true,
      	normalizer: function( value ) {
	        return $.trim( value );
	      },
        ifDonationPositive: true,
      }, 

      "appbundle_hazte_socio[name]": {
      	required: true,
      	minlength: 2,
      	lettersonlys: true,
      },

      "appbundle_hazte_socio[surname]": {
      	required: true,
      	minlength: 2,
      	lettersonlys: true,
      },

      "appbundle_hazte_socio[nif]" : {
      	required: true,
      	dniCheck: true,
      },

      "appbundle_hazte_socio[iban]": {
        required: true,
        iban: true
      },

      "appbundle_hazte_socio[email]": {
        required: true,
        email: true,
      },

      "appbundle_hazte_socio[cp]": {
        required: true,
        number:true,
				minlength: 5,
				maxlength: 5
      },

      "appbundle_hazte_socio[phone]": {
        required: true,
        number:true,
				minlength: 9,
				maxlength: 9
      },

      "appbundle_hazte_socio[known]": {
      	required: true,	
      },

      "appbundle_hazte_socio[receive_correspondence]": {
      	required: false,	
      },

      "appbundle_hazte_socio[frequency]": {
      	required: true,	
      },

      "appbundle_hazte_socio[acceptConditions]": {
        required: true, 
      },

      "appbundle_hazte_socio[address]": {
        required: false,
        ifReceiveCorrespondenceSocioCheck: true,
      },

      "appbundle_hazte_socio[town]": {
        required: false, 
        ifReceiveCorrespondenceSocioCheck: true,
      },

      "appbundle_hazte_socio[province]": {
        required: false,
        ifReceiveCorrespondenceSocioCheck: true,
      },
      
    },
    // Specify validation error messages
    messages: {
    	"appbundle_hazte_socio[contribution]":{
    		required: "Por favor introduzca el importe de su aportación",
    		number: "Sólo números",
    	},
      "appbundle_hazte_socio[name]": {
      	required: "Por favor introduzca su nombre",
      	minlength: "Longitud mínima 2 caracteres",
      	maxlength: "Longitud máxima 50 caracteres",
      	lettersonlys: "Sólo letras...",
      },

      "appbundle_hazte_socio[surname]": {
      	required: "Por favor introduzca sus apellidos",	
      	minlength: "Longitud mínima 2 caracteres",
      	maxlength: "Longitud máxima 50 caracteres",
      	lettersonlys: "Sólo letras...",
      },

      "appbundle_hazte_socio[nif]": {
      	required: "Por favor introduzca su DNI/NIF",	
      	dniCheck: "Por favor introduzca un DNI/NIF válido",	
      },

      "appbundle_hazte_socio[iban]": {
        required: "Por favor introduzca el número IBAN de su cuenta",
        iban: "Por favor introduzca un número IBAN válido",
      },
      
      "appbundle_hazte_socio[email]": {
      	required: "Por favor introduzca su email",	
      	email: "Por favor introduzca un email válido",	
      },

      "appbundle_hazte_socio[cp]": {
      	required: "Por favor introduzca su código postal",	
				number: "Por favor introduzca un código postal válido",
				maxlength: "El código postal debe contener 5 dígitos.",
				minlength: "El código postal debe contener 5 dígitos.",

      },

      "appbundle_hazte_socio[phone]": {
      	required: "Por favor introduzca su número de teléfono",	
				number: "Por favor introduzca un número de teléfono válido",
				maxlength: "El número de teléfono debe contener 9 dígitos.",
				minlength: "El número de teléfono debe contener 9 dígitos.",

      },

      "appbundle_hazte_socio[frequency]":{
    		required: "Por favor seleccione la frecuencia con que desea realizar su aportación",
    	},

      "appbundle_hazte_socio[known]":{
        required: "Por favor indique como nos ha conocido.",
      },

      "appbundle_hazte_socio[acceptConditions]":{
        required: "Debe aceptar las condiciones de privacidad",
      },
      
      
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("#hazUnDonativoForm").validate({
  	rules: {
      "appbundle_haz_un_donativo[otro]": {
      	required: true,
      	number:true,
      	normalizer: function( value ) {
	        return $.trim( value );
	      }
      },

      "appbundle_haz_un_donativo[name]": {
        required: true,
        minlength: 2,
        lettersonlys: true,
      },

      "appbundle_haz_un_donativo[surname]": {
        required: true,
        minlength: 2,
        lettersonlys: true,
      },

      "appbundle_haz_un_donativo[email]": {
        required: true,
        email: true,
      },

      "appbundle_haz_un_donativo[nif]": {
        required: true,
        dniCheck: true,
      },

      "appbundle_haz_un_donativo[cp]": {
        required: true,
        number:true,
        minlength: 5,
        maxlength: 5
      },

      "appbundle_haz_un_donativo[phone]": {
        required: true,
        number:true,
        minlength: 9,
        maxlength: 9
      },

      "appbundle_haz_un_donativo[known]": {
        required: true, 
      },

      "appbundle_haz_un_donativo[address]": {
        required: false,
        ifReceiveCorrespondenceDonativoCheck: true,
      },

      "appbundle_haz_un_donativo[town]": {
        required: false, 
        ifReceiveCorrespondenceDonativoCheck: true,
      },

      "appbundle_haz_un_donativo[province]": {
        required: false,
        ifReceiveCorrespondenceDonativoCheck: true,
      },
    },

    messages: {
    	"appbundle_haz_un_donativo[otro]":{
    		required: "Por favor introduzca el importe de su aportación",
    		number: "Sólo números",
    	},

      "appbundle_haz_un_donativo[name]": {
        required: "Por favor introduzca su nombre",
        minlength: "Longitud mínima 2 caracteres",
        maxlength: "Longitud máxima 50 caracteres",
        lettersonlys: "Sólo letras...",
      },

      "appbundle_haz_un_donativo[surname]": {
        required: "Por favor introduzca sus apellidos", 
        minlength: "Longitud mínima 2 caracteres",
        maxlength: "Longitud máxima 50 caracteres",
        lettersonlys: "Sólo letras...",
      },

      "appbundle_haz_un_donativo[email]": {
        required: "Por favor introduzca su email",  
        email: "Por favor introduzca un email válido",  
      },

      "appbundle_haz_un_donativo[nif]": {
        required: "Por favor introduzca su DNI/NIF",  
        dniCheck: "Por favor introduzca un DNI/NIF válido", 
      },

      "appbundle_haz_un_donativo[cp]": {
        required: "Por favor introduzca su código postal",  
        number: "Por favor introduzca un código postal válido",
        maxlength: "El código postal debe contener 5 dígitos.",
        minlength: "El código postal debe contener 5 dígitos.",

      },

      "appbundle_haz_un_donativo[phone]": {
        required: "Por favor introduzca su número de teléfono", 
        number: "Por favor introduzca un número de teléfono válido",
        maxlength: "El número de teléfono debe contener 9 dígitos.",
        minlength: "El número de teléfono debe contener 9 dígitos.",

      },

      "appbundle_haz_un_donativo[known]":{
        required: "Por favor indique como nos ha conocido.",
      },


    }

  });

});