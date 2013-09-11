jQuery(function( $ ) {
		
		$('#consultar-produto').submit(function(e) {
				e.preventDefault();
				var valor = $(this).find('input[name="valor"]').val();
				$.ajax({
						url					: BASE_URL + 'produto/json/getProduto',
						type				: 'post',
						data				: { valor:valor },
						dataType		: 'html',
						beforeSend	: function() { loading(); },
						success			: function(html) { $('.hikari-content').html(html); },
						complete		: function() { loading(0); },
						error				: function(x, op, e) { $('body').html(ajaxException(x, op, e)); }
				});
				$(this).find('input[name="valor"]').blur();
		});
		
});