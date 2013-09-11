jQuery(function() {
		
		$('#consulta-cliente').submit(function(e) {
				e.preventDefault();
				
				var val = $(this).find('input[name="consulta_cliente"]').val();
				$.ajax({
						url					: BASE_URL + 'cliente/json/consultaCliente',
						type				: 'post',
						data				: { apelido:val },
						dataType		: 'html',
						beforeSend	: function() { loading(); },
						success			: function(html) { $('.hikari-content').html(html); },
						complete		: function() { loading(0); },
						error				: function(x, op, e) { $('body').html(ajaxException(x, op, e)); }
				});
		});
		
});