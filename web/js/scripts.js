
$( document ).ready(function() {

  $('#appbundle_haz_un_donativo_contribution').on('change', function() {
  	if (this.value == 0.0) {
  		$('#appbundle_haz_un_donativo_otro_label').show();
  		$('#appbundle_haz_un_donativo_otro').show();
  	} else {
  		$('#appbundle_haz_un_donativo_otro_label').hide();
  		$('#appbundle_haz_un_donativo_otro').hide();
  	}
	})

	if ($('#appbundle_hazte_socio_correspondence').prop('checked')) {
		$('#correspondence-data').show();
	} else {
		$('#correspondence-data').hide();
	}

	$('#appbundle_hazte_socio_correspondence').on('change', function() {
		if ($('#appbundle_hazte_socio_correspondence').prop('checked')) {
			$('#correspondence-data').show();
		} else {
			$('#correspondence-data').hide();
		}
	})

	if ($('#appbundle_haz_un_donativo_correspondence').prop('checked')) {
		$('#correspondence-data').show();
	} else {
		$('#correspondence-data').hide();
	}


	$('#appbundle_haz_un_donativo_correspondence').on('change', function() {
		if ($('#appbundle_haz_un_donativo_correspondence').prop('checked')) {
			$('#correspondence-data').show();
		} else {
			$('#correspondence-data').hide();
		}
	})

	if($("input:radio[name='appbundle_haz_un_donativo[paymentMedia]']").is(":checked")) {
  	$('#datos_personales').show();
  	$('#button-continuar').hide();
	}

	$('#button-continuar').on('click', function() {
		if($("input:radio[name='appbundle_haz_un_donativo[paymentMedia]']").is(":checked")) {
			$('#datos_personales').show();
			$('#button-continuar').hide();
			$('#hazte-colaborador-mensaje').hide();
		} else {
			$('#hazte-colaborador-mensaje').show();
		}
	})

	$('#button-continuar-member').on('click', function() {
		if($('#appbundle_hazte_socio_frequency').val()){
			$('#datos_personales').show();
			$('#button-continuar').hide();
			$('#hazte-colaborador-mensaje').hide();
		} else {
			$('#hazte-colaborador-mensaje').show();
		}
	})

	$( "#appbundle_hazte_socio_frequency" ).change(function() {
  	if(!$('#appbundle_hazte_socio_frequency').val()){
  		$('#datos_personales').hide();
			$('#button-continuar').show();
  		$('#hazte-colaborador-mensaje').show();
  	}
	});

  // Mostrar y ocultar flecha inferior de los botones "hazte socio" y "haz un donativo"
  if(window.location.pathname.match('hazte-socio')){
    $('.haz-un-donativo--boton').addClass('flecha-oculta');
    $('.hazte-colaborador--boton > span').addClass('boton-activo');
  }
  if(window.location.pathname.match('haz-un-donativo')){
    $('.hazte-colaborador--boton').addClass('flecha-oculta');
    $('.haz-un-donativo--boton > span').addClass('boton-activo');
  }

  // Mover la etiqueta <label> de las formas de pago dentro del <div> contenedor
  $('.formas-de-pago > div:nth-child(1)').next('label').appendTo('.formas-de-pago > div:nth-child(1)');
  $('.formas-de-pago > div:nth-child(2)').next('label').appendTo('.formas-de-pago > div:nth-child(2)');

  // Inicializar slider
  $('.my-slider').unslider({
    // animation: 'fade',
    delay: 5000,
    autoplay: true,
    infinite: true,
    arrows: false,
    nav: false
  });

});

// Poner en marcha la libreria Selectric
$(function() {
  $('select').selectric();
});
