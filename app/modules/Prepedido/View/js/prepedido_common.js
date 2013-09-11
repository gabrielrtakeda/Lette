jQuery(function( $ ) {
		
		tableRowClick();
		
		$('#new-prepedido').on({
				click: function(e) {
						e.preventDefault();
						$.ajax({
								url					: BASE_URL + 'prepedido/json/consultaCliente/',
								type				: 'post',
								beforeSend	: function() { loading(); },
								success			: function(html) { $('.hikari-content').html(html); },
								complete		: function() { loading(0); },
								error				: function(x, op, e) { ajaxException(x, op, e); }
						});
				}
		});
		
		$('#filter-pedido').submit(function(e) {
				e.preventDefault();
						var p = $(this).find('input[name="num_pedido"]').val();
						$.ajax({
								url					: BASE_URL + 'prepedido/json/getPrepedidoByN/' + p,
								type				: 'post',
								dataType		: 'html',
								beforeSend	: function() { loading(); },
								success			: function(html) { $('.jsonTable').html(html); },
								complete		: function() { loading(2); tableRowClick() },
								error				: function(x, op, e) { ajaxException(x, op, e); }
						});
		});
		
});

function tableRowClick() {
		$('.prepedido table.data tbody tr').on({
				click: function() {
						var self			= $(this);
						var id				= self.attr('id');
						var active		= (typeof(self.attr('active')) == 'undefined' ? false : self.attr('active'));
						var tr				= $('.tr-presub');
						var tr_active	= $('.prepedido table.data tbody tr[active="active"]');
						if (!active) {
								$.ajax({
										url					: BASE_URL + 'prepedido/json/getPreSub/' + id,
										type				: 'post',
										dataType		: 'html',
										success			: function(html) {
												if (tr.length > 0) {
														tr_active.removeClass('dev-total').removeAttr('active');
														tr.remove();
												}
												self.addClass('dev-total').after(html).attr('active', 'active');
										},
										error				: function(x, op, e) { ajaxException(x, op, e); }
								});
						} else {
								tr_active.removeClass('dev-total').removeAttr('active');
								tr.remove();
						}
				},
				mouseenter: function() {
						$('.complementar .obs span').html( $(this).attr('obs') );
						$('.complementar .mat span').html( $(this).attr('mat') );
						$('.complementar .po span').html( $(this).attr('po') );
				}
		});

		$(window).scroll(function(e) {
				var top = $(this).scrollTop();
				if (top >= 245) $('.complementar').addClass('complementar-fixed');
									 else $('.complementar').removeClass('complementar-fixed');
		});
}
function EditaValoresPrepedido()
{
		function load()
		{
				$('.order .data #qtde, .order .data #un').click(function() {
						foco($(this), 0);
				});
				$('button.calcular').click(function() {
						calculateFinal();
				});
				$('#preco input[name="order[Preco]"]').keydown(function(e) {
						setPrecos($(this), e);
				});
				
				cancelar();
				setNivelCredito();
				setCondicaoPagamento();
				setClienteEndereco();
				setObservacao();
				cadastrar();
		}
		
		function cancelar()
		{
				$('button.cancelar').click(function() {
						var c = confirm('Tem certeza que deseja cancelar o cadastro do Pré-Pedido?');
						if (c) {
								$.ajax({
										url				: BASE_URL + 'prepedido/json/cancelaPrepedido',
										type			: 'post',
										success		: function(html) { hikari.hide(); },
										error			: function(x, op, e) { ajaxException(x, op, e); }
								});
						}
				});
		}
		
		function cadastrar()
		{
				$('button.cadastrar').click(function() {
						var c = confirm('Tem certeza que todas as informações foram cadastradas corretamente?');
						if (c) {
								$.ajax({
										url				: BASE_URL + 'prepedido/json/cadastraPrepedido',
										type			: 'post',
										success		: function(html) { location = BASE_URL + 'prepedido'; },
										error			: function(x, op, e) { ajaxException(x, op, e); }
								});
						}
				});
		}
		
		function setNivelCredito()
		{
				$('.order .complementar .box input[name="nivel_credito"]').change(function() {
						var val = $(this).val();
						$.ajax({
								url				: BASE_URL + 'order/json/setOrderNivelCredito',
								type			: 'post',
								data			: { nivel_credito:val },
								error			: function(x, op, e) { ajaxException(x, op, e); }
						});
				});
		}
		
		function setCondicaoPagamento()
		{
				$('.order .complementar .box select[name="condicao_pagamento"]').change(function() {
						var val = $(this).val();
						$.ajax({
								url				: BASE_URL + 'order/json/setOrderCondicaoPagamento',
								type			: 'post',
								data			: { condicao_pagamento:val },
								error			: function(x, op, e) { ajaxException(x, op, e); }
						});
				});
		}
		
		function setClienteEndereco()
		{
				$('.order .complementar .box input[name="order[LocalEnt]"]').blur(function() {
						var val			= $(this).val();
						$.ajax({
								url				: BASE_URL + 'order/json/setOrderClienteEndereco',
								type			: 'post',
								data			: { cliente_endereco:val },
								error			: function(x, op, e) { ajaxException(x, op, e); }
						});
				});
		}
		
		function setObservacao()
		{
				$('.order .complementar .box input[name="order[Obs]"]').blur(function() {
						var val			= $(this).val();
						$.ajax({
								url				: BASE_URL + 'order/json/setOrderObservacao',
								type			: 'post',
								data			: { observacao:val },
								error			: function(x, op, e) { ajaxException(x, op, e); }
						});
				});
		}
		
		function reset(self)
		{
				var val = changeCommaToDot(self.find('input').val());
				self.find('span').html(number_format(val, 2, ',', '.'));
				$('.order .data').find('tr td .icon').removeClass('icon-accept').addClass('icon-edit');
				$('.order .data').find('tr td input').hide();
				$('.order .data').find('tr td span').show();
				setQuantidadePreco(self, val);
				calculateTotal();
		}
		
		function setQuantidadePreco(self, val)
		{
				if ((self.attr('id') == 'qtde') || (self.attr('id') == 'un')) {
						var presub = {
								index:	self.closest('tr').index(),
								value:	val
						}
						var method = (self.attr('id') == 'qtde' ? 'setPreSubQuantidade' : 'setPreSubPreco');
						$.ajax({
								url					: BASE_URL +'order/json/'+ method,
								type				: 'post',
								data				: { presub:presub },
								error				: function(x, op, e) { ajaxException(x, op, e); }
						});
				}
		}
		
		function foco(self)
		{
				// reset(self);
				self.find('.icon').removeClass('icon-edit').addClass('icon-accept');
				self.find('span').hide();
				self.find('input').show().focus();
				focusNext(self);
		}
		
		function focusNext(self)
		{
				self.keydown(function(e) {
						e.stopImmediatePropagation();
						var keyCode = (e.keyCode ? e.keyCode : e.which);
						
						if (e.shiftKey && (keyCode == 9)) {
								var prev		= self.closest('td').prev();
								var prevId	= prev.attr('id');
								
								reset(self);
								if (prevId == 'qtde' || prevId == 'un') {
										foco(prev);
								} else {
										prev = self.closest('tr').prev().find('#un');
										if (prev.length > 0) {
												foco(prev);
										} else {
												prev = $('.order .data #un:eq('+ (prev.length - 1) +')');
												foco(prev);
										}
								}
								return false;
						}
						else {
								var next		= self.closest('td').next();
								var nextId	= next.attr('id');
								switch (keyCode) {
										case  9:	reset(self);
															if (nextId == 'qtde' || nextId == 'un') {
																	foco(next);
															} else {
																	next = self.closest('tr').next().find('#qtde');
																	if (next.length > 0) {
																			foco(next);
																	} else {
																			next = $('.order .data #qtde:eq(0)');
																			foco(next);
																	}
															}
															return false;
															break;
										case 13:	reset(self);
															break;
								}
						}
				});
		}
		
		function setPrecos(self, e)
		{
				e.stopImmediatePropagation();
				var keyCode = (e.keyCode ? e.keyCode : e.which);
				
				switch (keyCode) {
						case 13:	$('.order .data tbody tr').each(function() {
													with ($(this)) {
															find('#un span').html(number_format(self.val(), 2, ',', '.'));
															find('#un input').val(self.val());
															calculateTotal();
													}
													var un = $(this).find('#un');
													var val = changeCommaToDot(un.find('input').val());
													setQuantidadePreco(un, val);
											});
											return false;
											break;
				}
		}
		
		function calculateTotal()
		{
				$('.order .data tbody tr').each(function(i) {
						self = $(this);
						var qtde	= (parseFloat(changeCommaToDot(self.find('#qtde input').val())) > 0	? parseFloat(changeCommaToDot(self.find('#qtde input').val()))	: 0);
						var un		= (parseFloat(changeCommaToDot(self.find('#un input').val())) > 0		? parseFloat(changeCommaToDot(self.find('#un input').val()))		: 0);
						self.find('#total').html(number_format((qtde * un), 2, ',', '.'));
				});
		}
		
		function calculateFinal()
		{
				var total = 0;
				$('.order .data tbody tr').each(function(i) {
						var t	= (parseFloat(changeCommaToDot(stripDots($(this).find('#total').html()))) > 0	? parseFloat(changeCommaToDot(stripDots($(this).find('#total').html())))	: 0);
						total += t;
				});
				$('#totalFinal span').html(number_format(total, 2, ',', '.'));
		}
		
		function changeCommaToDot(val)
		{
				return (/,/ig.test(val) ? val.replace(/,/ig, '.') : val);
		}
		
		function stripDots(val)
		{
				return (/,/ig.test(val) ? val.replace(/\./ig, '') : val);
		}
		
		load();
}












