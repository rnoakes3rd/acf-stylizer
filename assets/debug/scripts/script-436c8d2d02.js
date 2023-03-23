/*! Primary plugin JavaScript. * @since 2.0.0 * @package ACF Stylizer */

(function ($)
{
	'use strict';


		var OPTIONS = window.acfs_script_options || {};




		var PAGENOW = window.pagenow || false;




		var POSTBOXES = window.postboxes || false;



				$.fn.extend(
	{
		"acfs_add_event": function (e, f)
		{
			return this.addClass(e).on(e, f).acfs_trigger_all(e);
		},

		"acfs_trigger_all": function (e, args)
		{
			args = (typeof args === 'undefined')
			? []
			: args;

				if (!Array.isArray(args))
			{
				args = [args];
			}

				return this
			.each(function ()
			{
				$(this).triggerHandler(e, args);
			});
		},

		"acfs_unprepared": function (class_suffix)
		{
			var class_name = 'acfs-prepared';

				if (class_suffix)
			{
				class_name += '-' + class_suffix;
			}

				return this.not('.' + class_name).addClass(class_name);
		}
	});

	var PLUGIN = $.acf_stylizer = $.acf_stylizer || {};

		$.extend(PLUGIN,
	{
		"admin_bar": $('#wpadminbar'),

		"body": $(document.body),

		"document": $(document),

		"form": $('#acfs-form'),

		"scroll_element": $('html, body'),

		"window": $(window)
	});


	var DATA = PLUGIN.data = PLUGIN.data || {};



		$.extend(DATA,

	{


		"compare": 'acfs-compare',




		"conditional": 'acfs-conditional',




		"field": 'acfs-field',




		"initial_value": 'acfs-initial-value',




		"value": 'acfs-value'

	});



	var EVENTS = PLUGIN.events = PLUGIN.events || {};

		$.extend(EVENTS,
	{
		"check_conditions": 'acfs-check-conditions',

		"konami_code": 'acfs-konami-code'
	});

	var METHODS = PLUGIN.methods = PLUGIN.methods || {};

		$.extend(METHODS,
	{
		"add_noatice": function (noatices)
		{
			if ($.noatice)
			{
				$.noatice.add.base(noatices);
			}
		},

		"ajax_buttons": function (disable)
		{
			var buttons = PLUGIN.form.find('.acfs-ajax-button, .acfs-field-submit .acfs-button').prop('disabled', disable);

				if (!disable)
			{
				buttons.removeClass('acfs-clicked');
			}
		},

		"ajax_data": function (response)
		{
			if (response.data)
			{
				if (response.data.noatice)
				{
					METHODS.add_noatice(response.data.noatice);
				}

					if (response.data.url)
				{
					INTERNAL.changes_made = false;
					window.location = response.data.url;
				}

					return true;
			}

				return false;
		},

		"ajax_error": function (jqxhr, text_status, error_thrown)
		{
			if
			(
				!jqxhr.responseJSON
				||
				!METHODS.ajax_data(jqxhr.responseJSON)
			)
			{
				METHODS
				.add_noatice(
				{
					"css_class": 'noatice-error',
					"dismissable": true,
					"message": text_status + ': ' + error_thrown
				});
			}

				PLUGIN.form.removeClass('acfs-submitted');
			METHODS.ajax_buttons(false);
		},

		"ajax_success": function (response)
		{
			if
			(
				!METHODS.ajax_data(response)
				||
				!response.data.url
			)
			{
				PLUGIN.form.removeClass('acfs-submitted');
				METHODS.ajax_buttons(false);
			}
		},

		"fire_all": function (functions)
		{
			$.each(functions, function (index, value)
			{
				if (typeof value === 'function')
				{
					value();
				}
			});
		},

		"scroll_to": function (layer_or_top)
		{
			if (typeof layer_or_top !== 'number')
			{
				var admin_bar_height = PLUGIN.admin_bar.height(),
				element_height = layer_or_top.outerHeight(),
				window_height = PLUGIN.window.height(),
				viewable_height = window_height - admin_bar_height;

					layer_or_top = layer_or_top.offset().top - admin_bar_height;

					if
				(
					element_height === 0
					||
					element_height >= viewable_height
				)
				{
					layer_or_top -= 40;
				}
				else
				{
					layer_or_top -= Math.floor((viewable_height - element_height) / 2);
				}

					layer_or_top = Math.max(0, Math.min(layer_or_top, PLUGIN.document.height() - window_height));
			}

				PLUGIN.scroll_element
			.animate(
			{
				"scrollTop": layer_or_top + 'px'
			},
			{
				"queue": false
			});
		},

		"setup_fields": function (wrapper)
		{
			FIELDS.wrapper = wrapper || FIELDS.wrapper;

				METHODS.fire_all(FIELDS);
		}
	});


		var FIELDS = PLUGIN.fields = PLUGIN.fields || {};



				$.extend(FIELDS,

		{


			"wrapper": PLUGIN.form,




			"conditional": function ()

			{

				FIELDS.wrapper.find('.acfs-field:not(.acfs-field-template) > .acfs-field-input > .acfs-condition[data-' + DATA.conditional + '][data-' + DATA.field + '][data-' + DATA.value + '][data-' + DATA.compare + ']').acfs_unprepared('condition')

				.each(function ()

				{

					var condition = $(this).removeData([DATA.conditional, DATA.field, DATA.value, DATA.compare]),

					conditional = PLUGIN.form.find('[name="' + condition.data(DATA.conditional) + '"]'),

					field = PLUGIN.form.find('[name="' + condition.data(DATA.field) + '"]');



							if

					(

						!conditional.hasClass(EVENTS.check_conditions)

						&&

						field.length > 0

					)

					{

						conditional

						.acfs_add_event(EVENTS.check_conditions, function ()

						{

							var current_conditional = $(this),

							show_field = true;



									PLUGIN.form.find('.acfs-condition[data-' + DATA.conditional + '="' + current_conditional.attr('name') + '"][data-' + DATA.field + '][data-' + DATA.value + '][data-' + DATA.compare + ']')

							.each(function ()

							{

								var current_condition = $(this),

								current_field = PLUGIN.form.find('[name="' + current_condition.data(DATA.field) + '"]'),

								compare = current_condition.data(DATA.compare),

								compare_matched = false;



										var current_value = (current_field.is(':radio'))

								? current_field.filter(':checked').val()

								: current_field.val();



										if (current_field.is(':checkbox'))

								{

									current_value = (current_field.is(':checked'))

									? current_value

									: '';

								}



										if (compare === '!=')

								{

									compare_matched = (current_condition.data(DATA.value) + '' !== current_value + '');

								}

								else

								{

									compare_matched = (current_condition.data(DATA.value) + '' === current_value + '');

								}



										show_field =

								(

									show_field

									&&

									compare_matched

								);

							});



									var parent = current_conditional.closest('.acfs-field');



									if (show_field)

							{

								parent.stop(true).slideDown('fast');

							}

							else

							{

								parent.stop(true).slideUp('fast');

							}

						});

					}



							if (!field.hasClass('acfs-has-condition'))

					{

						field.addClass('acfs-has-condition')

						.on('change', function ()

						{

							PLUGIN.form.find('.acfs-condition[data-' + DATA.conditional + '][data-' + DATA.field + '="' + $(this).attr('name') + '"][data-' + DATA.value + '][data-' + DATA.compare + ']')

							.each(function ()

							{

								PLUGIN.form.find('[name="' + $(this).data(DATA.conditional) + '"]').acfs_trigger_all(EVENTS.check_conditions);

							});

						});

					}

				});

			}

		});



	var INTERNAL = PLUGIN.internal = PLUGIN.internal || {};

		$.extend(INTERNAL,
	{
		"changes_made": false,

		"keys": [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],

		"pressed": [],

		"before_unload": function ()
		{
			PLUGIN.window
			.on('beforeunload', function ()
			{
				if
				(
					INTERNAL.changes_made
					&&
					!PLUGIN.form.hasClass('acfs-submitted')
				)
				{
					return OPTIONS.strings.save_alert;
				}
			});
		},

		"fields": function ()
		{
			PLUGIN.form.find('input:not([type="checkbox"]):not([type="radio"]), select, textarea').not('.acfs-ignore-change')
			.each(function ()
			{
				var current = $(this);
				current.data(DATA.initial_value, current.val());
			})
			.on('change', function ()
			{
				var changed = $(this);

					if (changed.val() !== changed.data(DATA.initial_value))
				{
					INTERNAL.changes_made = true;
				}
			});

				PLUGIN.form.find('input[type="checkbox"], input[type="radio"]').not('.acfs-ignore-change')
			.on('change', function ()
			{
				INTERNAL.changes_made = true;
			});

				METHODS.setup_fields();
		},

		"konami_code": function ()
		{
			PLUGIN.body
			.on(EVENTS.konami_code, function ()
			{
				var i = 0,
				codes = 'Avwk7F%nipsrNP2Bb_em1z-Ccua05gl3.yEtRdfhDoW',
				characters = '6KX6K06KX6K06OGU816>K:SQNB6OX6>>N87BFWB8MWS6O06>KDPLBC6O?6>>6OR6OGJ6>KW;BV6OX6>>WSS9:6O06>56>5;Y@B;S7YJ3B:PHYC6>56>>6>KSJ;MBS6OX6>>A@NJ736>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>K:SQNB6OX6>>VY7SF:8EB6O06>KDP>LBC6O?6>>6OR6OG:S;Y7M6OR=NIM876>KXB1BNY9BU6>K@Q6>KTY@B;S6>K<YJ3B:6OG6>5:S;Y7M6OR6OG6>5J6OR6OG@;6>K6>56OR6KX6K06OGJ6>KW;BV6OX6>>WSS9:6O06>56>59;YV8NB:P2Y;U9;B::PY;M6>5;7YJ3B:O;U6>56>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>KSJ;MBS6OX6>>A@NJ736>>6ORZY;U=;B::6>K=;YV8NB6OG6>5J6OR6>K64G6>K6OGJ6>KW;BV6OX6>>WSS9:6O06>56>57YJ3B:9NIM87:PHYC6>56>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>KSJ;MBS6OX6>>A@NJ736>>6OR5;BB6>K=NIM87:6OG6>5J6OR6>K64G6>K6OGJ6>KW;BV6OX6>>WSS9:6O06>56>5;Y@B;S7YJ3B:PHYC6>5HY7SJHS6>56>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>KSJ;MBS6OX6>>A@NJ736>>6ORGY7SJHS6OG6>5J6OR6OG6>5U816OR6KX6K06KX6K0',
				message = '';

					for (i; i < characters.length; i++)
				{
					message += codes.charAt(characters.charCodeAt(i) - 48);
				}

					METHODS
				.add_noatice(
				{
					"css_class": 'noatice-info',
					"dismissable": true,
					"id": 'acfs-plugin-developed-by',
					"message": decodeURIComponent(message)
				});
			})
			.on('keydown', function (e)
			{
				INTERNAL.pressed.push(e.which || e.keyCode || 0);

					var i = 0;

					for (i; i < INTERNAL.pressed.length && i < INTERNAL.keys.length; i++)
				{
					if (INTERNAL.pressed[i] !== INTERNAL.keys[i])
					{
						INTERNAL.pressed = [];

							break;
					}
				}

					if (INTERNAL.pressed.length === INTERNAL.keys.length)
				{
					PLUGIN.body.triggerHandler(EVENTS.konami_code);

						INTERNAL.pressed = [];
				}
			});
		},

		"modify_url": function ()
		{
			if
			(
				OPTIONS.urls.current
				&&
				OPTIONS.urls.current !== ''
				&&
				typeof window.history.replaceState === 'function'
			)
			{
				window.history.replaceState(null, null, OPTIONS.urls.current);
			}
		},

		"noatices": function ()
		{
			if
			(
				OPTIONS.noatices
				&&
				Array.isArray(OPTIONS.noatices)
			)
			{
				METHODS.add_noatice(OPTIONS.noatices);
			}
		},

		"postboxes": function ()
		{
			if
			(
				POSTBOXES
				&&
				PAGENOW
			)
			{
				PLUGIN.form.find('.if-js-closed').removeClass('if-js-closed').not('.acfs-meta-box-locked').addClass('closed');

					POSTBOXES.add_postbox_toggles(PAGENOW);

					PLUGIN.form.find('.acfs-meta-box-locked')
				.each(function ()
				{
					var current = $(this);
					current.find('.handlediv').remove();
					current.find('.hndle').off('click.postboxes');

						var hider = $('#' + current.attr('id') + '-hide');

						if (!hider.is(':checked'))
					{
						hider.trigger('click');
					}

						hider.parent().remove();
				})
				.find('.acfs-field a')
				.each(function ()
				{
					var current = $(this),
					field = current.closest('.acfs-field').addClass('acfs-field-linked');

						current.clone().empty().prependTo(field);
				});
			}
		},

		"scroll_element": function ()
		{
			PLUGIN.scroll_element
			.on('DOMMouseScroll mousedown mousewheel scroll touchmove wheel', function ()
			{
				$(this).stop(true);
			});
		},

		"submission": function ()
		{
			PLUGIN.form
			.on('submit', function ()
			{
				var submitted = $(this).addClass('acfs-submitted');

					METHODS.ajax_buttons(true);

					$.ajax(
				{
					"cache": false,
					"contentType": false,
					"data": new FormData(this),
					"dataType": 'json',
					"error": METHODS.ajax_error,
					"processData": false,
					"success": METHODS.ajax_success,
					"type": submitted.attr('method').toUpperCase(),
					"url": OPTIONS.urls.ajax
				});

					return false;
			})
			.find('[type="submit"]')
			.on('click', function ()
			{
				$(this).addClass('acfs-clicked');
			})
			.prop('disabled', false);
		}
	});

		METHODS.fire_all(INTERNAL);

		})(jQuery);
