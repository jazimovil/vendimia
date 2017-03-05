$('#btn-new').click(function(event) {
	$('#uno').hide();
	$('#dos').show();
	window.onbeforeunload = confirmExit;
});

function seguro() {
	if (confirm('¿Seguro que desea salir de la pantalla actual?')) document.location = '/index.php';
}

function searchC(v) {
	$.ajax({type: "POST", url: "/ajax/autocomplete.php", data: 't=c&txt='+v,
		success: function(data) {
			$('.sugerencias.c').show().html(data);
			$('.sugerencias.c .elemento').click(function(event) {
				$('#clvC').val($(this).attr('clv'));
				$('#rfc').text($(this).attr('rfc'));
				$('#cliente').val($(this).attr('clv') + ' - ' + $(this).text());
				$('.sugerencias.c').hide();
			});
		}
	});
}

function searchA(v) {
	$.ajax({type: "POST", url: "/ajax/autocomplete.php", data: 't=a&txt='+v,
		success: function(data) {
			$('.sugerencias.a').show().html(data);
			$('.sugerencias.a .elemento').click(function(event) {
				$('#clvA').val($(this).attr('clv'));
				$('#articulo').val($(this).attr('clv') + ' - ' + $(this).text());
				$('.sugerencias.a').hide();
			});
		}
	});
}

function addArticulo() {
	var clv = $('#clvA').val();
	if ($('#lista tbody tr[clv="'+clv+'"]').length) alert('El producto ya está en la lista');
	else $.post('/ajax/addArticulo.php', {'clv': clv}, function(data, textStatus, xhr) {
		if (data != '0') {
			$('#lista tbody').append(data);
			calcularDatos();
		} else {
			alert('El artículo seleccionado no cuenta con existencia, favor de verificar');
		}
		$('#articulo, #clvA').val('');
	});
}

function setImporte(obj) {
	var tr = $(obj).closest('tr');
	var p = tr.find('.p').text().replace('$ ', '').replace(',', '');  // precio del articulo
	var imp = (obj.value * p).toFixed(2);  // importe = cantidad * precio
	tr.find('.i').text('$ ' + imp);
	calcularDatos();
}

function delArticulo(obj) {
	$(obj).closest('tr').remove();
	calcularDatos();
}

function ventas3(obj) {
	// revisar que esté seleccionado un cliente y que haya al menos un articulo seleccionado
	if (($('#clvC').val() == '') || !$('#lista tbody tr').length) alert('Los datos ingresados no son correctos, favor de verificar');
	else {
		$.post('/ajax/abonos.php', {tot: $('#tot').text().replace('$ ','')}, function(data, textStatus, xhr) {
			$('#articulo').closest('tr').hide();
			$('#abonos').show().html(data);
			obj.value = 'Guardar';
			$(obj).attr('onclick', "alert('Debe seleccionar un plazo para realizar el pago de su compra')");
		});
	}
}

function chk(obj) {
	$('input.ok').attr('onclick', 'comprar()');
}

function comprar() {
	$.post('/ajax/setVenta.php', $('#ventaTotal').serialize(), function(data, textStatus, xhr) {
		alert("Bien Hecho, Tu venta ha sido registrada correctamente");
		window.onbeforeunload = null;
		document.location = '/index.php';
	});
}
