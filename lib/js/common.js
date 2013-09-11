jQuery(function( $ ) {
		
		hikari = new HikariBox();
		
		$('.tip').easyTooltip({
				tooltipId: 'tooltip',
				xOffset: -10,
				yOffset: -32
		});
		
		$(window).on({
				keydown: function(e) {
						keyCode = (e.keyCode ? e.keyCode : e.which);
				
						if ((e.ctrlKey) && (keyCode == 122)) {
								$('#firma').toggle(0, function() {
										if ($(this).attr('active') == 'active') {
												$(this).removeAttr('active');
												$(this).find('#data a').blur();
										} else {
												$(this).attr('active', 'active')
												$(this).find('#data a:eq('+ SETTED_FIRMA +')').focus();
										}
								});
						}
						if (keyCode == 27) hikari.hide();
				},
				resize: function(e) {
						console.log($(this).height());
						$('#container').css({ 'min-height':($(this).height() - 180) });
				},
				load: function() {
						console.log($(this).height());
						$('#container').css({ 'min-height':($(this).height() - 180) });
				}
		});
		
		$('#firma #data a').on({
				keydown: function(e) {
						keyCode = (e.keyCode ? e.keyCode : e.which);
						var idFirma = $(this).attr('id');
						switch (keyCode) {
												case  9: return false; /* key: 'Tab' */
												case 37: case 38: $(this).prev('a').focus(); return false; /* key: 'Arrow Left',  'Arrow Up' */
								break;	case 39: case 40: $(this).next('a').focus(); return false; /* key: 'Arrow Right', 'Arrow Down' */
								break;	case 13: /* key: 'Enter' */
														$.ajax({
																url				: BASE_URL + 'default/json/setFirma/' + idFirma,
																type			: 'post',
																success		: function() { $('#firma').toggle().removeAttr('active').find('#data a').blur(); SETTED_FIRMA = idFirma; },
																error			: function(x, op, e) { alert(ajaxException(x, op, e)); }
														});
														
														/* Atualiza as informações do produto */
														switch (stripslashes(MODULE))
														{
																case 'produto':
																		$.ajax({
																				url				: BASE_URL + 'produto/json/reloadProduto/',
																				type			: 'post',
																				success		: function(html) { $('#container .produto-data').html(html); },
																				error			: function(x, op, e) { alert(ajaxException(x, op, e)); }
																		});
																break;
														}
														return false;
								break;
						}
				}
		});
		
});

function ajaxException(x, op, e)
{
		$('body').html(e + "\r\n" + x.statusText + "\r\n" + x.responseText);
}
function loading(status, object)
{
		if ( typeof(status) == 'undefined')  status = 1;
		if ( typeof(object) == 'undefined')  object = $('#container .welcome + div');
		switch (status) {
				case 0: 	$('html').css({'overflow':'hidden'});
									object.find('.loading').slideUp().children().remove();
									hikari.show(); /* Hikari Class from global JS's */
									break;
				case 1:		object.find('.loading').show(0).html('<div class="bullet"></div>').children().show().animate({'left':'97%'}, 1500, function(e) {
											$(this).hide(0);css({'left':0});
									});
									break;
				case 2: 	object.find('.loading').slideUp();
									break;
		}
}
function stripslashes(string)
{
		return string.replace(/[\\ \/]/ig, '');
}
function number_format (number, decimals, dec_point, thousands_sep)
{
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				s = '',
				toFixedFix = function (n, prec) {
						var k = Math.pow(10, prec);
						return '' + Math.round(n * k) / k;
				};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
}












