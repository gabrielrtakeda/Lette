function HikariBox()
{
		this.show = show;
		this.hide = hide;
		this.setContent = setContent;
		
		function load()
		{
				$('.hikari-box').click(function() {
						hide();
				});
		}
		
		function setContent(html)
		{
				$('.hikari-content').html(html);
		}
		
		function show()
		{
				var hikari_width	= $('.hikari-content').width() + 30;
				var hikari_height	= $('.hikari-content').height() + 30;
				var window_height	= $(window).height();
				$('.hikari-content').css({'margin-left':'-'+ hikari_width / 2 +'px', 'max-height':window_height-30 +'px'});
				$('.hikari-box').show(0);
				$('.hikari-content').show(0, function() {
						$(this).find('a:eq(0)').focus();
						$(this).find('input[type="text"]:eq(0)').focus();
						handle();
				});
		}
		
		function hide()
		{
				$('html').css({'overflow':'auto'});
				$('.hikari-box, .hikari-content').fadeOut();
		}
		
		function handle(action)
		{
				$('.hikari-content a').on({
						keydown: function(e) {
								keyCode		= (e.keyCode ? e.keyCode : e.which);
								var self	= $(this);
								switch (keyCode) {
														case  9: return false; /* key: 'Tab' */
														case 37: case 38: $(this).prev('a').focus(); return false; /* key: 'Arrow Left',  'Arrow Up' */
										break;	case 39: case 40: $(this).next('a').focus(); return false; /* key: 'Arrow Right', 'Arrow Down' */
										break;	case 13: /* key: 'Enter' */
																switch (stripslashes(MODULE)) {
																						case 'produto'		: getProduto(this);
																																break;
																						case 'prepedido'	: eval(self.parent('#data').attr('method') + '(self);');
																																break;
																						case 'cliente'		: getCliente(self);
																																break;
																}
																return false;
										break;
								}
						},
						click: function() {
								var self	= $(this);
								switch (stripslashes(MODULE)) {
														case 'produto'		: getProduto(this); break;
														case 'prepedido'	: eval(self.parent('#data').attr('method') + '(self);');
														break;
								}
								return false;
						}
				});
		}
		
		/* PRODUTO */
		function getProduto(self)
		{
				var campo	= $(self).attr('class');
				var valor	= $(self).children().html();
				
				$.ajax({
						url					: BASE_URL + 'produto/json/getCor',
						type				: 'post',
						data				: {valor:valor},
						success			: function(html) { $('#container .produto-data').html(html); },
						complete		: function() {
								hikari.hide();
								$('#container .produto-data').fadeIn();
						},
						error				: function(x, op, e) { $('body').html(ajaxException(x, op, e)); }
				});
		}
		
		/* CLIENTE */
		function setCliente(self)
		{
				var valor	= $(self).children().html();
				$.ajax({
						url					: BASE_URL + 'cliente/json/setCliente/'+ valor,
						type				: 'post',
						dataType		: 'html',
						success			: function(html) { consultaOrder(); },
						error				: function(x, op, e) { ajaxException(x, op, e); }
				});
		}
		
		function getCliente(self)
		{
				var valor = self.attr('apelido');
				$.ajax({
						url					: BASE_URL + 'cliente/json/getCliente/' + valor,
						type				: 'post',
						data				: { cliente:valor },
						dataType		: 'html',
						success			: function(html) { $('.cliente .cliente-data').html(html); },
						complete		: function() { hikari.hide(); },
						error				: function(x, op, e) { ajaxException(x, op, e); }
				});
		}
		
		/* ORDER */
		function consultaOrder()
		{
				$.ajax({
						url					: BASE_URL + 'order/json/orderPrompt',
						type				: 'post',
						dataType		: 'html',
						success			: function(html) { hikari.setContent(html); },
						complete		: function() { hikari.show(); },
						error				: function(x, op, e) { ajaxException(x, op, e); }
				});
		}
		
		function setOrder(self)
		{
				var order_data = {
						material	: $(self).attr('mat'),
						po				: $(self).attr('po')
				}
				$.ajax({
						url					: BASE_URL + 'order/json/setOrder/',
						type				: 'post',
						data				: {order_data:order_data},
						dataType		: 'html',
						success			: function(html) { hikari.setContent(html); },
						complete		: function() { hikari.show(); },
						error				: function(x, op, e) { ajaxException(x, op, e); }
				});
		}
		
		load();
}