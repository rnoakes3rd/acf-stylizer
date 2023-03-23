/*! jQuery Easing v1.4.2 - http://gsgd.co.uk/sandbox/jquery/easing/ * Open source under the BSD License. * Copyright © 2008 George McGinley Smith * All rights reserved. * https://raw.github.com/gdsmith/jquery-easing/master/LICENSE */

(function (factory) {
	if (typeof define === "function" && define.amd) {
		define(['jquery'], function ($) {
			return factory($);
		});
	} else if (typeof module === "object" && typeof module.exports === "object") {
		exports = factory(require('jquery'));
	} else {
		factory(jQuery);
	}
})(function($){

if (typeof $.easing !== 'undefined') {
	$.easing.jswing = $.easing.swing;
}

var pow = Math.pow,
	sqrt = Math.sqrt,
	sin = Math.sin,
	cos = Math.cos,
	PI = Math.PI,
	c1 = 1.70158,
	c2 = c1 * 1.525,
	c3 = c1 + 1,
	c4 = ( 2 * PI ) / 3,
	c5 = ( 2 * PI ) / 4.5;

function bounceOut(x) {
	var n1 = 7.5625,
		d1 = 2.75;
	if ( x < 1/d1 ) {
		return n1*x*x;
	} else if ( x < 2/d1 ) {
		return n1*(x-=(1.5/d1))*x + 0.75;
	} else if ( x < 2.5/d1 ) {
		return n1*(x-=(2.25/d1))*x + 0.9375;
	} else {
		return n1*(x-=(2.625/d1))*x + 0.984375;
	}
}

$.extend( $.easing,
{
	def: 'easeOutQuad',
	swing: function (x) {
		return $.easing[$.easing.def](x);
	},
	easeInQuad: function (x) {
		return x * x;
	},
	easeOutQuad: function (x) {
		return 1 - ( 1 - x ) * ( 1 - x );
	},
	easeInOutQuad: function (x) {
		return x < 0.5 ?
			2 * x * x :
			1 - pow( -2 * x + 2, 2 ) / 2;
	},
	easeInCubic: function (x) {
		return x * x * x;
	},
	easeOutCubic: function (x) {
		return 1 - pow( 1 - x, 3 );
	},
	easeInOutCubic: function (x) {
		return x < 0.5 ?
			4 * x * x * x :
			1 - pow( -2 * x + 2, 3 ) / 2;
	},
	easeInQuart: function (x) {
		return x * x * x * x;
	},
	easeOutQuart: function (x) {
		return 1 - pow( 1 - x, 4 );
	},
	easeInOutQuart: function (x) {
		return x < 0.5 ?
			8 * x * x * x * x :
			1 - pow( -2 * x + 2, 4 ) / 2;
	},
	easeInQuint: function (x) {
		return x * x * x * x * x;
	},
	easeOutQuint: function (x) {
		return 1 - pow( 1 - x, 5 );
	},
	easeInOutQuint: function (x) {
		return x < 0.5 ?
			16 * x * x * x * x * x :
			1 - pow( -2 * x + 2, 5 ) / 2;
	},
	easeInSine: function (x) {
		return 1 - cos( x * PI/2 );
	},
	easeOutSine: function (x) {
		return sin( x * PI/2 );
	},
	easeInOutSine: function (x) {
		return -( cos( PI * x ) - 1 ) / 2;
	},
	easeInExpo: function (x) {
		return x === 0 ? 0 : pow( 2, 10 * x - 10 );
	},
	easeOutExpo: function (x) {
		return x === 1 ? 1 : 1 - pow( 2, -10 * x );
	},
	easeInOutExpo: function (x) {
		return x === 0 ? 0 : x === 1 ? 1 : x < 0.5 ?
			pow( 2, 20 * x - 10 ) / 2 :
			( 2 - pow( 2, -20 * x + 10 ) ) / 2;
	},
	easeInCirc: function (x) {
		return 1 - sqrt( 1 - pow( x, 2 ) );
	},
	easeOutCirc: function (x) {
		return sqrt( 1 - pow( x - 1, 2 ) );
	},
	easeInOutCirc: function (x) {
		return x < 0.5 ?
			( 1 - sqrt( 1 - pow( 2 * x, 2 ) ) ) / 2 :
			( sqrt( 1 - pow( -2 * x + 2, 2 ) ) + 1 ) / 2;
	},
	easeInElastic: function (x) {
		return x === 0 ? 0 : x === 1 ? 1 :
			-pow( 2, 10 * x - 10 ) * sin( ( x * 10 - 10.75 ) * c4 );
	},
	easeOutElastic: function (x) {
		return x === 0 ? 0 : x === 1 ? 1 :
			pow( 2, -10 * x ) * sin( ( x * 10 - 0.75 ) * c4 ) + 1;
	},
	easeInOutElastic: function (x) {
		return x === 0 ? 0 : x === 1 ? 1 : x < 0.5 ?
			-( pow( 2, 20 * x - 10 ) * sin( ( 20 * x - 11.125 ) * c5 )) / 2 :
			pow( 2, -20 * x + 10 ) * sin( ( 20 * x - 11.125 ) * c5 ) / 2 + 1;
	},
	easeInBack: function (x) {
		return c3 * x * x * x - c1 * x * x;
	},
	easeOutBack: function (x) {
		return 1 + c3 * pow( x - 1, 3 ) + c1 * pow( x - 1, 2 );
	},
	easeInOutBack: function (x) {
		return x < 0.5 ?
			( pow( 2 * x, 2 ) * ( ( c2 + 1 ) * 2 * x - c2 ) ) / 2 :
			( pow( 2 * x - 2, 2 ) *( ( c2 + 1 ) * ( x * 2 - 2 ) + c2 ) + 2 ) / 2;
	},
	easeInBounce: function (x) {
		return 1 - bounceOut( 1 - x );
	},
	easeOutBounce: bounceOut,
	easeInOutBounce: function (x) {
		return x < 0.5 ?
			( 1 - bounceOut( 1 - 2 * x ) ) / 2 :
			( 1 + bounceOut( 2 * x - 1 ) ) / 2;
	}
});

});


/*! Noatice v0.1.8 * https://noatice.com/ * Copyright (c) 2020-2022 Robert Noakes * License: GNU General Public License v3.0 */

 (function ($)
{
	'use strict';

	if (typeof $.noatice === 'undefined')
	{
		$.fn.extend(
		{
			"noatice_message": function (message)
			{
				this
				.each(function ()
				{
					$('<div class="noatice-message" />').html(message).appendTo($(this));
				});

						return this;
			}
		});

		var PLUGIN = $.noatice = $.noatice || {};

				$.extend(PLUGIN,
		{
			"body": $(document.body),

			"dismiss": 'noatice-dismiss',

			"queue": [],

			"ready": false,

			"running": false,

			"wrapper": $('<div id="noatifications" />'),

			"init": function ()
			{
				if (!PLUGIN.ready)
				{
					PLUGIN.ready = true;

							$(window)
					.on('resize', function ()
					{
						$('.noatification').find(':animated').stop(true, true);
					});

							if (PLUGIN.body.hasClass(OPTIONS.rtl_class))
					{
						PLUGIN.wrapper.addClass('noatifications-rtl');
					}

							METHODS.enter();
					METHODS.tooltips();
				}
			}
		});


				var ADD = PLUGIN.add = PLUGIN.add || {};



								$.extend(ADD,

				{


					"base": function (options)

					{

						if (!Array.isArray(options))

						{

							options = [options];

						}



										$.each(options, function (index, value)

						{

							if ($.isPlainObject(value))

							{

								PLUGIN.queue.push($.extend({}, OPTIONS.defaults, value));

							}

						});



										if (!PLUGIN.running)

						{

							METHODS.enter();

						}

					},




					"general": function (css_class, message, options_or_dismissable)

					{

						var options = ($.isPlainObject(options_or_dismissable))

						? options_or_dismissable

						: {"dismissable": options_or_dismissable};



										ADD.base($.extend(options,

						{

							"css_class": (css_class === '')

							? 'noatice-general'

							: css_class,



											"message": message

						}));

					},




					"error": function (message, options_or_dismissable)

					{

						ADD.general('noatice-error', message, options_or_dismissable);

					},




					"info": function (message, options_or_dismissable)

					{

						ADD.general('noatice-info', message, options_or_dismissable);

					},




					"success": function (message, options_or_dismissable)

					{

						ADD.general('noatice-success', message, options_or_dismissable);

					},




					"warning": function (message, options_or_dismissable)

					{

						ADD.general('noatice-warning', message, options_or_dismissable);

					}

				});



		var METHODS = PLUGIN.methods = PLUGIN.methods || {};

				$.extend(METHODS,
		{
			"enter": function ()
			{
				if
				(
					PLUGIN.ready
					&&
					PLUGIN.queue.length > 0
				)
				{
					PLUGIN.running = true;

							if (PLUGIN.wrapper.closest(document.documentElement).length === 0)
					{
						PLUGIN.wrapper.appendTo(PLUGIN.body);
					}

							var options = PLUGIN.queue.shift();

							var noatice = (options.id)
					? $('#' + options.id)
					: '';

							if (noatice.length === 0)
					{
						noatice = $('<div class="noatice" />').attr('id', options.id).addClass(options.css_class);

								var inner = $('<div class="noatice-inner" />').css('width', PLUGIN.wrapper.width()).noatice_message(options.message).appendTo(noatice);

								if (options.dismissable)
						{
							noatice.addClass('noatice-dismissable');

									var dismiss = $('<div class="noatice-dismiss" />').appendTo(inner)
							.on('click', function ()
							{
								var existing = $(this).closest('.noatice-inner').css('width', PLUGIN.wrapper.width()).closest('.noatice').stop(true, true).css('z-index', '0');

																var exit_complete = function ()
								{
									var exiting = $(this);

																		if (exiting.hasClass('noatice-exited'))
									{
										exiting.remove();

												if (PLUGIN.wrapper.children().length === 0)
										{
											PLUGIN.wrapper.detach();
										}
									}
									else
									{
										exiting.addClass('noatice-exited');
									}
								};

																existing
								.animate(
								{
									"margin-top": '-' + existing.height() + 'px'
								},
								{
									"complete": exit_complete,
									"duration": options.duration.down,
									"easing": options.easing.down,
									"queue": false
								})
								.animate(
								{
									"margin-left": '100%'
								},
								{
									"complete": exit_complete,
									"duration": options.duration.exit,
									"easing": options.easing.exit,
									"queue": false
								});
							});

									if
							(
								typeof options.dismissable === 'number'
								&&
								options.dismissable > 0
							)
							{
								noatice
								.on(PLUGIN.dismiss, function ()
								{
									var current = $(this);
									var timeout = current.data(PLUGIN.dismiss);

											if (timeout)
									{
										clearTimeout(timeout);
									}

											current
									.data(PLUGIN.dismiss, setTimeout(function ()
									{
										dismiss.triggerHandler('click');
									},
									options.dismissable));
								})
								.triggerHandler(PLUGIN.dismiss);
							}
						}

								var enter_complete = function ()
						{
							METHODS.set_widths($(this));
							METHODS.enter();
						};

								if
						(
							typeof options.delay === 'number'
							&&
							options.delay > 0
						)
						{
							METHODS.enter();

									enter_complete = function ()
							{
								METHODS.set_widths($(this));
							};
						}
						else
						{
							options.delay = 0;
						}

								setTimeout(function ()
						{
							noatice.prependTo(PLUGIN.wrapper)
							.animate(
							{
								"margin-left": '0px'
							},
							{
								"complete": enter_complete,
								"duration": options.duration.enter,
								"easing": options.easing.enter,
								"queue": false
							});
						},
						options.delay);
					}
					else
					{
						noatice.triggerHandler(PLUGIN.dismiss);
						METHODS.enter();
					}
				}
				else
				{
					PLUGIN.running = false;
				}
			},

			"set_widths": function (noatice)
			{
				noatice.children().css('width', '');
			},

			"tooltips": function (elements)
			{
				elements = elements || $('.noatice-tooltip[title], [data-noatice-tooltip]');

						if (elements.length > 0)
				{
					elements.filter('.noatice-tooltip[title]')
					.each(function ()
					{
						var current = $(this);
						current.data('noatice-tooltip', current.attr('title')).removeAttr('title');
					});

							elements
					.on('focus mouseenter', function ()
					{
						var focused = $(this),
						tooltip = focused.data('noatice-sibling');

								if (!tooltip)
						{
							tooltip = $('<div class="noatice" />').data('noatice-sibling', focused).append($('<span class="noatice-arrow" />')).noatice_message(focused.data('noatice-tooltip'))
							.on('noatice-position', function ()
							{
								var positioning = $(this).css('width', ''),
								tooltip_width = positioning.width(),
								sibling = positioning.data('noatice-sibling'),
								offset = sibling.offset(),
								width = sibling.outerWidth();

										positioning
								.css(
								{
									"left": (offset.left - ((tooltip_width - width) / 2)) + 'px',
									"top": (offset.top - positioning.innerHeight() - 9) + 'px',
									"width": (tooltip_width + 1) + 'px'
								});
							});

									focused.data('noatice-sibling', tooltip);

														if (focused.is('[data-noatice-class]'))
							{
								tooltip.addClass(focused.data('noatice-class'));
							}
						}

								if (tooltip.closest(document.documentElement).length === 0)
						{
							tooltip.appendTo(PLUGIN.body);
						}

								tooltip.stop(true).triggerHandler('noatice-position');
						tooltip.fadeIn('fast');
					})
					.on('blur mouseleave', function ()
					{
						var blurred = $(this);

								var sibling = (blurred.is(':focus'))
						? false
						: blurred.data('noatice-sibling');

								if (sibling)
						{
							sibling.stop(true)
							.fadeOut('fast', function ()
							{
								$(this).detach();
							});
						}
					});
				}
			}
		});

		var OPTIONS = PLUGIN.options = PLUGIN.options || {};

				$.extend(OPTIONS,
		{
			"defaults":
			{
				"css_class": '',

				"delay": 0,

				"dismissable": 5000,

				"duration":
				{
					"down": 400,

					"enter": 600,

					"exit": 200
				},

				"easing":
				{
					"down": 'easeOutBounce',

					"enter": 'easeOutElastic',

					"exit": 'easeOutQuad'
				},

				"id": '',

				"message": ''
			},

			"rtl_class": 'rtl'
		});

				PLUGIN.init();
	}
})(jQuery);
