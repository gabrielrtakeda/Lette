
jQuery(function( $ ) {
		
		$('input[name="login_uid"]').on({
				keypress: function() {
						if ($(this).val() == 'LOGIN') $(this).val('');
				},
				blur: function() {
						if ($(this).val() == '') $(this).val('LOGIN');
				}
		});
		
		var section_height = 0;
		$('.uid-section > .mask').animate({height:0}, 700, function() {
				section_height = $(this).parent().height();
				$(this).parent().find('input').attr('active', true).animate({opacity:1}, 500).focus();
		});
		
		$('input[name="login_uid"], input[name="login_pwd"]').on({
				focus: function() {
						var inputs = 'input[name="login_uid"], input[name="login_pwd"]';
						
						if (!$(this).attr('active')) {
								with( $('input[active="true"]').parent() ) {
										find('.mask').animate({height:section_height}, 500, function() {
												$(this).parent().find('input').css({opacity:0});
										});
								}
								$(inputs).removeAttr('active');
								$(this).attr('active', true);
								section_height = $('input[active="true"]').parent().find('.mask').height();
								$('input[active="true"]').parent().find('.mask').animate({height:0}, 500, function() {
										$(this).parent().find('input').animate({opacity:1});
								});
						}
				}
		});
		
		login();
		
});
		
		function login() {
				$('#login-access').submit(function(e) {
						var uid = $(this).find('input[name="login_uid"]').val();
						var pwd = $(this).find('input[name="login_pwd"]').val();
						$.ajax({
								url				: BASE_URL + 'default/login/login',
								type			: 'post',
								data			: {login_uid:uid, login_pwd:pwd},
								dataType	: 'json',
								success		: function(json) {
										var redirect = (MODULE == 'default/' ? BASE_URL : BASE_URL + MODULE);
										if (json['success']) location = redirect;
										else {
												$('.login-message').addClass('error').html('Login e/ou senha inválido(s).');
												$('input[name="login_uid"]').focus();
										}
								},
								error			: function(x, op, e) { $('body').html(ajaxException(x, op, e)); }
						});
						
						return false;
				});
		}