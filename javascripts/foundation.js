/*
 Theme Name: Ally
 Theme URI: http://www.axisphilly.com
 Description: Custom theme for AxisPhilly
 Author: Casey Thomas, Jeff Frankl
 Author URI: caseypthomas.org, jfrankl.org
 Version: 0.9.0
*/
/*!
 * jQuery Cookie Plugin v1.3
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function ($, document, undefined) {

  var pluses = /\+/g;

  function raw(s) {
    return s;
  }

  function decoded(s) {
    return decodeURIComponent(s.replace(pluses, ' '));
  }

  var config = $.cookie = function (key, value, options) {

    // write
    if (value !== undefined) {
      options = $.extend({}, config.defaults, options);

      if (value === null) {
        options.expires = -1;
      }

      if (typeof options.expires === 'number') {
        var days = options.expires, t = options.expires = new Date();
        t.setDate(t.getDate() + days);
      }

      value = config.json ? JSON.stringify(value) : String(value);

      return (document.cookie = [
        encodeURIComponent(key), '=', config.raw ? value : encodeURIComponent(value),
        options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
        options.path    ? '; path=' + options.path : '',
        options.domain  ? '; domain=' + options.domain : '',
        options.secure  ? '; secure' : ''
      ].join(''));
    }

    // read
    var decode = config.raw ? raw : decoded;
    var cookies = document.cookie.split('; ');
    for (var i = 0, l = cookies.length; i < l; i++) {
      var parts = cookies[i].split('=');
      if (decode(parts.shift()) === key) {
        var cookie = decode(parts.join('='));
        return config.json ? JSON.parse(cookie) : cookie;
      }
    }

    return null;
  };

  config.defaults = {};

  $.removeCookie = function (key, options) {
    if ($.cookie(key) !== null) {
      $.cookie(key, null, options);
      return true;
    }
    return false;
  };

})(jQuery, document);
// jquery.event.move
//
// 1.3.1
//
// Stephen Band
//
// Triggers 'movestart', 'move' and 'moveend' events after
// mousemoves following a mousedown cross a distance threshold,
// similar to the native 'dragstart', 'drag' and 'dragend' events.
// Move events are throttled to animation frames. Move event objects
// have the properties:
//
// pageX:
// pageY:   Page coordinates of pointer.
// startX:
// startY:  Page coordinates of pointer at movestart.
// distX:
// distY:  Distance the pointer has moved since movestart.
// deltaX:
// deltaY:  Distance the finger has moved since last event.
// velocityX:
// velocityY:  Average velocity over last few events.


(function (module) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], module);
	} else {
		// Browser globals
		module(jQuery);
	}
})(function(jQuery, undefined){

	var // Number of pixels a pressed pointer travels before movestart
	    // event is fired.
	    threshold = 6,
	
	    add = jQuery.event.add,
	
	    remove = jQuery.event.remove,

	    // Just sugar, so we can have arguments in the same order as
	    // add and remove.
	    trigger = function(node, type, data) {
	    	jQuery.event.trigger(type, data, node);
	    },

	    // Shim for requestAnimationFrame, falling back to timer. See:
	    // see http://paulirish.com/2011/requestanimationframe-for-smart-animating/
	    requestFrame = (function(){
	    	return (
	    		window.requestAnimationFrame ||
	    		window.webkitRequestAnimationFrame ||
	    		window.mozRequestAnimationFrame ||
	    		window.oRequestAnimationFrame ||
	    		window.msRequestAnimationFrame ||
	    		function(fn, element){
	    			return window.setTimeout(function(){
	    				fn();
	    			}, 25);
	    		}
	    	);
	    })(),
	    
	    ignoreTags = {
	    	textarea: true,
	    	input: true,
	    	select: true,
	    	button: true
	    },
	    
	    mouseevents = {
	    	move: 'mousemove',
	    	cancel: 'mouseup dragstart',
	    	end: 'mouseup'
	    },
	    
	    touchevents = {
	    	move: 'touchmove',
	    	cancel: 'touchend',
	    	end: 'touchend'
	    };


	// Constructors
	
	function Timer(fn){
		var callback = fn,
				active = false,
				running = false;
		
		function trigger(time) {
			if (active){
				callback();
				requestFrame(trigger);
				running = true;
				active = false;
			}
			else {
				running = false;
			}
		}
		
		this.kick = function(fn) {
			active = true;
			if (!running) { trigger(); }
		};
		
		this.end = function(fn) {
			var cb = callback;
			
			if (!fn) { return; }
			
			// If the timer is not running, simply call the end callback.
			if (!running) {
				fn();
			}
			// If the timer is running, and has been kicked lately, then
			// queue up the current callback and the end callback, otherwise
			// just the end callback.
			else {
				callback = active ?
					function(){ cb(); fn(); } : 
					fn ;
				
				active = true;
			}
		};
	}


	// Functions
	
	function returnTrue() {
		return true;
	}
	
	function returnFalse() {
		return false;
	}
	
	function preventDefault(e) {
		e.preventDefault();
	}
	
	function preventIgnoreTags(e) {
		// Don't prevent interaction with form elements.
		if (ignoreTags[ e.target.tagName.toLowerCase() ]) { return; }
		
		e.preventDefault();
	}

	function isLeftButton(e) {
		// Ignore mousedowns on any button other than the left (or primary)
		// mouse button, or when a modifier key is pressed.
		return (e.which === 1 && !e.ctrlKey && !e.altKey);
	}

	function identifiedTouch(touchList, id) {
		var i, l;

		if (touchList.identifiedTouch) {
			return touchList.identifiedTouch(id);
		}
		
		// touchList.identifiedTouch() does not exist in
		// webkit yet… we must do the search ourselves...
		
		i = -1;
		l = touchList.length;
		
		while (++i < l) {
			if (touchList[i].identifier === id) {
				return touchList[i];
			}
		}
	}

	function changedTouch(e, event) {
		var touch = identifiedTouch(e.changedTouches, event.identifier);

		// This isn't the touch you're looking for.
		if (!touch) { return; }

		// Chrome Android (at least) includes touches that have not
		// changed in e.changedTouches. That's a bit annoying. Check
		// that this touch has changed.
		if (touch.pageX === event.pageX && touch.pageY === event.pageY) { return; }

		return touch;
	}


	// Handlers that decide when the first movestart is triggered
	
	function mousedown(e){
		var data;

		if (!isLeftButton(e)) { return; }

		data = {
			target: e.target,
			startX: e.pageX,
			startY: e.pageY,
			timeStamp: e.timeStamp
		};

		add(document, mouseevents.move, mousemove, data);
		add(document, mouseevents.cancel, mouseend, data);
	}

	function mousemove(e){
		var data = e.data;

		checkThreshold(e, data, e, removeMouse);
	}

	function mouseend(e) {
		removeMouse();
	}

	function removeMouse() {
		remove(document, mouseevents.move, mousemove);
		remove(document, mouseevents.cancel, removeMouse);
	}

	function touchstart(e) {
		var touch, template;

		// Don't get in the way of interaction with form elements.
		if (ignoreTags[ e.target.tagName.toLowerCase() ]) { return; }

		touch = e.changedTouches[0];
		
		// iOS live updates the touch objects whereas Android gives us copies.
		// That means we can't trust the touchstart object to stay the same,
		// so we must copy the data. This object acts as a template for
		// movestart, move and moveend event objects.
		template = {
			target: touch.target,
			startX: touch.pageX,
			startY: touch.pageY,
			timeStamp: e.timeStamp,
			identifier: touch.identifier
		};

		// Use the touch identifier as a namespace, so that we can later
		// remove handlers pertaining only to this touch.
		add(document, touchevents.move + '.' + touch.identifier, touchmove, template);
		add(document, touchevents.cancel + '.' + touch.identifier, touchend, template);
	}

	function touchmove(e){
		var data = e.data,
		    touch = changedTouch(e, data);

		if (!touch) { return; }

		checkThreshold(e, data, touch, removeTouch);
	}

	function touchend(e) {
		var template = e.data,
		    touch = identifiedTouch(e.changedTouches, template.identifier);

		if (!touch) { return; }

		removeTouch(template.identifier);
	}

	function removeTouch(identifier) {
		remove(document, '.' + identifier, touchmove);
		remove(document, '.' + identifier, touchend);
	}


	// Logic for deciding when to trigger a movestart.

	function checkThreshold(e, template, touch, fn) {
		var distX = touch.pageX - template.startX,
		    distY = touch.pageY - template.startY;

		// Do nothing if the threshold has not been crossed.
		if ((distX * distX) + (distY * distY) < (threshold * threshold)) { return; }

		triggerStart(e, template, touch, distX, distY, fn);
	}

	function handled() {
		// this._handled should return false once, and after return true.
		this._handled = returnTrue;
		return false;
	}

	function flagAsHandled(e) {
		e._handled();
	}

	function triggerStart(e, template, touch, distX, distY, fn) {
		var node = template.target,
		    touches, time;

		touches = e.targetTouches;
		time = e.timeStamp - template.timeStamp;

		// Create a movestart object with some special properties that
		// are passed only to the movestart handlers.
		template.type = 'movestart';
		template.distX = distX;
		template.distY = distY;
		template.deltaX = distX;
		template.deltaY = distY;
		template.pageX = touch.pageX;
		template.pageY = touch.pageY;
		template.velocityX = distX / time;
		template.velocityY = distY / time;
		template.targetTouches = touches;
		template.finger = touches ?
			touches.length :
			1 ;

		// The _handled method is fired to tell the default movestart
		// handler that one of the move events is bound.
		template._handled = handled;
			
		// Pass the touchmove event so it can be prevented if or when
		// movestart is handled.
		template._preventTouchmoveDefault = function() {
			e.preventDefault();
		};

		// Trigger the movestart event.
		trigger(template.target, template);

		// Unbind handlers that tracked the touch or mouse up till now.
		fn(template.identifier);
	}


	// Handlers that control what happens following a movestart

	function activeMousemove(e) {
		var event = e.data.event,
		    timer = e.data.timer;

		updateEvent(event, e, e.timeStamp, timer);
	}

	function activeMouseend(e) {
		var event = e.data.event,
		    timer = e.data.timer;
		
		removeActiveMouse();

		endEvent(event, timer, function() {
			// Unbind the click suppressor, waiting until after mouseup
			// has been handled.
			setTimeout(function(){
				remove(event.target, 'click', returnFalse);
			}, 0);
		});
	}

	function removeActiveMouse(event) {
		remove(document, mouseevents.move, activeMousemove);
		remove(document, mouseevents.end, activeMouseend);
	}

	function activeTouchmove(e) {
		var event = e.data.event,
		    timer = e.data.timer,
		    touch = changedTouch(e, event);

		if (!touch) { return; }

		// Stop the interface from gesturing
		e.preventDefault();

		event.targetTouches = e.targetTouches;
		updateEvent(event, touch, e.timeStamp, timer);
	}

	function activeTouchend(e) {
		var event = e.data.event,
		    timer = e.data.timer,
		    touch = identifiedTouch(e.changedTouches, event.identifier);

		// This isn't the touch you're looking for.
		if (!touch) { return; }

		removeActiveTouch(event);
		endEvent(event, timer);
	}

	function removeActiveTouch(event) {
		remove(document, '.' + event.identifier, activeTouchmove);
		remove(document, '.' + event.identifier, activeTouchend);
	}


	// Logic for triggering move and moveend events

	function updateEvent(event, touch, timeStamp, timer) {
		var time = timeStamp - event.timeStamp;

		event.type = 'move';
		event.distX =  touch.pageX - event.startX;
		event.distY =  touch.pageY - event.startY;
		event.deltaX = touch.pageX - event.pageX;
		event.deltaY = touch.pageY - event.pageY;
		
		// Average the velocity of the last few events using a decay
		// curve to even out spurious jumps in values.
		event.velocityX = 0.3 * event.velocityX + 0.7 * event.deltaX / time;
		event.velocityY = 0.3 * event.velocityY + 0.7 * event.deltaY / time;
		event.pageX =  touch.pageX;
		event.pageY =  touch.pageY;

		timer.kick();
	}

	function endEvent(event, timer, fn) {
		timer.end(function(){
			event.type = 'moveend';

			trigger(event.target, event);
			
			return fn && fn();
		});
	}


	// jQuery special event definition

	function setup(data, namespaces, eventHandle) {
		// Stop the node from being dragged
		//add(this, 'dragstart.move drag.move', preventDefault);
		
		// Prevent text selection and touch interface scrolling
		//add(this, 'mousedown.move', preventIgnoreTags);
		
		// Tell movestart default handler that we've handled this
		add(this, 'movestart.move', flagAsHandled);

		// Don't bind to the DOM. For speed.
		return true;
	}
	
	function teardown(namespaces) {
		remove(this, 'dragstart drag', preventDefault);
		remove(this, 'mousedown touchstart', preventIgnoreTags);
		remove(this, 'movestart', flagAsHandled);
		
		// Don't bind to the DOM. For speed.
		return true;
	}
	
	function addMethod(handleObj) {
		// We're not interested in preventing defaults for handlers that
		// come from internal move or moveend bindings
		if (handleObj.namespace === "move" || handleObj.namespace === "moveend") {
			return;
		}
		
		// Stop the node from being dragged
		add(this, 'dragstart.' + handleObj.guid + ' drag.' + handleObj.guid, preventDefault, undefined, handleObj.selector);
		
		// Prevent text selection and touch interface scrolling
		add(this, 'mousedown.' + handleObj.guid, preventIgnoreTags, undefined, handleObj.selector);
	}
	
	function removeMethod(handleObj) {
		if (handleObj.namespace === "move" || handleObj.namespace === "moveend") {
			return;
		}
		
		remove(this, 'dragstart.' + handleObj.guid + ' drag.' + handleObj.guid);
		remove(this, 'mousedown.' + handleObj.guid);
	}
	
	jQuery.event.special.movestart = {
		setup: setup,
		teardown: teardown,
		add: addMethod,
		remove: removeMethod,

		_default: function(e) {
			var template, data;
			
			// If no move events were bound to any ancestors of this
			// target, high tail it out of here.
			if (!e._handled()) { return; }

			template = {
				target: e.target,
				startX: e.startX,
				startY: e.startY,
				pageX: e.pageX,
				pageY: e.pageY,
				distX: e.distX,
				distY: e.distY,
				deltaX: e.deltaX,
				deltaY: e.deltaY,
				velocityX: e.velocityX,
				velocityY: e.velocityY,
				timeStamp: e.timeStamp,
				identifier: e.identifier,
				targetTouches: e.targetTouches,
				finger: e.finger
			};

			data = {
				event: template,
				timer: new Timer(function(time){
					trigger(e.target, template);
				})
			};
			
			if (e.identifier === undefined) {
				// We're dealing with a mouse
				// Stop clicks from propagating during a move
				add(e.target, 'click', returnFalse);
				add(document, mouseevents.move, activeMousemove, data);
				add(document, mouseevents.end, activeMouseend, data);
			}
			else {
				// We're dealing with a touch. Stop touchmove doing
				// anything defaulty.
				e._preventTouchmoveDefault();
				add(document, touchevents.move + '.' + e.identifier, activeTouchmove, data);
				add(document, touchevents.end + '.' + e.identifier, activeTouchend, data);
			}
		}
	};

	jQuery.event.special.move = {
		setup: function() {
			// Bind a noop to movestart. Why? It's the movestart
			// setup that decides whether other move events are fired.
			add(this, 'movestart.move', jQuery.noop);
		},
		
		teardown: function() {
			remove(this, 'movestart.move', jQuery.noop);
		}
	};
	
	jQuery.event.special.moveend = {
		setup: function() {
			// Bind a noop to movestart. Why? It's the movestart
			// setup that decides whether other move events are fired.
			add(this, 'movestart.moveend', jQuery.noop);
		},
		
		teardown: function() {
			remove(this, 'movestart.moveend', jQuery.noop);
		}
	};

	add(document, 'mousedown.move', mousedown);
	add(document, 'touchstart.move', touchstart);

	// Make jQuery copy touch event properties over to the jQuery event
	// object, if they are not already listed. But only do the ones we
	// really need. IE7/8 do not have Array#indexOf(), but nor do they
	// have touch events, so let's assume we can ignore them.
	if (typeof Array.prototype.indexOf === 'function') {
		(function(jQuery, undefined){
			var props = ["changedTouches", "targetTouches"],
			    l = props.length;
			
			while (l--) {
				if (jQuery.event.props.indexOf(props[l]) === -1) {
					jQuery.event.props.push(props[l]);
				}
			}
		})(jQuery);
	};
});
// jQuery.event.swipe
// 0.5
// Stephen Band

// Dependencies
// jQuery.event.move 1.2

// One of swipeleft, swiperight, swipeup or swipedown is triggered on
// moveend, when the move has covered a threshold ratio of the dimension
// of the target node, or has gone really fast. Threshold and velocity
// sensitivity changed with:
//
// jQuery.event.special.swipe.settings.threshold
// jQuery.event.special.swipe.settings.sensitivity

(function (module) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], module);
	} else {
		// Browser globals
		module(jQuery);
	}
})(function(jQuery, undefined){
	var add = jQuery.event.add,
	   
	    remove = jQuery.event.remove,

	    // Just sugar, so we can have arguments in the same order as
	    // add and remove.
	    trigger = function(node, type, data) {
	    	jQuery.event.trigger(type, data, node);
	    },

	    settings = {
	    	// Ratio of distance over target finger must travel to be
	    	// considered a swipe.
	    	threshold: 0.4,
	    	// Faster fingers can travel shorter distances to be considered
	    	// swipes. 'sensitivity' controls how much. Bigger is shorter.
	    	sensitivity: 6
	    };

	function moveend(e) {
		var w, h, event;

		w = e.target.offsetWidth;
		h = e.target.offsetHeight;

		// Copy over some useful properties from the move event
		event = {
			distX: e.distX,
			distY: e.distY,
			velocityX: e.velocityX,
			velocityY: e.velocityY,
			finger: e.finger
		};

		// Find out which of the four directions was swiped
		if (e.distX > e.distY) {
			if (e.distX > -e.distY) {
				if (e.distX/w > settings.threshold || e.velocityX * e.distX/w * settings.sensitivity > 1) {
					event.type = 'swiperight';
					trigger(e.currentTarget, event);
				}
			}
			else {
				if (-e.distY/h > settings.threshold || e.velocityY * e.distY/w * settings.sensitivity > 1) {
					event.type = 'swipeup';
					trigger(e.currentTarget, event);
				}
			}
		}
		else {
			if (e.distX > -e.distY) {
				if (e.distY/h > settings.threshold || e.velocityY * e.distY/w * settings.sensitivity > 1) {
					event.type = 'swipedown';
					trigger(e.currentTarget, event);
				}
			}
			else {
				if (-e.distX/w > settings.threshold || e.velocityX * e.distX/w * settings.sensitivity > 1) {
					event.type = 'swipeleft';
					trigger(e.currentTarget, event);
				}
			}
		}
	}

	function getData(node) {
		var data = jQuery.data(node, 'event_swipe');
		
		if (!data) {
			data = { count: 0 };
			jQuery.data(node, 'event_swipe', data);
		}
		
		return data;
	}

	jQuery.event.special.swipe =
	jQuery.event.special.swipeleft =
	jQuery.event.special.swiperight =
	jQuery.event.special.swipeup =
	jQuery.event.special.swipedown = {
		setup: function( data, namespaces, eventHandle ) {
			var data = getData(this);

			// If another swipe event is already setup, don't setup again.
			if (data.count++ > 0) { return; }

			add(this, 'moveend', moveend);

			return true;
		},

		teardown: function() {
			var data = getData(this);

			// If another swipe event is still setup, don't teardown.
			if (--data.count > 0) { return; }

			remove(this, 'moveend', moveend);

			return true;
		},

		settings: settings
	};
});
;(function ($, window, undefined){
  'use strict';

  $.fn.foundationAccordion = function (options) {

    // DRY up the logic used to determine if the event logic should execute.
    var hasHover = function(accordion) {
      return accordion.hasClass('hover') && !Modernizr.touch
    };

    $(document).on('mouseenter', '.accordion li', function () {
        var p = $(this).parent();

        if (hasHover(p)) {
          var flyout = $(this).children('.content').first();

          $('.content', p).not(flyout).hide().parent('li').removeClass('active');
          flyout.show(0, function () {
            flyout.parent('li').addClass('active');
          });
        }
      }
    );

    $(document).on('click.fndtn', '.accordion li .title', function () {
        var li = $(this).closest('li'),
            p = li.parent();

        if(!hasHover(p)) {
          var flyout = li.children('.content').first();

          if (li.hasClass('active')) {
            p.find('li').removeClass('active').end().find('.content').hide();
          } else {
            $('.content', p).not(flyout).hide().parent('li').removeClass('active');
            flyout.show(0, function () {
              flyout.parent('li').addClass('active');
            });
          }
        }
      }
     );

  };

})( jQuery, this );


;(function ($, window, undefined) {
  'use strict';
  
  $.fn.foundationAlerts = function (options) {
    var settings = $.extend({
      callback: $.noop
    }, options);
    
    $(document).on("click", ".alert-box a.close", function (e) {
      e.preventDefault();
      $(this).closest(".alert-box").fadeOut(function () {
        $(this).remove();
        // Do something else after the alert closes
        settings.callback();
      });
    });
    
  };

})(jQuery, this);

;(function ($, window, undefined) {
  'use strict';

  $.fn.foundationButtons = function (options) {
    var $doc = $(document),
      config = $.extend({
        dropdownAsToggle:false,
        activeClass:'active'
      }, options),

    // close all dropdowns except for the dropdown passed
      closeDropdowns = function (dropdown) {
        // alert(dropdown.html());
        $('.button.dropdown').find('ul').not(dropdown).removeClass('show-dropdown');
      },
    // reset all toggle states except for the button passed
      resetToggles = function (button) {
        // alert(button.html());
        var buttons = $('.button.dropdown').not(button);
        buttons.add($('> span.' + config.activeClass, buttons)).removeClass(config.activeClass);
      };

    // Prevent event propagation on disabled buttons
    $doc.on('click.fndtn', '.button.disabled', function (e) {
      e.preventDefault();
    });

    $('.button.dropdown > ul', this).addClass('no-hover');

    // reset other active states
    $doc.on('click.fndtn', '.button.dropdown:not(.split), .button.dropdown.split span', function (e) {
      var $el = $(this),
        button = $el.closest('.button.dropdown'),
        dropdown = $('> ul', button);

        // If the click is registered on an actual link or on button element then do not preventDefault which stops the browser from following the link
        if (["A", "BUTTON"].indexOf(e.target.nodeName) == -1){
          e.preventDefault();
        }

      // close other dropdowns
      setTimeout(function () {
        closeDropdowns(config.dropdownAsToggle ? '' : dropdown);
        dropdown.toggleClass('show-dropdown');

        if (config.dropdownAsToggle) {
          resetToggles(button);
          $el.toggleClass(config.activeClass);
        }
      }, 0);
    });

    // close all dropdowns and deactivate all buttons
    $doc.on('click.fndtn', 'body, html', function (e) {
      if (undefined == e.originalEvent) { return; }
      // check original target instead of stopping event propagation to play nice with other events
      if (!$(e.originalEvent.target).is('.button.dropdown:not(.split), .button.dropdown.split span')) {
        closeDropdowns();
        if (config.dropdownAsToggle) {
          resetToggles();
        }
      }
    });

    // Positioning the Flyout List
    var normalButtonHeight  = $('.button.dropdown:not(.large):not(.small):not(.tiny):visible', this).outerHeight() - 1,
        largeButtonHeight   = $('.button.large.dropdown:visible', this).outerHeight() - 1,
        smallButtonHeight   = $('.button.small.dropdown:visible', this).outerHeight() - 1,
        tinyButtonHeight    = $('.button.tiny.dropdown:visible', this).outerHeight() - 1;

    $('.button.dropdown:not(.large):not(.small):not(.tiny) > ul', this).css('top', normalButtonHeight);
    $('.button.dropdown.large > ul', this).css('top', largeButtonHeight);
    $('.button.dropdown.small > ul', this).css('top', smallButtonHeight);
    $('.button.dropdown.tiny > ul', this).css('top', tinyButtonHeight);

    $('.button.dropdown.up:not(.large):not(.small):not(.tiny) > ul', this).css('top', 'auto').css('bottom', normalButtonHeight - 2);
    $('.button.dropdown.up.large > ul', this).css('top', 'auto').css('bottom', largeButtonHeight - 2);
    $('.button.dropdown.up.small > ul', this).css('top', 'auto').css('bottom', smallButtonHeight - 2);
    $('.button.dropdown.up.tiny > ul', this).css('top', 'auto').css('bottom', tinyButtonHeight - 2);

  };

})( jQuery, this );

/*
 * jQuery Foundation Clearing 1.2.1
 * http://foundation.zurb.com
 * Copyright 2012, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

/*jslint unparam: true, browser: true, indent: 2 */

;(function ($, window, document, undefined) {
  'use strict';

  var defaults = {
        templates : {
          viewing : '<a href="#" class="clearing-close">&times;</a>' +
            '<div class="visible-img" style="display: none"><img src="#">' +
            '<p class="clearing-caption"></p><a href="#" class="clearing-main-left"></a>' +
            '<a href="#" class="clearing-main-right"></a></div>'
        },

        // comma delimited list of selectors that, on click, will close clearing, 
        // add 'div.clearing-blackout, div.visible-img' to close on background click
        close_selectors : 'a.clearing-close',

        // event initializers and locks
        initialized : false,
        locked : false
      },

      cl = {
        init : function (options, extendMethods) {
          return this.find('ul[data-clearing]').each(function () {
            var doc = $(document),
                $el = $(this),
                options = options || {},
                extendMethods = extendMethods || {},
                settings = $el.data('fndtn.clearing.settings');

            if (!settings) {
              options.$parent = $el.parent();

              $el.data('fndtn.clearing.settings', $.extend({}, defaults, options));

              cl.assemble($el.find('li'));

              if (!defaults.initialized) {
                cl.events($el);
                if (Modernizr.touch) cl.swipe_events();
              }

            }
          });
        },

        events : function (el) {
          var settings = el.data('fndtn.clearing.settings');

          $(document)
            .on('click.fndtn.clearing', 'ul[data-clearing] li', function (e, current, target) {
              var current = current || $(this),
                  target = target || current,
                  settings = current.parent().data('fndtn.clearing.settings');

              e.preventDefault();

              if (!settings) {
                current.parent().foundationClearing();
              }

              // set current and target to the clicked li if not otherwise defined.
              cl.open($(e.target), current, target);
              cl.update_paddles(target);
            })

            .on('click.fndtn.clearing', '.clearing-main-right', function (e) { cl.nav(e, 'next') })
            .on('click.fndtn.clearing', '.clearing-main-left', function (e) { cl.nav(e, 'prev') })
            .on('click.fndtn.clearing', settings.close_selectors, this.close)
            .on('keydown.fndtn.clearing', this.keydown);

          $(window).on('resize.fndtn.clearing', this.resize);

          defaults.initialized = true;
        },

        swipe_events : function () {
          $(document)
            .bind('swipeleft', 'ul[data-clearing]', function (e) { cl.nav(e, 'next') })
            .bind('swiperight', 'ul[data-clearing]', function (e) { cl.nav(e, 'prev') })
            .bind('movestart', 'ul[data-clearing]', function (e) {
              if ((e.distX > e.distY && e.distX < -e.distY) ||
                  (e.distX < e.distY && e.distX > -e.distY)) {
                e.preventDefault();
              }
            });
        },

        assemble : function ($li) {
          var $el = $li.parent(),
              settings = $el.data('fndtn.clearing.settings'),
              grid = $el.detach(),
              data = {
                grid: '<div class="carousel">' + this.outerHTML(grid[0]) + '</div>',
                viewing: settings.templates.viewing
              },
              wrapper = '<div class="clearing-assembled"><div>' + data.viewing + data.grid + '</div></div>';

          return settings.$parent.append(wrapper);
        },

        open : function ($image, current, target) {
          var root = target.closest('.clearing-assembled'),
              container = root.find('div:first'),
              visible_image = container.find('.visible-img'),
              image = visible_image.find('img').not($image);

          if (!cl.locked()) {

            // set the image to the selected thumbnail
            image.attr('src', this.load($image));

            image.loaded(function () {
              // toggle the gallery if not visible
              root.addClass('clearing-blackout');
              container.addClass('clearing-container');
              this.caption(visible_image.find('.clearing-caption'), $image);
              visible_image.show();
              this.fix_height(target);
              this.center(image);

              // shift the thumbnails if necessary
              this.shift(current, target, function () {
                target.siblings().removeClass('visible');
                target.addClass('visible');
              });
            }.bind(this));
          }
        },

        close : function (e) {
          e.preventDefault();

          var root = (function (target) {
                if (/blackout/.test(target.selector)) {
                  return target;
                } else {
                  return target.closest('.clearing-blackout');
                }
              }($(this))), container, visible_image;

          if (this === e.target && root) {
            container = root.find('div:first'),
            visible_image = container.find('.visible-img');

            defaults.prev_index = 0;

            root.find('ul[data-clearing]').attr('style', '')
            root.removeClass('clearing-blackout');
            container.removeClass('clearing-container');
            visible_image.hide();
          }

          return false;
        },

        keydown : function (e) {
          var clearing = $('.clearing-blackout').find('ul[data-clearing]');

          if (e.which === 39) cl.go(clearing, 'next');
          if (e.which === 37) cl.go(clearing, 'prev');
          if (e.which === 27) $('a.clearing-close').trigger('click');
        },

        nav : function (e, direction) {
          var clearing = $('.clearing-blackout').find('ul[data-clearing]');

          e.preventDefault();
          this.go(clearing, direction);
        },

        resize : function () {
          var image = $('.clearing-blackout .visible-img').find('img');

          if (image.length > 0) {
            cl.center(image);
          }
        },

        fix_height : function (target) {
          var lis = target.siblings();

          lis.each(function () {
              var li = $(this),
                  image = li.find('img');

              if (li.height() > image.outerHeight()) {
                li.addClass('fix-height');
              }
            })
            .closest('ul').width(lis.length * 100 + '%');
        },

        update_paddles : function (target) {
          var visible_image = target.closest('.carousel').siblings('.visible-img');

          if (target.next().length > 0) {
            visible_image.find('.clearing-main-right').removeClass('disabled');
          } else {
            visible_image.find('.clearing-main-right').addClass('disabled');
          }

          if (target.prev().length > 0) {
            visible_image.find('.clearing-main-left').removeClass('disabled');
          } else {
            visible_image.find('.clearing-main-left').addClass('disabled');
          }
        },

        load : function ($image) {
          var href = $image.parent().attr('href');

          this.preload($image);

          if (href) return href;
          return $image.attr('src');
        },

        preload : function ($image) {
          this.img($image.closest('li').next());
          this.img($image.closest('li').prev());
        },

        img : function (img) {
          if (img.length > 0) {
            var new_img = new Image(),
                new_a = img.find('a');

            if (new_a.length > 0) {
              new_img.src = new_a.attr('href');
            } else {
              new_img.src = img.find('img').attr('src');
            }
          }
        },

        caption : function (container, $image) {
          var caption = $image.data('caption');

          if (caption) {
            container.text(caption).show();
          } else {
            container.text('').hide();
          }
        },

        go : function ($ul, direction) {
          var current = $ul.find('.visible'),
              target = current[direction]();

          if (target.length > 0) {
            target.find('img').trigger('click', [current, target]);
          }
        },

        shift : function (current, target, callback) {
          var clearing = target.parent(),
              old_index = defaults.prev_index,
              direction = this.direction(clearing, current, target),
              left = parseInt(clearing.css('left'), 10),
              width = target.outerWidth(),
              skip_shift;

          // we use jQuery animate instead of CSS transitions because we
          // need a callback to unlock the next animation
          if (target.index() !== old_index && !/skip/.test(direction)){
            if (/left/.test(direction)) {
              this.lock();
              clearing.animate({left : left + width}, 300, this.unlock);
            } else if (/right/.test(direction)) {
              this.lock();
              clearing.animate({left : left - width}, 300, this.unlock);
            }
          } else if (/skip/.test(direction)) {

            // the target image is not adjacent to the current image, so
            // do we scroll right or not
            skip_shift = target.index() - defaults.up_count;
            this.lock();

            if (skip_shift > 0) {
              clearing.animate({left : -(skip_shift * width)}, 300, this.unlock);
            } else {
              clearing.animate({left : 0}, 300, this.unlock);
            }
          }

          callback();
        },

        lock : function () {
          defaults.locked = true;
        },

        unlock : function () {
          defaults.locked = false;
        },

        locked : function () {
          return defaults.locked;
        },

        direction : function ($el, current, target) {
          var lis = $el.find('li'),
              li_width = lis.outerWidth() + (lis.outerWidth() / 4),
              up_count = Math.floor($('.clearing-container').outerWidth() / li_width) - 1,
              target_index = lis.index(target),
              response;

          defaults.up_count = up_count;

          if (this.adjacent(defaults.prev_index, target_index)) {
            if ((target_index > up_count) && target_index > defaults.prev_index) {
              response = 'right';
            } else if ((target_index > up_count - 1) && target_index <= defaults.prev_index) {
              response = 'left';
            } else {
              response = false;
            }
          } else {
            response = 'skip';
          }

          defaults.prev_index = target_index;

          return response;
        },

        adjacent : function (current_index, target_index) {
          for (var i = target_index + 1; i >= target_index - 1; i--) {
            if (i === current_index) return true;
          }
          return false;
        },

        center : function (target) {
          target.css({
            marginLeft : -(target.outerWidth() / 2),
            marginTop : -(target.outerHeight() / 2)
          });
        },

        outerHTML : function (el) {
          // support FireFox < 11
          return el.outerHTML || new XMLSerializer().serializeToString(el);
        }

      };

  $.fn.foundationClearing = function (method) {
    if (cl[method]) {
      return cl[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return cl.init.apply(this, arguments);
    } else {
      $.error('Method ' +  method + ' does not exist on jQuery.foundationClearing');
    }
  };

  // jquery.imageready.js
  // @weblinc, @jsantell, (c) 2012

  (function( $ ) {
    $.fn.loaded = function ( callback, userSettings ) {
      var
        options = $.extend( {}, $.fn.loaded.defaults, userSettings ),
        $images = this.find( 'img' ).add( this.filter( 'img' ) ),
        unloadedImages = $images.length;

      function loaded () {
        unloadedImages -= 1;
        !unloadedImages && callback();
      }

      function bindLoad () {
        this.one( 'load', loaded );
        if ( $.browser.msie ) {
          var
            src   = this.attr( 'src' ),
            param = src.match( /\?/ ) ? '&' : '?';
          param  += options.cachePrefix + '=' + ( new Date() ).getTime();
          this.attr( 'src', src + param );
        }
      }

      return $images.each(function () {
        var $this = $( this );
        if ( !$this.attr( 'src' ) ) {
          loaded();
          return;
        }
        this.complete || this.readyState === 4 ?
          loaded() :
          bindLoad.call( $this );
      });
    };

    $.fn.loaded.defaults = {
      cachePrefix: 'random'
    };

  }(jQuery));

}(jQuery, this, this.document));

/*
 * jQuery Custom Forms Plugin 1.0
 * www.ZURB.com
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

(function( $ ){

  /**
   * Helper object used to quickly adjust all hidden parent element's, display and visibility properties.
   * This is currently used for the custom drop downs. When the dropdowns are contained within a reveal modal
   * we cannot accurately determine the list-item elements width property, since the modal's display property is set
   * to 'none'.
   *
   * This object will help us work around that problem.
   *
   * NOTE: This could also be plugin.
   *
   * @function hiddenFix
   */
  var hiddenFix = function() {

    return {
      /**
       * Sets all hidden parent elements and self to visibile.
       *
       * @method adjust
       * @param {jQuery Object} $child
       */

      // We'll use this to temporarily store style properties.
      tmp : [],

      // We'll use this to set hidden parent elements.
      hidden : null,

      adjust : function( $child ) {
        // Internal reference.
        var _self = this;

        // Set all hidden parent elements, including this element.
        _self.hidden = $child.parents().andSelf().filter( ":hidden" );

        // Loop through all hidden elements.
        _self.hidden.each( function() {

          // Cache the element.
          var $elem = $( this );

          // Store the style attribute.
          // Undefined if element doesn't have a style attribute.
          _self.tmp.push( $elem.attr( 'style' ) );

          // Set the element's display property to block,
          // but ensure it's visibility is hidden.
          $elem.css( { 'visibility' : 'hidden', 'display' : 'block' } );
        });

      }, // end adjust

      /**
       * Resets the elements previous state.
       *
       * @method reset
       */
      reset : function() {
        // Internal reference.
        var _self = this;
        // Loop through our hidden element collection.
        _self.hidden.each( function( i ) {
          // Cache this element.
          var $elem = $( this ),
              _tmp = _self.tmp[ i ]; // Get the stored 'style' value for this element.

          // If the stored value is undefined.
          if( _tmp === undefined )
            // Remove the style attribute.
            $elem.removeAttr( 'style' );
          else
            // Otherwise, reset the element style attribute.
            $elem.attr( 'style', _tmp );

        });
        // Reset the tmp array.
        _self.tmp = [];
        // Reset the hidden elements variable.
        _self.hidden = null;

      } // end reset

    }; // end return

  };

  jQuery.foundation = jQuery.foundation || {};
  jQuery.foundation.customForms = jQuery.foundation.customForms || {};

  $.foundation.customForms.appendCustomMarkup = function ( options ) {

    var defaults = {
      disable_class: "no-custom"
    };

    options = $.extend( defaults, options );

    function appendCustomMarkup(idx, sel) {
      var $this = $(sel).hide(),
          type  = $this.attr('type'),
          $span = $this.next('span.custom.' + type);

      if ($span.length === 0) {
        $span = $('<span class="custom ' + type + '"></span>').insertAfter($this);
      }

      $span.toggleClass('checked', $this.is(':checked'));
      $span.toggleClass('disabled', $this.is(':disabled'));
    }

    function appendCustomSelect(idx, sel) {
      var hiddenFixObj = hiddenFix();
          //
          // jQueryify the <select> element and cache it.
          //
      var $this = $( sel ),
          //
          // Find the custom drop down element.
          //
          $customSelect = $this.next( 'div.custom.dropdown' ),
          //
          // Find the custom select element within the custom drop down.
          //
          $customList = $customSelect.find( 'ul' ),
          //
          // Find the custom a.current element.
          //
          $selectCurrent = $customSelect.find( ".current" ),
          //
          // Find the custom a.selector element (the drop-down icon).
          //
          $selector = $customSelect.find( ".selector" ),
          //
          // Get the <options> from the <select> element.
          //
          $options = $this.find( 'option' ),
          //
          // Filter down the selected options
          //
          $selectedOption = $options.filter( ':selected' ),
          //
          // Initial max width.
          //
          maxWidth = 0,
          //
          // We'll use this variable to create the <li> elements for our custom select.
          //
          liHtml = '',
          //
          // We'll use this to cache the created <li> elements within our custom select.
          //
          $listItems
      ;
      var $currentSelect = false;
      //
      // Should we not create a custom list?
      //
      if ( $this.hasClass( options.disable_class ) ) return;

      //
      // Did we not create a custom select element yet?
      //
      if ( $customSelect.length === 0 ) {
        //
        // Let's create our custom select element!
        //

            //
            // Determine what select size to use.
            //
        var customSelectSize = $this.hasClass( 'small' )   ? 'small'   :
                               $this.hasClass( 'medium' )  ? 'medium'  :
                               $this.hasClass( 'large' )   ? 'large'   :
                               $this.hasClass( 'expand' )  ? 'expand'  : ''
        ;
        //
        // Build our custom list.
        //
        $customSelect = $('<div class="' + ['custom', 'dropdown', customSelectSize ].join( ' ' ) + '"><a href="#" class="selector"></a><ul /></div>');
        //
        // Grab the selector element
        //
        $selector = $customSelect.find( ".selector" );
        //
        // Grab the unordered list element from the custom list.
        //
        $customList = $customSelect.find( "ul" );
        //
        // Build our <li> elements.
        //
        liHtml = $options.map( function() { return "<li>" + $( this ).html() + "</li>"; } ).get().join( '' );
        //
        // Append our <li> elements to the custom list (<ul>).
        //
        $customList.append( liHtml );
        //
        // Insert the the currently selected list item before all other elements.
        // Then, find the element and assign it to $currentSelect.
        //

        $currentSelect = $customSelect.prepend( '<a href="#" class="current">' + $selectedOption.html() + '</a>' ).find( ".current" );
        //
        // Add the custom select element after the <select> element.
        //
        $this.after( $customSelect )
        //
        //then hide the <select> element.
        //
        .hide();

      } else {
        //
        // Create our list item <li> elements.
        //
        liHtml = $options.map( function() { return "<li>" + $( this ).html() + "</li>"; } ).get().join( '' );
        //
        // Refresh the ul with options from the select in case the supplied markup doesn't match.
        // Clear what's currently in the <ul> element.
        //
        $customList.html( '' )
        //
        // Populate the list item <li> elements.
        //
        .append( liHtml );

      } // endif $customSelect.length === 0

      //
      // Determine whether or not the custom select element should be disabled.
      //
      $customSelect.toggleClass( 'disabled', $this.is( ':disabled' ) );
      //
      // Cache our List item elements.
      //
      $listItems = $customList.find( 'li' );

      //
      // Determine which elements to select in our custom list.
      //
      $options.each( function ( index ) {

        if ( this.selected ) {
          //
          // Add the selected class to the current li element
          //
          $listItems.eq( index ).addClass( 'selected' );
          //
          // Update the current element with the option value.
          //
          if ($currentSelect) {
            $currentSelect.html( $( this ).html() );
          }

        }

      });

      //
      // Update the custom <ul> list width property.
      //
      $customList.css( 'width', 'auto' );
      //
      // Set the custom select width property.
      //
      $customSelect.css( 'width', 'auto' );

      //
      // If we're not specifying a predetermined form size.
      //
      if ( !$customSelect.is( '.small, .medium, .large, .expand' ) ) {

        // ------------------------------------------------------------------------------------
        // This is a work-around for when elements are contained within hidden parents.
        // For example, when custom-form elements are inside of a hidden reveal modal.
        //
        // We need to display the current custom list element as well as hidden parent elements
        // in order to properly calculate the list item element's width property.
        // -------------------------------------------------------------------------------------

        //
        // Show the drop down.
        // This should ensure that the list item's width values are properly calculated.
        //
        $customSelect.addClass( 'open' );
        //
        // Quickly, display all parent elements.
        // This should help us calcualate the width of the list item's within the drop down.
        //
        hiddenFixObj.adjust( $customList );
        //
        // Grab the largest list item width.
        //
        maxWidth = ( $listItems.outerWidth() > maxWidth ) ? $listItems.outerWidth() : maxWidth;
        //
        // Okay, now reset the parent elements.
        // This will hide them again.
        //
        hiddenFixObj.reset();
        //
        // Finally, hide the drop down.
        //
        $customSelect.removeClass( 'open' );
        //
        // Set the custom list width.
        //
        $customSelect.width( maxWidth + 18);
        //
        // Set the custom list element (<ul />) width.
        //
        $customList.width(  maxWidth + 16 );

      } // endif

    }

    $('form.custom input:radio[data-customforms!=disabled]').each(appendCustomMarkup);
    $('form.custom input:checkbox[data-customforms!=disabled]').each(appendCustomMarkup);
    $('form.custom select[data-customforms!=disabled]').each(appendCustomSelect);
  };

  var refreshCustomSelect = function($select) {
    var maxWidth = 0,
        $customSelect = $select.next();
    $options = $select.find('option');
    $customSelect.find('ul').html('');

    $options.each(function () {
      $li = $('<li>' + $(this).html() + '</li>');
      $customSelect.find('ul').append($li);
    });

    // re-populate
    $options.each(function (index) {
      if (this.selected) {
        $customSelect.find('li').eq(index).addClass('selected');
        $customSelect.find('.current').html($(this).html());
      }
    });

    // fix width
    $customSelect.removeAttr('style')
      .find('ul').removeAttr('style');
    $customSelect.find('li').each(function () {
      $customSelect.addClass('open');
      if ($(this).outerWidth() > maxWidth) {
        maxWidth = $(this).outerWidth();
      }
      $customSelect.removeClass('open');
    });
    $customSelect.css('width', maxWidth + 18 + 'px');
    $customSelect.find('ul').css('width', maxWidth + 16 + 'px');

  };

  var toggleCheckbox = function($element) {
    var $input = $element.prev(),
        input = $input[0];

    if (false === $input.is(':disabled')) {
        input.checked = ((input.checked) ? false : true);
        $element.toggleClass('checked');

        $input.trigger('change');
    }
  };

  var toggleRadio = function($element) {
    var $input = $element.prev(),
        $form = $input.closest('form.custom'),
        input = $input[0];

    if (false === $input.is(':disabled')) {
      $form.find('input:radio[name="' + $input.attr('name') + '"]').next().not($element).removeClass('checked');
      if ( !$element.hasClass('checked') ) {
        $element.toggleClass('checked');
      }
      input.checked = $element.hasClass('checked');

      $input.trigger('change');
    }
  };

  $(document).on('click', 'form.custom span.custom.checkbox', function (event) {
    event.preventDefault();
    event.stopPropagation();

    toggleCheckbox($(this));
  });

  $(document).on('click', 'form.custom span.custom.radio', function (event) {
    event.preventDefault();
    event.stopPropagation();

    toggleRadio($(this));
  });

  $(document).on('change', 'form.custom select[data-customforms!=disabled]', function (event) {
    refreshCustomSelect($(this));
  });

  $(document).on('click', 'form.custom label', function (event) {
    var $associatedElement = $('#' + $(this).attr('for') + '[data-customforms!=disabled]'),
        $customCheckbox,
        $customRadio;
    if ($associatedElement.length !== 0) {
      if ($associatedElement.attr('type') === 'checkbox') {
        event.preventDefault();
        $customCheckbox = $(this).find('span.custom.checkbox');
        //the checkbox might be outside after the label
        if ($customCheckbox.length == 0) {
            $customCheckbox = $(this).next('span.custom.checkbox');
        }
        //the checkbox might be outside before the label
        if ($customCheckbox.length == 0) {
            $customCheckbox = $(this).prev('span.custom.checkbox');
        }
        toggleCheckbox($customCheckbox);
      } else if ($associatedElement.attr('type') === 'radio') {
        event.preventDefault();
        $customRadio = $(this).find('span.custom.radio');
        //the radio might be outside after the label
        if ($customRadio.length == 0) {
            $customRadio = $(this).next('span.custom.radio');
        }
        //the radio might be outside before the label
        if ($customRadio.length == 0) {
            $customRadio = $(this).prev('span.custom.radio');
        }
        toggleRadio($customRadio);
      }
    }
  });

  $(document).on('click', 'form.custom div.custom.dropdown a.current, form.custom div.custom.dropdown a.selector', function (event) {
    var $this = $(this),
        $dropdown = $this.closest('div.custom.dropdown'),
        $select = $dropdown.prev();

    event.preventDefault();
    $('div.dropdown').removeClass('open');

    if (false === $select.is(':disabled')) {
        $dropdown.toggleClass('open');

        if ($dropdown.hasClass('open')) {
          $(document).bind('click.customdropdown', function (event) {
            $dropdown.removeClass('open');
            $(document).unbind('.customdropdown');
          });
        } else {
          $(document).unbind('.customdropdown');
        }
        return false;
    }
  });

  $(document).on('click', 'form.custom div.custom.dropdown li', function (event) {
    var $this = $(this),
        $customDropdown = $this.closest('div.custom.dropdown'),
        $select = $customDropdown.prev(),
        selectedIndex = 0;

    event.preventDefault();
    event.stopPropagation();
    $('div.dropdown').removeClass('open');

    $this
      .closest('ul')
      .find('li')
      .removeClass('selected');
    $this.addClass('selected');

    $customDropdown
      .removeClass('open')
      .find('a.current')
      .html($this.html());

    $this.closest('ul').find('li').each(function (index) {
      if ($this[0] == this) {
        selectedIndex = index;
      }

    });
    $select[0].selectedIndex = selectedIndex;

    $select.trigger('change');
  });


  $.fn.foundationCustomForms = $.foundation.customForms.appendCustomMarkup;

})( jQuery );

;(function ($, window, undefined) {
  'use strict';
  
  $.fn.foundationMediaQueryViewer = function (options) {
    var settings = $.extend(options,{toggleKey:77}), // Press 'M'
        $doc = $(document);

    $doc.on("keyup.mediaQueryViewer", ":input", function (e){
      if (e.which === settings.toggleKey) {
        e.stopPropagation();
      }
    });
    $doc.on("keyup.mediaQueryViewer", function (e) {
      var $mqViewer = $('#fqv');

      if (e.which === settings.toggleKey) { 
        if ($mqViewer.length > 0) {
          $mqViewer.remove();
        } else {
          $('body').prepend('<div id="fqv" style="position:fixed;top:4px;left:4px;z-index:999;color:#fff;"><p style="font-size:12px;background:rgba(0,0,0,0.75);padding:5px;margin-bottom:1px;line-height:1.2;"><span class="left">Media:</span> <span style="font-weight:bold;" class="show-for-xlarge">Extra Large</span><span style="font-weight:bold;" class="show-for-large">Large</span><span style="font-weight:bold;" class="show-for-medium">Medium</span><span style="font-weight:bold;" class="show-for-small">Small</span><span style="font-weight:bold;" class="show-for-landscape">Landscape</span><span style="font-weight:bold;" class="show-for-portrait">Portrait</span><span style="font-weight:bold;" class="show-for-touch">Touch</span></p></div>');
        }
      }
    });

  };

})(jQuery, this);
;(function ($, window, undefined) {
  'use strict';

  $.fn.foundationNavigation = function (options) {

    var lockNavBar = false;
    // Windows Phone, sadly, does not register touch events :(
    if (Modernizr.touch || navigator.userAgent.match(/Windows Phone/i)) {
      $(document).on('click.fndtn touchstart.fndtn', '.nav-bar a.flyout-toggle', function (e) {
        e.preventDefault();
        var flyout = $(this).siblings('.flyout').first();
        if (lockNavBar === false) {
          $('.nav-bar .flyout').not(flyout).slideUp(500);
          flyout.slideToggle(500, function () {
            lockNavBar = false;
          });
        }
        lockNavBar = true;
      });
      $('.nav-bar>li.has-flyout', this).addClass('is-touch');
    } else {
      $('.nav-bar>li.has-flyout', this).on('mouseenter mouseleave', function (e) {
        if (e.type == 'mouseenter') {
          $('.nav-bar').find('.flyout').hide();
          $(this).children('.flyout').show();
        }

        if (e.type == 'mouseleave') {
          var flyout = $(this).children('.flyout'),
              inputs = flyout.find('input'),
              hasFocus = function (inputs) {
                var focus;
                if (inputs.length > 0) {
                  inputs.each(function () {
                    if ($(this).is(":focus")) {
                      focus = true;
                    }
                  });
                  return focus;
                }

                return false;
              };

          if (!hasFocus(inputs)) {
            $(this).children('.flyout').hide();
          }
        }

      });
    }

  };

})( jQuery, this );

/*
 * jQuery Orbit Plugin 1.4.0
 * www.ZURB.com/playground
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/


(function ($) {
  'use strict';

  $.fn.findFirstImage = function () {
    return this.first()
            .find('img')
            .andSelf().filter('img')
            .first();
  };

  var ORBIT = {

    defaults: {
      animation: 'horizontal-push',     // fade, horizontal-slide, vertical-slide, horizontal-push, vertical-push
      animationSpeed: 600,              // how fast animations are
      timer: true,                      // display timer?
      advanceSpeed: 4000,               // if timer is enabled, time between transitions
      pauseOnHover: false,              // if you hover pauses the slider
      startClockOnMouseOut: false,      // if clock should start on MouseOut
      startClockOnMouseOutAfter: 1000,  // how long after MouseOut should the timer start again
      directionalNav: true,             // manual advancing directional navs
      directionalNavRightText: 'Right', // text of right directional element for accessibility
      directionalNavLeftText: 'Left',   // text of left directional element for accessibility
      captions: true,                   // do you want captions?
      captionAnimation: 'fade',         // fade, slideOpen, none
      captionAnimationSpeed: 600,       // if so how quickly should they animate in
      resetTimerOnClick: false,         // true resets the timer instead of pausing slideshow progress on manual navigation
      bullets: false,                   // true or false to activate the bullet navigation
      bulletThumbs: false,              // thumbnails for the bullets
      bulletThumbLocation: '',          // relative path to thumbnails from this file
      bulletThumbsHideOnSmall: true,	// hide thumbs on small devices
      afterSlideChange: $.noop,         // callback to execute after slide changes
      afterLoadComplete: $.noop,        // callback to execute after everything has been loaded
      fluid: true,
      centerBullets: true,              // center bullet nav with js, turn this off if you want to position the bullet nav manually
      singleCycle: false,               // cycles through orbit slides only once
      slideNumber: false,               // display slide numbers?
      stackOnSmall: false               // stack slides on small devices (i.e. phones)
    },

    activeSlide: 0,
    numberSlides: 0,
    orbitWidth: null,
    orbitHeight: null,
    locked: null,
    timerRunning: null,
    degrees: 0,
    wrapperHTML: '<div class="orbit-wrapper" />',
    timerHTML: '<div class="timer"><span class="mask"><span class="rotator"></span></span><span class="pause"></span></div>',
    captionHTML: '<div class="orbit-caption"></div>',
    directionalNavHTML: '<div class="slider-nav hide-for-small"><span class="right"></span><span class="left"></span></div>',
    bulletHTML: '<ul class="orbit-bullets"></ul>',
    slideNumberHTML: '<span class="orbit-slide-counter"></span>',

    init: function (element, options) {
      var $imageSlides,
          imagesLoadedCount = 0,
          self = this;

      // Bind functions to correct context
      this.clickTimer = $.proxy(this.clickTimer, this);
      this.addBullet = $.proxy(this.addBullet, this);
      this.resetAndUnlock = $.proxy(this.resetAndUnlock, this);
      this.stopClock = $.proxy(this.stopClock, this);
      this.startTimerAfterMouseLeave = $.proxy(this.startTimerAfterMouseLeave, this);
      this.clearClockMouseLeaveTimer = $.proxy(this.clearClockMouseLeaveTimer, this);
      this.rotateTimer = $.proxy(this.rotateTimer, this);

      this.options = $.extend({}, this.defaults, options);
      if (this.options.timer === 'false') this.options.timer = false;
      if (this.options.captions === 'false') this.options.captions = false;
      if (this.options.directionalNav === 'false') this.options.directionalNav = false;

      this.$element = $(element);
      this.$wrapper = this.$element.wrap(this.wrapperHTML).parent();
      this.$slides = this.$element.children('img, a, div, figure, li');

      this.$element.on('movestart', function(e) {
        // If the movestart is heading off in an upwards or downwards
        // direction, prevent it so that the browser scrolls normally.
        if ((e.distX > e.distY && e.distX < -e.distY) ||
            (e.distX < e.distY && e.distX > -e.distY)) {
          e.preventDefault();
        }
      });

      this.$element.bind('orbit.next', function () {
        self.shift('next');
      });

      this.$element.bind('orbit.prev', function () {
        self.shift('prev');
      });

      this.$element.bind('swipeleft', function () {
        $(this).trigger('orbit.next');
      });

      this.$element.bind('swiperight', function () {
        $(this).trigger('orbit.prev');
      });

      this.$element.bind('orbit.goto', function (event, index) {
        self.shift(index);
      });

      this.$element.bind('orbit.start', function (event, index) {
        self.startClock();
      });

      this.$element.bind('orbit.stop', function (event, index) {
        self.stopClock();
      });

      $imageSlides = this.$slides.filter('img');

      if ($imageSlides.length === 0) {
        this.loaded();
      } else {
        $imageSlides.bind('imageready', function () {
          imagesLoadedCount += 1;
          if (imagesLoadedCount === $imageSlides.length) {
            self.loaded();
          }
        });
      }
    },

    loaded: function () {
      this.$element
        .addClass('orbit')
        .css({width: '1px', height: '1px'});

      if (this.options.stackOnSmall) {
        this.$element.addClass('orbit-stack-on-small');
      }

      this.$slides.addClass('orbit-slide').css({"opacity" : 0});

      this.setDimentionsFromLargestSlide();
      this.updateOptionsIfOnlyOneSlide();
      this.setupFirstSlide();
      this.notifySlideChange();

      if (this.options.timer) {
        this.setupTimer();
        this.startClock();
      }

      if (this.options.captions) {
        this.setupCaptions();
      }

      if (this.options.directionalNav) {
        this.setupDirectionalNav();
      }

      if (this.options.bullets) {
        this.setupBulletNav();
        this.setActiveBullet();
      }

      this.options.afterLoadComplete.call(this);
      Holder.run();
    },

    currentSlide: function () {
      return this.$slides.eq(this.activeSlide);
    },

    notifySlideChange: function() {
      if (this.options.slideNumber) {
        var txt = (this.activeSlide+1) + ' of ' + this.$slides.length;
        this.$element.trigger("orbit.change", {slideIndex: this.activeSlide, slideCount: this.$slides.length});
        if (this.$counter === undefined) {
          var $counter = $(this.slideNumberHTML).html(txt);
          this.$counter = $counter;
          this.$wrapper.append(this.$counter);
        } else {
          this.$counter.html(txt);
        }
      }
    },

    setDimentionsFromLargestSlide: function () {
      //Collect all slides and set slider size of largest image
      var self = this,
          $fluidPlaceholder;

      self.$element.add(self.$wrapper).width(this.$slides.first().outerWidth());
      self.$element.add(self.$wrapper).height(this.$slides.first().height());
      self.orbitWidth = this.$slides.first().outerWidth();
      self.orbitHeight = this.$slides.first().height();
      $fluidPlaceholder = this.$slides.first().findFirstImage().clone();


      this.$slides.each(function () {
        var slide = $(this),
            slideWidth = slide.outerWidth(),
            slideHeight = slide.height();

        if (slideWidth > self.$element.outerWidth()) {
          self.$element.add(self.$wrapper).width(slideWidth);
          self.orbitWidth = self.$element.outerWidth();
        }
        if (slideHeight > self.$element.height()) {
          self.$element.add(self.$wrapper).height(slideHeight);
          self.orbitHeight = self.$element.height();
          $fluidPlaceholder = $(this).findFirstImage().clone();
        }
        self.numberSlides += 1;
      });

      if (this.options.fluid) {
        if (typeof this.options.fluid === "string") {
          // $fluidPlaceholder = $("<img>").attr("src", "http://placehold.it/" + this.options.fluid);
          $fluidPlaceholder = $("<img>").attr("data-src", "holder.js/" + this.options.fluid);
          //var inner = $("<div/>").css({"display":"inline-block", "width":"2px", "height":"2px"});
          //$fluidPlaceholder = $("<div/>").css({"float":"left"});
          //$fluidPlaceholder.wrapInner(inner);

          //$fluidPlaceholder = $("<div/>").css({"height":"1px", "width":"2px"});
          //$fluidPlaceholder = $("<div style='display:inline-block;width:2px;height:1px;'></div>");
        }

        self.$element.prepend($fluidPlaceholder);
        $fluidPlaceholder.addClass('fluid-placeholder');
        self.$element.add(self.$wrapper).css({width: 'inherit'});
        self.$element.add(self.$wrapper).css({height: 'inherit'});

        $(window).bind('resize', function () {
          self.orbitWidth = self.$element.outerWidth();
          self.orbitHeight = self.$element.height();
        });
      }
    },

    //Animation locking functions
    lock: function () {
      this.locked = true;
    },

    unlock: function () {
      this.locked = false;
    },

    updateOptionsIfOnlyOneSlide: function () {
      if(this.$slides.length === 1) {
        this.options.directionalNav = false;
        this.options.timer = false;
        this.options.bullets = false;
      }
    },

    setupFirstSlide: function () {
      //Set initial front photo z-index and fades it in
      var self = this;
      this.$slides.first()
        .css({"z-index" : 3, "opacity" : 1})
        .fadeIn(function() {
          //brings in all other slides IF css declares a display: none
          self.$slides.css({"display":"block"})
      });
    },

    startClock: function () {
      var self = this;

      if(!this.options.timer) {
        return false;
      }

      if (this.$timer.is(':hidden')) {
        this.clock = setInterval(function () {
          self.$element.trigger('orbit.next');
        }, this.options.advanceSpeed);
      } else {
        this.timerRunning = true;
        this.$pause.removeClass('active');
        this.clock = setInterval(this.rotateTimer, this.options.advanceSpeed / 180, false);
      }
    },

    rotateTimer: function (reset) {
      var degreeCSS = "rotate(" + this.degrees + "deg)";
      this.degrees += 2;
      this.$rotator.css({
        "-webkit-transform": degreeCSS,
        "-moz-transform": degreeCSS,
        "-o-transform": degreeCSS,
        "-ms-transform": degreeCSS
      });
      if (reset) {
        this.degrees = 0;
        this.$rotator.removeClass('move');
        this.$mask.removeClass('move');
      }
      if(this.degrees > 180) {
        this.$rotator.addClass('move');
        this.$mask.addClass('move');
      }
      if(this.degrees > 360) {
        this.$rotator.removeClass('move');
        this.$mask.removeClass('move');
        this.degrees = 0;
        this.$element.trigger('orbit.next');
      }
    },

    stopClock: function () {
      if (!this.options.timer) {
        return false;
      } else {
        this.timerRunning = false;
        clearInterval(this.clock);
        this.$pause.addClass('active');
      }
    },

    setupTimer: function () {
      this.$timer = $(this.timerHTML);
      this.$wrapper.append(this.$timer);

      this.$rotator = this.$timer.find('.rotator');
      this.$mask = this.$timer.find('.mask');
      this.$pause = this.$timer.find('.pause');

      this.$timer.click(this.clickTimer);

      if (this.options.startClockOnMouseOut) {
        this.$wrapper.mouseleave(this.startTimerAfterMouseLeave);
        this.$wrapper.mouseenter(this.clearClockMouseLeaveTimer);
      }

      if (this.options.pauseOnHover) {
        this.$wrapper.mouseenter(this.stopClock);
      }
    },

    startTimerAfterMouseLeave: function () {
      var self = this;

      this.outTimer = setTimeout(function() {
        if(!self.timerRunning){
          self.startClock();
        }
      }, this.options.startClockOnMouseOutAfter)
    },

    clearClockMouseLeaveTimer: function () {
      clearTimeout(this.outTimer);
    },

    clickTimer: function () {
      if(!this.timerRunning) {
          this.startClock();
      } else {
          this.stopClock();
      }
    },

    setupCaptions: function () {
      this.$caption = $(this.captionHTML);
      this.$wrapper.append(this.$caption);
      this.setCaption();
    },

    setCaption: function () {
      var captionLocation = this.currentSlide().attr('data-caption'),
          captionHTML;

      if (!this.options.captions) {
        return false;
      }

      //Set HTML for the caption if it exists
      if (captionLocation) {
        //if caption text is blank, don't show captions
        if ($.trim($(captionLocation).text()).length < 1){
          return false;
        }
        
        // if location selector starts with '#', remove it so we don't see id="#selector"
        if (captionLocation.charAt(0) == '#') {
            captionLocation = captionLocation.substring(1, captionLocation.length);
        }
        captionHTML = $('#' + captionLocation).html(); //get HTML from the matching HTML entity
        this.$caption
          .attr('id', captionLocation) // Add ID caption TODO why is the id being set?
          .html(captionHTML); // Change HTML in Caption
          //Animations for Caption entrances
        switch (this.options.captionAnimation) {
          case 'none':
            this.$caption.show();
            break;
          case 'fade':
            this.$caption.fadeIn(this.options.captionAnimationSpeed);
            break;
          case 'slideOpen':
            this.$caption.slideDown(this.options.captionAnimationSpeed);
            break;
        }
      } else {
        //Animations for Caption exits
        switch (this.options.captionAnimation) {
          case 'none':
            this.$caption.hide();
            break;
          case 'fade':
            this.$caption.fadeOut(this.options.captionAnimationSpeed);
            break;
          case 'slideOpen':
            this.$caption.slideUp(this.options.captionAnimationSpeed);
            break;
        }
      }
    },

    setupDirectionalNav: function () {
      var self = this,
          $directionalNav = $(this.directionalNavHTML);

      $directionalNav.find('.right').html(this.options.directionalNavRightText);
      $directionalNav.find('.left').html(this.options.directionalNavLeftText);

      this.$wrapper.append($directionalNav);

      this.$wrapper.find('.left').click(function () {
        self.stopClock();
        if (self.options.resetTimerOnClick) {
          self.rotateTimer(true);
          self.startClock();
        }
        self.$element.trigger('orbit.prev');
      });

      this.$wrapper.find('.right').click(function () {
        self.stopClock();
        if (self.options.resetTimerOnClick) {
          self.rotateTimer(true);
          self.startClock();
        }
        self.$element.trigger('orbit.next');
      });
    },

    setupBulletNav: function () {
      this.$bullets = $(this.bulletHTML);
      this.$wrapper.append(this.$bullets);
      this.$slides.each(this.addBullet);
      this.$element.addClass('with-bullets');
      if (this.options.centerBullets) this.$bullets.css('margin-left', -this.$bullets.outerWidth() / 2);
      if (this.options.bulletThumbsHideOnSmall) this.$bullets.addClass('hide-for-small');
    },

    addBullet: function (index, slide) {
      var position = index + 1,
          $li = $('<li>' + (position) + '</li>'),
          thumbName,
          self = this;

      if (this.options.bulletThumbs) {
        thumbName = $(slide).attr('data-thumb');
        if (thumbName) {
          $li
            .addClass('has-thumb')
            .css({background: "url(" + this.options.bulletThumbLocation + thumbName + ") no-repeat"});;
        }
      }
      this.$bullets.append($li);
      $li.data('index', index);
      $li.click(function () {
        self.stopClock();
        if (self.options.resetTimerOnClick) {
          self.rotateTimer(true);
          self.startClock();
        }
        self.$element.trigger('orbit.goto', [$li.data('index')])
      });
    },

    setActiveBullet: function () {
      if(!this.options.bullets) { return false; } else {
        this.$bullets.find('li')
          .removeClass('active')
          .eq(this.activeSlide)
          .addClass('active');
      }
    },

    resetAndUnlock: function () {
      this.$slides
        .eq(this.prevActiveSlide)
        .css({"z-index" : 1});
      this.unlock();
      this.options.afterSlideChange.call(this, this.$slides.eq(this.prevActiveSlide), this.$slides.eq(this.activeSlide));
    },

    shift: function (direction) {
      var slideDirection = direction;

      //remember previous activeSlide
      this.prevActiveSlide = this.activeSlide;

      //exit function if bullet clicked is same as the current image
      if (this.prevActiveSlide == slideDirection) { return false; }

      if (this.$slides.length == "1") { return false; }
      if (!this.locked) {
        this.lock();
        //deduce the proper activeImage
        if (direction == "next") {
          this.activeSlide++;
          if (this.activeSlide == this.numberSlides) {
              this.activeSlide = 0;
          }
        } else if (direction == "prev") {
          this.activeSlide--
          if (this.activeSlide < 0) {
            this.activeSlide = this.numberSlides - 1;
          }
        } else {
          this.activeSlide = direction;
          if (this.prevActiveSlide < this.activeSlide) {
            slideDirection = "next";
          } else if (this.prevActiveSlide > this.activeSlide) {
            slideDirection = "prev"
          }
        }

        //set to correct bullet
        this.setActiveBullet();
        this.notifySlideChange();

        //set previous slide z-index to one below what new activeSlide will be
        this.$slides
          .eq(this.prevActiveSlide)
          .css({"z-index" : 2});

        //fade
        if (this.options.animation == "fade") {
          this.$slides
            .eq(this.activeSlide)
            .css({"opacity" : 0, "z-index" : 3})
            .animate({"opacity" : 1}, this.options.animationSpeed, this.resetAndUnlock);
          this.$slides
              .eq(this.prevActiveSlide)
              .animate({"opacity":0}, this.options.animationSpeed);
        }

        //horizontal-slide
        if (this.options.animation == "horizontal-slide") {
          if (slideDirection == "next") {
            this.$slides
              .eq(this.activeSlide)
              .css({"left": this.orbitWidth, "z-index" : 3})
              .css("opacity", 1)
              .animate({"left" : 0}, this.options.animationSpeed, this.resetAndUnlock);
          }
          if (slideDirection == "prev") {
            this.$slides
              .eq(this.activeSlide)
              .css({"left": -this.orbitWidth, "z-index" : 3})
              .css("opacity", 1)
              .animate({"left" : 0}, this.options.animationSpeed, this.resetAndUnlock);
          }
          this.$slides
              .eq(this.prevActiveSlide)
              .css("opacity", 0);
        }

        //vertical-slide
        if (this.options.animation == "vertical-slide") {
          if (slideDirection == "prev") {
            this.$slides
              .eq(this.activeSlide)
              .css({"top": this.orbitHeight, "z-index" : 3})
              .css("opacity", 1)
              .animate({"top" : 0}, this.options.animationSpeed, this.resetAndUnlock);
            this.$slides
              .eq(this.prevActiveSlide)
              .css("opacity", 0);
          }
          if (slideDirection == "next") {
            this.$slides
              .eq(this.activeSlide)
              .css({"top": -this.orbitHeight, "z-index" : 3})
              .css("opacity", 1)
              .animate({"top" : 0}, this.options.animationSpeed, this.resetAndUnlock);
          }
          this.$slides
              .eq(this.prevActiveSlide)
              .css("opacity", 0);
        }

        //horizontal-push
        if (this.options.animation == "horizontal-push") {
          if (slideDirection == "next") {
            this.$slides
              .eq(this.activeSlide)
              .css({"left": this.orbitWidth, "z-index" : 3})
              .animate({"left" : 0, "opacity" : 1}, this.options.animationSpeed, this.resetAndUnlock);
            this.$slides
              .eq(this.prevActiveSlide)
              .animate({"left" : -this.orbitWidth}, this.options.animationSpeed, "", function(){
                $(this).css({"opacity" : 0});
              });
          }
          if (slideDirection == "prev") {
            this.$slides
              .eq(this.activeSlide)
              .css({"left": -this.orbitWidth, "z-index" : 3})
              .animate({"left" : 0, "opacity" : 1}, this.options.animationSpeed, this.resetAndUnlock);
            this.$slides
              .eq(this.prevActiveSlide)
              .animate({"left" : this.orbitWidth}, this.options.animationSpeed, "", function(){
                $(this).css({"opacity" : 0});
              });
          }
        }

        //vertical-push
        if (this.options.animation == "vertical-push") {
          if (slideDirection == "next") {
            this.$slides
              .eq(this.activeSlide)
              .css({top: -this.orbitHeight, "z-index" : 3})
              .css("opacity", 1)
              .animate({top : 0, "opacity":1}, this.options.animationSpeed, this.resetAndUnlock);
            this.$slides
              .eq(this.prevActiveSlide)
              .css("opacity", 0)
              .animate({top : this.orbitHeight}, this.options.animationSpeed, "");
          }
          if (slideDirection == "prev") {
            this.$slides
              .eq(this.activeSlide)
              .css({top: this.orbitHeight, "z-index" : 3})
              .css("opacity", 1)
              .animate({top : 0}, this.options.animationSpeed, this.resetAndUnlock);
            this.$slides
              .eq(this.prevActiveSlide)
              .css("opacity", 0)
              .animate({top : -this.orbitHeight}, this.options.animationSpeed);
          }
        }

        this.setCaption();
      }

      // if on last slide and singleCycle is true, don't loop through slides again
      // .length is zero based so must minus 1 to match activeSlide index
      if (this.activeSlide === this.$slides.length-1 && this.options.singleCycle) {
        this.stopClock();
      }
    }
  };

  $.fn.orbit = function (options) {
    return this.each(function () {
      var orbit = $.extend({}, ORBIT);
      orbit.init(this, options);
    });
  };

})(jQuery);

/*!
 * jQuery imageready Plugin
 * http://www.zurb.com/playground/
 *
 * Copyright 2011, ZURB
 * Released under the MIT License
 */
(function ($) {

  var options = {};

  $.event.special.imageready = {

    setup: function (data, namespaces, eventHandle) {
      options = data || options;
    },

    add: function (handleObj) {
      var $this = $(this),
          src;

      if ( this.nodeType === 1 && this.tagName.toLowerCase() === 'img' && this.src !== '' ) {
        if (options.forceLoad) {
          src = $this.attr('src');
          $this.attr('src', '');
          bindToLoad(this, handleObj.handler);
          $this.attr('src', src);
        } else if ( this.complete || this.readyState === 4 ) {
          handleObj.handler.apply(this, arguments);
        } else {
          bindToLoad(this, handleObj.handler);
        }
      }
    },

    teardown: function (namespaces) {
      $(this).unbind('.imageready');
    }
  };

  function bindToLoad(element, callback) {
    var $this = $(element);

    $this.bind('load.imageready', function () {
       callback.apply(element, arguments);
       $this.unbind('load.imageready');
     });
  }

}(jQuery));

/*

Holder - 1.3 - client side image placeholders
(c) 2012 Ivan Malopinsky / http://imsky.co

Provided under the Apache 2.0 License: http://www.apache.org/licenses/LICENSE-2.0
Commercial use requires attribution.

*/

var Holder = Holder || {};
(function (app, win) {

var preempted = false,
fallback = false,
canvas = document.createElement('canvas');

//http://javascript.nwbox.com/ContentLoaded by Diego Perini with modifications
function contentLoaded(n,t){var l="complete",s="readystatechange",u=!1,h=u,c=!0,i=n.document,a=i.documentElement,e=i.addEventListener?"addEventListener":"attachEvent",v=i.addEventListener?"removeEventListener":"detachEvent",f=i.addEventListener?"":"on",r=function(e){(e.type!=s||i.readyState==l)&&((e.type=="load"?n:i)[v](f+e.type,r,u),!h&&(h=!0)&&t.call(n,null))},o=function(){try{a.doScroll("left")}catch(n){setTimeout(o,50);return}r("poll")};if(i.readyState==l)t.call(n,"lazy");else{if(i.createEventObject&&a.doScroll){try{c=!n.frameElement}catch(y){}c&&o()}i[e](f+"DOMContentLoaded",r,u),i[e](f+s,r,u),n[e](f+"load",r,u)}};

//https://gist.github.com/991057 by Jed Schmidt with modifications
function selector(a){
	a=a.match(/^(\W)?(.*)/);var b=document["getElement"+(a[1]?a[1]=="#"?"ById":"sByClassName":"sByTagName")](a[2]);
	var ret=[];	b!=null&&(b.length?ret=b:b.length==0?ret=b:ret=[b]);	return ret;
}

//shallow object property extend
function extend(a,b){var c={};for(var d in a)c[d]=a[d];for(var e in b)c[e]=b[e];return c}

function draw(ctx, dimensions, template) {
	var dimension_arr = [dimensions.height, dimensions.width].sort();
	var maxFactor = Math.round(dimension_arr[1] / 16),
		minFactor = Math.round(dimension_arr[0] / 16);
	var text_height = Math.max(template.size, maxFactor);
	canvas.width = dimensions.width;
	canvas.height = dimensions.height;
	ctx.textAlign = "center";
	ctx.textBaseline = "middle";
	ctx.fillStyle = template.background;
	ctx.fillRect(0, 0, dimensions.width, dimensions.height);
	ctx.fillStyle = template.foreground;
	ctx.font = "bold " + text_height + "px sans-serif";
	var text = template.text ? template.text : (dimensions.width + "x" + dimensions.height);
	if (Math.round(ctx.measureText(text).width) / dimensions.width > 1) {
		text_height = Math.max(minFactor, template.size);
	}
	ctx.font = "bold " + text_height + "px sans-serif";
	ctx.fillText(text, (dimensions.width / 2), (dimensions.height / 2), dimensions.width);
	return canvas.toDataURL("image/png");
}

if (!canvas.getContext) {
	fallback = true;
} else {
	if (canvas.toDataURL("image/png").indexOf("data:image/png") < 0) {
		//Android doesn't support data URI
		fallback = true;
	} else {
		var ctx = canvas.getContext("2d");
	}
}

var settings = {
	domain: "holder.js",
	images: "img",
	themes: {
		"gray": {
			background: "#eee",
			foreground: "#aaa",
			size: 12
		},
		"social": {
			background: "#3a5a97",
			foreground: "#fff",
			size: 12
		},
		"industrial": {
			background: "#434A52",
			foreground: "#C2F200",
			size: 12
		}
	}
};



app.flags = {
	dimensions: {
		regex: /([0-9]+)x([0-9]+)/,
		output: function(val){
			var exec = this.regex.exec(val);
			return {
				width: +exec[1],
				height: +exec[2]
			}
		}
	},
	colors: {
		regex: /#([0-9a-f]{3,})\:#([0-9a-f]{3,})/i,
		output: function(val){
			var exec = this.regex.exec(val);
			return {
					size: settings.themes.gray.size,
					foreground: "#" + exec[2],
					background: "#" + exec[1]
					}
		}
	},
	text: {
		regex: /text\:(.*)/,
		output: function(val){
			return this.regex.exec(val)[1];
		}
	}
}

for(var flag in app.flags){
	app.flags[flag].match = function (val){
		return val.match(this.regex)
	}
}

app.add_theme = function (name, theme) {
	name != null && theme != null && (settings.themes[name] = theme);
	return app;
};

app.add_image = function (src, el) {
	var node = selector(el);
	if (node.length) {
		for (var i = 0, l = node.length; i < l; i++) {
			var img = document.createElement("img")
			img.setAttribute("data-src", src);
			node[i].appendChild(img);
		}
	}
	return app;
};

app.run = function (o) {
	var options = extend(settings, o),
		images = selector(options.images),
		preempted = true;

	for (var l = images.length, i = 0; i < l; i++) {
		var theme = settings.themes.gray;
		var src = images[i].getAttribute("data-src") || images[i].getAttribute("src");
		if (src && !! ~src.indexOf(options.domain)) {
			var render = false,
				dimensions = null,
				text = null;
			var flags = src.substr(src.indexOf(options.domain) + options.domain.length + 1).split("/");
			for (sl = flags.length, j = 0; j < sl; j++) {
				if (app.flags.dimensions.match(flags[j])) {
					render = true;
					dimensions = app.flags.dimensions.output(flags[j]);
				} else if (app.flags.colors.match(flags[j])) {
					theme = app.flags.colors.output(flags[j]);
				} else if (options.themes[flags[j]]) {
					//If a theme is specified, it will override custom colors
					theme = options.themes[flags[j]];
				} else if (app.flags.text.match(flags[j])) {
					text = app.flags.text.output(flags[j]);
				}
			}
			if (render) {
				images[i].setAttribute("data-src", src);
				var dimensions_caption = dimensions.width + "x" + dimensions.height;
				images[i].setAttribute("alt", text ? text : theme.text ? theme.text + " [" + dimensions_caption + "]" : dimensions_caption);

				// Fallback
        // images[i].style.width = dimensions.width + "px";
        // images[i].style.height = dimensions.height + "px";
				images[i].style.backgroundColor = theme.background;

				var theme = (text ? extend(theme, {
						text: text
					}) : theme);

				if (!fallback) {
					images[i].setAttribute("src", draw(ctx, dimensions, theme));
				}
			}
		}
	}
	return app;
};
contentLoaded(win, function () {
	preempted || app.run()
})

})(Holder, window);

/*
 * jQuery Reveal Plugin 1.1
 * www.ZURB.com
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/
/*globals jQuery */

(function ($) {
  'use strict';
  //
  // Global variable.
  // Helps us determine if the current modal is being queued for display.
  //
  var modalQueued = false;

  //
  // Bind the live 'click' event to all anchor elemnets with the data-reveal-id attribute.
  //
  $(document).on('click', 'a[data-reveal-id]', function ( event ) {
    //
    // Prevent default action of the event.
    //
    event.preventDefault();
    //
    // Get the clicked anchor data-reveal-id attribute value.
    //
    var modalLocation = $( this ).attr( 'data-reveal-id' );
    //
    // Find the element with that modalLocation id and call the reveal plugin.
    //
    $( '#' + modalLocation ).reveal( $( this ).data() );

  });

  /**
   * @module reveal
   * @property {Object} [options] Reveal options
   */
  $.fn.reveal = function ( options ) {
      /*
       * Cache the document object.
       */
    var $doc = $( document ),
        /*
         * Default property values.
         */
        defaults = {
          /**
           * Possible options: fade, fadeAndPop, none
           *
           * @property animation
           * @type {String}
           * @default fadeAndPop
           */
          animation: 'fadeAndPop',
          /**
           * Speed at which the reveal should show. How fast animtions are.
           *
           * @property animationSpeed
           * @type {Integer}
           * @default 300
           */
          animationSpeed: 300,
          /**
           * Should the modal close when the background is clicked?
           *
           * @property closeOnBackgroundClick
           * @type {Boolean}
           * @default true
           */
          closeOnBackgroundClick: true,
          /**
           * Specify a class name for the 'close modal' element.
           * This element will close an open modal.
           *
           @example
           <a href='#close' class='close-reveal-modal'>Close Me</a>
           *
           * @property dismissModalClass
           * @type {String}
           * @default close-reveal-modal
           */
          dismissModalClass: 'close-reveal-modal',
          /**
           * Specify a callback function that triggers 'before' the modal opens.
           *
           * @property open
           * @type {Function}
           * @default function(){}
           */
          open: $.noop,
          /**
           * Specify a callback function that triggers 'after' the modal is opened.
           *
           * @property opened
           * @type {Function}
           * @default function(){}
           */
          opened: $.noop,
          /**
           * Specify a callback function that triggers 'before' the modal prepares to close.
           *
           * @property close
           * @type {Function}
           * @default function(){}
           */
          close: $.noop,
          /**
           * Specify a callback function that triggers 'after' the modal is closed.
           *
           * @property closed
           * @type {Function}
           * @default function(){}
           */
          closed: $.noop
        }
    ;
    //
    // Extend the default options.
    // This replaces the passed in option (options) values with default values.
    //
    options = $.extend( {}, defaults, options );

    //
    // Apply the plugin functionality to each element in the jQuery collection.
    //
    return this.not('.reveal-modal.open').each( function () {
        //
        // Cache the modal element
        //
      var modal = $( this ),
        //
        // Get the current css 'top' property value in decimal format.
        //
        topMeasure = parseInt( modal.css( 'top' ), 10 ),
        //
        // Calculate the top offset.
        //
        topOffset = modal.height() + topMeasure,
        //
        // Helps determine if the modal is locked.
        // This way we keep the modal from triggering while it's in the middle of animating.
        //
        locked = false,
        //
        // Get the modal background element.
        //
        modalBg = $( '.reveal-modal-bg' ),
        //
        // Show modal properties
        //
        cssOpts = {
          //
          // Used, when we show the modal.
          //
          open : {
            //
            // Set the 'top' property to the document scroll minus the calculated top offset.
            //
            'top': 0,
            //
            // Opacity gets set to 0.
            //
            'opacity': 0,
            //
            // Show the modal
            //
            'visibility': 'visible',
            //
            // Ensure it's displayed as a block element.
            //
            'display': 'block'
          },
          //
          // Used, when we hide the modal.
          //
          close : {
            //
            // Set the default 'top' property value.
            //
            'top': topMeasure,
            //
            // Has full opacity.
            //
            'opacity': 1,
            //
            // Hide the modal
            //
            'visibility': 'hidden',
            //
            // Ensure the elment is hidden.
            //
            'display': 'none'
          }

        },
        //
        // Initial closeButton variable.
        //
        $closeButton
      ;

      //
      // Do we have a modal background element?
      //
      if ( modalBg.length === 0 ) {
        //
        // No we don't. So, let's create one.
        //
        modalBg = $( '<div />', { 'class' : 'reveal-modal-bg' } )
        //
        // Then insert it after the modal element.
        //
        .insertAfter( modal );
        //
        // Now, fade it out a bit.
        //
        modalBg.fadeTo( 'fast', 0.8 );
      }

      //
      // Helper Methods
      //

      /**
       * Unlock the modal for animation.
       *
       * @method unlockModal
       */
      function unlockModal() {
        locked = false;
      }

      /**
       * Lock the modal to prevent further animation.
       *
       * @method lockModal
       */
      function lockModal() {
        locked = true;
      }

      /**
       * Closes all open modals.
       *
       * @method closeOpenModal
       */
      function closeOpenModals() {
        //
        // Get all reveal-modal elements with the .open class.
        //
        var $openModals = $( ".reveal-modal.open" );
        //
        // Do we have modals to close?
        //
        if ( $openModals.length === 1 ) {
          //
          // Set the modals for animation queuing.
          //
          modalQueued = true;
          //
          // Trigger the modal close event.
          //
          $openModals.trigger( "reveal:close" );
        }

      }
      /**
       * Animates the modal opening.
       * Handles the modal 'open' event.
       *
       * @method openAnimation
       */
      function openAnimation() {
        //
        // First, determine if we're in the middle of animation.
        //
        if ( !locked ) {
          //
          // We're not animating, let's lock the modal for animation.
          //
          lockModal();
          //
          // Close any opened modals.
          //
          closeOpenModals();
          //
          // Now, add the open class to this modal.
          //
          modal.addClass( "open" );

          //
          // Are we executing the 'fadeAndPop' animation?
          //
          if ( options.animation === "fadeAndPop" ) {
            //
            // Yes, we're doing the 'fadeAndPop' animation.
            // Okay, set the modal css properties.
            //
            //
            // Set the 'top' property to the document scroll minus the calculated top offset.
            //
            cssOpts.open.top = $doc.scrollTop() - topOffset;
            //
            // Flip the opacity to 0.
            //
            cssOpts.open.opacity = 0;
            //
            // Set the css options.
            //
            modal.css( cssOpts.open );
            //
            // Fade in the background element, at half the speed of the modal element.
            // So, faster than the modal element.
            //
            modalBg.fadeIn( options.animationSpeed / 2 );

            //
            // Let's delay the next animation queue.
            // We'll wait until the background element is faded in.
            //
            modal.delay( options.animationSpeed / 2 )
            //
            // Animate the following css properties.
            //
            .animate( {
              //
              // Set the 'top' property to the document scroll plus the calculated top measure.
              //
              "top": $doc.scrollTop() + topMeasure + 'px',
              //
              // Set it to full opacity.
              //
              "opacity": 1

            },
            /*
             * Fade speed.
             */
            options.animationSpeed,
            /*
             * End of animation callback.
             */
            function () {
              //
              // Trigger the modal reveal:opened event.
              // This should trigger the functions set in the options.opened property.
              //
              modal.trigger( 'reveal:opened' );

            }); // end of animate.

          } // end if 'fadeAndPop'

          //
          // Are executing the 'fade' animation?
          //
          if ( options.animation === "fade" ) {
            //
            // Yes, were executing 'fade'.
            // Okay, let's set the modal properties.
            //
            cssOpts.open.top = $doc.scrollTop() + topMeasure;
            //
            // Flip the opacity to 0.
            //
            cssOpts.open.opacity = 0;
            //
            // Set the css options.
            //
            modal.css( cssOpts.open );
            //
            // Fade in the modal background at half the speed of the modal.
            // So, faster than modal.
            //
            modalBg.fadeIn( options.animationSpeed / 2 );

            //
            // Delay the modal animation.
            // Wait till the modal background is done animating.
            //
            modal.delay( options.animationSpeed / 2 )
            //
            // Now animate the modal.
            //
            .animate( {
              //
              // Set to full opacity.
              //
              "opacity": 1
            },

            /*
             * Animation speed.
             */
            options.animationSpeed,

            /*
             * End of animation callback.
             */
            function () {
              //
              // Trigger the modal reveal:opened event.
              // This should trigger the functions set in the options.opened property.
              //
              modal.trigger( 'reveal:opened' );

            });

          } // end if 'fade'

          //
          // Are we not animating?
          //
          if ( options.animation === "none" ) {
            //
            // We're not animating.
            // Okay, let's set the modal css properties.
            //
            //
            // Set the top property.
            //
            cssOpts.open.top = $doc.scrollTop() + topMeasure;
            //
            // Set the opacity property to full opacity, since we're not fading (animating).
            //
            cssOpts.open.opacity = 1;
            //
            // Set the css property.
            //
            modal.css( cssOpts.open );
            //
            // Show the modal Background.
            //
            modalBg.css( { "display": "block" } );
            //
            // Trigger the modal opened event.
            //
            modal.trigger( 'reveal:opened' );

          } // end if animating 'none'

        }// end if !locked

      }// end openAnimation


      function openVideos() {
        var video = modal.find('.flex-video'),
            iframe = video.find('iframe');
        if (iframe.length > 0) {
          iframe.attr("src", iframe.data("src"));
          video.fadeIn(100);
        }
      }

      //
      // Bind the reveal 'open' event.
      // When the event is triggered, openAnimation is called
      // along with any function set in the options.open property.
      //
      modal.bind( 'reveal:open.reveal', openAnimation );
      modal.bind( 'reveal:open.reveal', openVideos);

      /**
       * Closes the modal element(s)
       * Handles the modal 'close' event.
       *
       * @method closeAnimation
       */
      function closeAnimation() {
        //
        // First, determine if we're in the middle of animation.
        //
        if ( !locked ) {
          //
          // We're not animating, let's lock the modal for animation.
          //
          lockModal();
          //
          // Clear the modal of the open class.
          //
          modal.removeClass( "open" );

          //
          // Are we using the 'fadeAndPop' animation?
          //
          if ( options.animation === "fadeAndPop" ) {
            //
            // Yes, okay, let's set the animation properties.
            //
            modal.animate( {
              //
              // Set the top property to the document scrollTop minus calculated topOffset.
              //
              "top":  $doc.scrollTop() - topOffset + 'px',
              //
              // Fade the modal out, by using the opacity property.
              //
              "opacity": 0

            },
            /*
             * Fade speed.
             */
            options.animationSpeed / 2,
            /*
             * End of animation callback.
             */
            function () {
              //
              // Set the css hidden options.
              //
              modal.css( cssOpts.close );

            });
            //
            // Is the modal animation queued?
            //
            if ( !modalQueued ) {
              //
              // Oh, the modal(s) are mid animating.
              // Let's delay the animation queue.
              //
              modalBg.delay( options.animationSpeed )
              //
              // Fade out the modal background.
              //
              .fadeOut(
              /*
               * Animation speed.
               */
              options.animationSpeed,
             /*
              * End of animation callback.
              */
              function () {
                //
                // Trigger the modal 'closed' event.
                // This should trigger any method set in the options.closed property.
                //
                modal.trigger( 'reveal:closed' );

              });

            } else {
              //
              // We're not mid queue.
              // Trigger the modal 'closed' event.
              // This should trigger any method set in the options.closed propety.
              //
              modal.trigger( 'reveal:closed' );

            } // end if !modalQueued

          } // end if animation 'fadeAndPop'

          //
          // Are we using the 'fade' animation.
          //
          if ( options.animation === "fade" ) {
            //
            // Yes, we're using the 'fade' animation.
            //
            modal.animate( { "opacity" : 0 },
              /*
               * Animation speed.
               */
              options.animationSpeed,
              /*
               * End of animation callback.
               */
              function () {
              //
              // Set the css close options.
              //
              modal.css( cssOpts.close );

            }); // end animate

            //
            // Are we mid animating the modal(s)?
            //
            if ( !modalQueued ) {
              //
              // Oh, the modal(s) are mid animating.
              // Let's delay the animation queue.
              //
              modalBg.delay( options.animationSpeed )
              //
              // Let's fade out the modal background element.
              //
              .fadeOut(
              /*
               * Animation speed.
               */
              options.animationSpeed,
                /*
                 * End of animation callback.
                 */
                function () {
                  //
                  // Trigger the modal 'closed' event.
                  // This should trigger any method set in the options.closed propety.
                  //
                  modal.trigger( 'reveal:closed' );

              }); // end fadeOut

            } else {
              //
              // We're not mid queue.
              // Trigger the modal 'closed' event.
              // This should trigger any method set in the options.closed propety.
              //
              modal.trigger( 'reveal:closed' );

            } // end if !modalQueued

          } // end if animation 'fade'

          //
          // Are we not animating?
          //
          if ( options.animation === "none" ) {
            //
            // We're not animating.
            // Set the modal close css options.
            //
            modal.css( cssOpts.close );
            //
            // Is the modal in the middle of an animation queue?
            //
            if ( !modalQueued ) {
              //
              // It's not mid queueu. Just hide it.
              //
              modalBg.css( { 'display': 'none' } );
            }
            //
            // Trigger the modal 'closed' event.
            // This should trigger any method set in the options.closed propety.
            //
            modal.trigger( 'reveal:closed' );

          } // end if not animating
          //
          // Reset the modalQueued variable.
          //
          modalQueued = false;
        } // end if !locked

      } // end closeAnimation

      /**
       * Destroys the modal and it's events.
       *
       * @method destroy
       */
      function destroy() {
        //
        // Unbind all .reveal events from the modal.
        //
        modal.unbind( '.reveal' );
        //
        // Unbind all .reveal events from the modal background.
        //
        modalBg.unbind( '.reveal' );
        //
        // Unbind all .reveal events from the modal 'close' button.
        //
        $closeButton.unbind( '.reveal' );
        //
        // Unbind all .reveal events from the body.
        //
        $( 'body' ).unbind( '.reveal' );

      }

      function closeVideos() {
        var video = modal.find('.flex-video'),
            iframe = video.find('iframe');
        if (iframe.length > 0) {
          iframe.data("src", iframe.attr("src"));
          iframe.attr("src", "");
          video.fadeOut(100);  
        }
      }

      //
      // Bind the modal 'close' event
      //
      modal.bind( 'reveal:close.reveal', closeAnimation );
      modal.bind( 'reveal:closed.reveal', closeVideos );
      //
      // Bind the modal 'opened' + 'closed' event
      // Calls the unlockModal method.
      //
      modal.bind( 'reveal:opened.reveal reveal:closed.reveal', unlockModal );
      //
      // Bind the modal 'closed' event.
      // Calls the destroy method.
      //
      modal.bind( 'reveal:closed.reveal', destroy );
      //
      // Bind the modal 'open' event
      // Handled by the options.open property function.
      //
      modal.bind( 'reveal:open.reveal', options.open );
      //
      // Bind the modal 'opened' event.
      // Handled by the options.opened property function.
      //
      modal.bind( 'reveal:opened.reveal', options.opened );
      //
      // Bind the modal 'close' event.
      // Handled by the options.close property function.
      //
      modal.bind( 'reveal:close.reveal', options.close );
      //
      // Bind the modal 'closed' event.
      // Handled by the options.closed property function.
      //
      modal.bind( 'reveal:closed.reveal', options.closed );

      //
      // We're running this for the first time.
      // Trigger the modal 'open' event.
      //
      modal.trigger( 'reveal:open' );

      //
      // Get the closeButton variable element(s).
      //
     $closeButton = $( '.' + options.dismissModalClass )
     //
     // Bind the element 'click' event and handler.
     //
     .bind( 'click.reveal', function () {
        //
        // Trigger the modal 'close' event.
        //
        modal.trigger( 'reveal:close' );

      });

     //
     // Should we close the modal background on click?
     //
     if ( options.closeOnBackgroundClick ) {
      //
      // Yes, close the modal background on 'click'
      // Set the modal background css 'cursor' propety to pointer.
      // Adds a pointer symbol when you mouse over the modal background.
      //
      modalBg.css( { "cursor": "pointer" } );
      //
      // Bind a 'click' event handler to the modal background.
      //
      modalBg.bind( 'click.reveal', function () {
        //
        // Trigger the modal 'close' event.
        //
        modal.trigger( 'reveal:close' );

      });

     }

     //
     // Bind keyup functions on the body element.
     // We'll want to close the modal when the 'escape' key is hit.
     //
     $( 'body' ).bind( 'keyup.reveal', function ( event ) {
      //
      // Did the escape key get triggered?
      //
       if ( event.which === 27 ) { // 27 is the keycode for the Escape key
         //
         // Escape key was triggered.
         // Trigger the modal 'close' event.
         //
         modal.trigger( 'reveal:close' );
       }

      }); // end $(body)

    }); // end this.each

  }; // end $.fn

} ( jQuery ) );
;(function ($, window, document, undefined) {
  'use strict';

  var settings = {
        callback: $.noop,
        deep_linking: true,
        init: false
      },

      methods = {
        init : function (options) {
          settings = $.extend({}, settings, options);

          return this.each(function () {
            if (!settings.init) methods.events();

            if (settings.deep_linking) methods.from_hash();
          });
        },

        events : function () {
          $(document).on('click.fndtn', '.tabs a', function (e) {
            methods.set_tab($(this).parent('dd, li'), e);
          });
          
          settings.init = true;
        },

        set_tab : function ($tab, e) {
          var $activeTab = $tab.closest('dl, ul').find('.active'),
              target = $tab.children('a').attr("href"),
              hasHash = /^#/.test(target),
              $content = $(target + 'Tab');

          if (hasHash && $content.length > 0) {
            // Show tab content
            if (e && !settings.deep_linking) e.preventDefault();
            $content.closest('.tabs-content').children('li').removeClass('active').hide();
            $content.css('display', 'block').addClass('active');
          }

          // Make active tab
          $activeTab.removeClass('active');
          $tab.addClass('active');

          settings.callback();
        },

        from_hash : function () {
          var hash = window.location.hash,
              $tab = $('a[href="' + hash + '"]');

          $tab.trigger('click.fndtn');
        }
      }

  $.fn.foundationTabs = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' +  method + ' does not exist on jQuery.foundationTabs');
    }
  };
}(jQuery, this, this.document));

/*
 * jQuery Foundation Tooltips 2.0.2
 * http://foundation.zurb.com
 * Copyright 2012, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

/*jslint unparam: true, browser: true, indent: 2 */

;(function ($, window, undefined) {
  'use strict';
  
  var settings = {
      bodyHeight : 0,
      selector : '.has-tip',
      additionalInheritableClasses : [],
      tooltipClass : '.tooltip',
      tipTemplate : function (selector, content) {
        return '<span data-selector="' + selector + '" class="' + settings.tooltipClass.substring(1) + '">' + content + '<span class="nub"></span></span>';
      }
    },
    methods = {
      init : function (options) {
        settings = $.extend(settings, options);

        // alias the old targetClass option
        settings.selector = settings.targetClass ? settings.targetClass : settings.selector;

        return this.each(function () {
          var $body = $('body');

          if (Modernizr.touch) {
            $body.on('click.tooltip touchstart.tooltip touchend.tooltip', settings.selector, function (e) {
              e.preventDefault();
              $(settings.tooltipClass).hide();
              methods.showOrCreateTip($(this));
            });
            $body.on('click.tooltip touchstart.tooltip touchend.tooltip', settings.tooltipClass, function (e) {
              e.preventDefault();
              $(this).fadeOut(150);
            });
          } else {
            $body.on('mouseenter.tooltip mouseleave.tooltip', settings.selector, function (e) {
              var $this = $(this);

              if (e.type === 'mouseenter') {
                methods.showOrCreateTip($this);
              } else if (e.type === 'mouseleave') {
                methods.hide($this);
              }
            });
          }

          $(this).data('tooltips', true);

        });
      },
      showOrCreateTip : function ($target, content) {
        var $tip = methods.getTip($target);

        if ($tip && $tip.length > 0) {
          methods.show($target);
        } else {
          methods.create($target, content);
        }
      },
      getTip : function ($target) {
        var selector = methods.selector($target),
          tip = null;

        if (selector) {
          tip = $('span[data-selector=' + selector + ']' + settings.tooltipClass);
        }
        return (tip.length > 0) ? tip : false;
      },
      selector : function ($target) {
        var id = $target.attr('id'),
          dataSelector = $target.data('selector');

        if (id === undefined && dataSelector === undefined) {
          dataSelector = 'tooltip' + Math.random().toString(36).substring(7);
          $target.attr('data-selector', dataSelector);
        }
        return (id) ? id : dataSelector;
      },
      create : function ($target, content) {
        var $tip = $(settings.tipTemplate(methods.selector($target),
          $('<div>').html(content ? content : $target.attr('title')).html())),
          classes = methods.inheritable_classes($target);

        $tip.addClass(classes).appendTo('body');
        if (Modernizr.touch) {
          $tip.append('<span class="tap-to-close">tap to close </span>');
        }
        $target.removeAttr('title');
        methods.show($target);
      },
      reposition : function (target, tip, classes) {
        var width, nub, nubHeight, nubWidth, column, objPos;

        tip.css('visibility', 'hidden').show();

        width = target.data('width');
        nub = tip.children('.nub');
        nubHeight = nub.outerHeight();
        nubWidth = nub.outerWidth();

        objPos = function (obj, top, right, bottom, left, width) {
          return obj.css({
            'top' : top,
            'bottom' : bottom,
            'left' : left,
            'right' : right,
            'max-width' : (width) ? width : 'auto'
          }).end();
        };

        objPos(tip, (target.offset().top + target.outerHeight() + 10), 'auto', 'auto', target.offset().left, width);
        objPos(nub, -nubHeight, 'auto', 'auto', 10);

        if ($(window).width() < 767) {
          column = target.closest('.columns');

          if (column.length < 0) {
            // if not using Foundation
            column = $('body');
          }
          tip.width(column.outerWidth() - 25).css('left', 15).addClass('tip-override');
          objPos(nub, -nubHeight, 'auto', 'auto', target.offset().left);
        } else {
          if (classes && classes.indexOf('tip-top') > -1) {
            objPos(tip, (target.offset().top - tip.outerHeight() - nubHeight), 'auto', 'auto', target.offset().left, width)
              .removeClass('tip-override');
            objPos(nub, 'auto', 'auto', -nubHeight, 'auto');
          } else if (classes && classes.indexOf('tip-left') > -1) {
            objPos(tip, (target.offset().top + (target.outerHeight() / 2) - nubHeight), 'auto', 'auto', (target.offset().left - tip.outerWidth() - 10), width)
              .removeClass('tip-override');
            objPos(nub, (tip.outerHeight() / 2) - (nubHeight / 2), -nubHeight, 'auto', 'auto');
          } else if (classes && classes.indexOf('tip-right') > -1) {
            objPos(tip, (target.offset().top + (target.outerHeight() / 2) - nubHeight), 'auto', 'auto', (target.offset().left + target.outerWidth() + 10), width)
              .removeClass('tip-override');
            objPos(nub, (tip.outerHeight() / 2) - (nubHeight / 2), 'auto', 'auto', -nubHeight);
          } else if (classes && classes.indexOf('tip-centered-top') > -1) {
            objPos(tip, (target.offset().top - tip.outerHeight() - nubHeight), 'auto', 'auto', (target.offset().left + ((target.outerWidth() - tip.outerWidth()) / 2) ), width)
              .removeClass('tip-override');
            objPos(nub, 'auto', ((tip.outerWidth() / 2) -(nubHeight / 2)), -nubHeight, 'auto');
          } else if (classes && classes.indexOf('tip-centered-bottom') > -1) {
            objPos(tip, (target.offset().top + target.outerHeight() + 10), 'auto', 'auto', (target.offset().left + ((target.outerWidth() - tip.outerWidth()) / 2) ), width)
              .removeClass('tip-override');
            objPos(nub, -nubHeight, ((tip.outerWidth() / 2) -(nubHeight / 2)), 'auto', 'auto');
          }
        }
        tip.css('visibility', 'visible').hide();
      },
      inheritable_classes : function (target) {
        var inheritables = ['tip-top', 'tip-left', 'tip-bottom', 'tip-right', 'tip-centered-top', 'tip-centered-bottom', 'noradius'].concat(settings.additionalInheritableClasses),
          classes = target.attr('class'),
          filtered = classes ? $.map(classes.split(' '), function (el, i) {
              if ($.inArray(el, inheritables) !== -1) {
                return el;
              }
          }).join(' ') : '';
          
        return $.trim(filtered);
      },
      show : function ($target) {
        var $tip = methods.getTip($target);

        methods.reposition($target, $tip, $target.attr('class'));
        $tip.fadeIn(150);
      },
      hide : function ($target) {
        var $tip = methods.getTip($target);

        $tip.fadeOut(150);
      },
      reload : function () {
        var $self = $(this);

        return ($self.data('tooltips')) ? $self.foundationTooltips('destroy').foundationTooltips('init') : $self.foundationTooltips('init');
      },
      destroy : function () {
        return this.each(function () {
          $(window).off('.tooltip');
          $(settings.selector).off('.tooltip');
          $(settings.tooltipClass).each(function (i) {
            $($(settings.selector).get(i)).attr('title', $(this).text());
          }).remove();
        });
      }
    };

  $.fn.foundationTooltips = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' +  method + ' does not exist on jQuery.foundationTooltips');
    }
  };
}(jQuery, this));

/*
 * jQuery Foundation Top Bar 2.0.3
 * http://foundation.zurb.com
 * Copyright 2012, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

/*jslint unparam: true, browser: true, indent: 2 */

;(function ($, window, undefined) {
  'use strict';

  var settings = {
      index : 0,
      initialized : false
    },

    methods = {
      init : function (options) {
        return this.each(function () {
          settings = $.extend(settings, options);
          settings.$w = $(window),
          settings.$topbar = $('nav.top-bar'),
          settings.$section = settings.$topbar.find('section'),
          settings.$titlebar = settings.$topbar.children('ul:first');

          var breakpoint = $("<div class='top-bar-js-breakpoint'/>").appendTo("body");
          settings.breakPoint = breakpoint.width();
          breakpoint.remove();

          if (!settings.initialized) {
            methods.assemble();
            settings.initialized = true;
          }

          if (!settings.height) {
            methods.largestUL();
          }

          if (settings.$topbar.parent().hasClass('fixed')) {
            $('body').css('padding-top',settings.$topbar.outerHeight())
          }

          $('.top-bar .toggle-topbar').die('click.fndtn').live('click.fndtn', function (e) {
            e.preventDefault();

            if (methods.breakpoint()) {
              settings.$topbar.toggleClass('expanded');
              settings.$topbar.css('min-height', '');
            }

            if (!settings.$topbar.hasClass('expanded')) {
              settings.$section.css({left: '0%'});
              settings.$section.find('>.name').css({left: '100%'});
              settings.$section.find('li.moved').removeClass('moved');
              settings.index = 0;
            }
          });

          // Show the Dropdown Levels on Click
          $('.top-bar .has-dropdown>a').die('click.fndtn').live('click.fndtn', function (e) {
            if (Modernizr.touch || methods.breakpoint())
              e.preventDefault();

            if (methods.breakpoint()) {
              var $this = $(this),
                  $selectedLi = $this.closest('li');

              settings.index += 1;
              $selectedLi.addClass('moved');
              settings.$section.css({left: -(100 * settings.index) + '%'});
              settings.$section.find('>.name').css({left: 100 * settings.index + '%'});

              $this.siblings('ul').height(settings.height + settings.$titlebar.outerHeight(true));
              settings.$topbar.css('min-height', settings.height + settings.$titlebar.outerHeight(true) * 2)
            }
          });

          $(window).on('resize.fndtn.topbar',function() {
            if (!methods.breakpoint()) {
              settings.$topbar.css('min-height', '');
            }
          });

          // Go up a level on Click
          $('.top-bar .has-dropdown .back').die('click.fndtn').live('click.fndtn', function (e) {
            e.preventDefault();

            var $this = $(this),
              $movedLi = $this.closest('li.moved'),
              $previousLevelUl = $movedLi.parent();

            settings.index -= 1;
            settings.$section.css({left: -(100 * settings.index) + '%'});
            settings.$section.find('>.name').css({'left': 100 * settings.index + '%'});

            if (settings.index === 0) {
              settings.$topbar.css('min-height', 0);
            }

            setTimeout(function () {
              $movedLi.removeClass('moved');
            }, 300);
          });
        });
      },

      breakpoint : function () {
        return settings.$w.width() < settings.breakPoint;
      },

      assemble : function () {
        // Pull element out of the DOM for manipulation
        settings.$section.detach();

        settings.$section.find('.has-dropdown>a').each(function () {
          var $link = $(this),
              $dropdown = $link.siblings('.dropdown'),
              $titleLi = $('<li class="title back js-generated"><h5><a href="#"></a></h5></li>');

          // Copy link to subnav
          $titleLi.find('h5>a').html($link.html());
          $dropdown.prepend($titleLi);
        });

        // Put element back in the DOM
        settings.$section.appendTo(settings.$topbar);
      },

      largestUL : function () {
        var uls = settings.$topbar.find('section ul ul'),
            largest = uls.first(),
            total = 0;

        uls.each(function () {
          if ($(this).children('li').length > largest.children('li').length) {
            largest = $(this);
          }
        });

        largest.children('li').each(function () { total += $(this).outerHeight(true); });

        settings.height = total;
      }
    };

  $.fn.foundationTopBar = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' +  method + ' does not exist on jQuery.foundationTopBar');
    }
  };

  // Monitor scroll position for sticky
  if ($('.sticky').length > 0) {
    var distance = $('.sticky').length ? $('.sticky').offset().top: 0,
        $window = $(window);

      $window.scroll(function() {
        if ( $window.scrollTop() >= distance ) {
           $(".sticky").addClass("fixed");
        }

       else if ( $window.scrollTop() < distance ) {
          $(".sticky").removeClass("fixed");
       }
    });
  }

}(jQuery, this));

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(window, document, $) {

	var isInputSupported = 'placeholder' in document.createElement('input'),
	    isTextareaSupported = 'placeholder' in document.createElement('textarea'),
	    prototype = $.fn,
	    valHooks = $.valHooks,
	    hooks,
	    placeholder;

	if (isInputSupported && isTextareaSupported) {

		placeholder = prototype.placeholder = function() {
			return this;
		};

		placeholder.input = placeholder.textarea = true;

	} else {

		placeholder = prototype.placeholder = function() {
			var $this = this;
			$this
				.filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
				.not('.placeholder')
				.bind({
					'focus.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder
				})
				.data('placeholder-enabled', true)
				.trigger('blur.placeholder');
			return $this;
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			'get': function(element) {
				var $element = $(element);
				return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
			},
			'set': function(element, value) {
				var $element = $(element);
				if (!$element.data('placeholder-enabled')) {
					return element.value = value;
				}
				if (value == '') {
					element.value = value;
					// Issue #56: Setting the placeholder causes problems if the element continues to have focus.
					if (element != document.activeElement) {
						// We can't use `triggerHandler` here because of dummy text/password inputs :(
						setPlaceholder.call(element);
					}
				} else if ($element.hasClass('placeholder')) {
					clearPlaceholder.call(element, true, value) || (element.value = value);
				} else {
					element.value = value;
				}
				// `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
				return $element;
			}
		};

		isInputSupported || (valHooks.input = hooks);
		isTextareaSupported || (valHooks.textarea = hooks);

		$(function() {
			// Look for forms
			$(document).delegate('form', 'submit.placeholder', function() {
				// Clear the placeholder values so they don't get submitted
				var $inputs = $('.placeholder', this).each(clearPlaceholder);
				setTimeout(function() {
					$inputs.each(setPlaceholder);
				}, 10);
			});
		});

		// Clear placeholder values upon page reload
		$(window).bind('beforeunload.placeholder', function() {
			$('.placeholder').each(function() {
				this.value = '';
			});
		});

	}

	function args(elem) {
		// Return an object of element attributes
		var newAttrs = {},
		    rinlinejQuery = /^jQuery\d+$/;
		$.each(elem.attributes, function(i, attr) {
			if (attr.specified && !rinlinejQuery.test(attr.name)) {
				newAttrs[attr.name] = attr.value;
			}
		});
		return newAttrs;
	}

	function clearPlaceholder(event, value) {
		var input = this,
		    $input = $(input);
		if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
			if ($input.data('placeholder-password')) {
				$input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
				// If `clearPlaceholder` was called from `$.valHooks.input.set`
				if (event === true) {
					return $input[0].value = value;
				}
				$input.focus();
			} else {
				input.value = '';
				$input.removeClass('placeholder');
				input == document.activeElement && input.select();
			}
		}
	}

	function setPlaceholder() {
		var $replacement,
		    input = this,
		    $input = $(input),
		    $origInput = $input,
		    id = this.id;
		if (input.value == '') {
			if (input.type == 'password') {
				if (!$input.data('placeholder-textinput')) {
					try {
						$replacement = $input.clone().attr({ 'type': 'text' });
					} catch(e) {
						$replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
					}
					$replacement
						.removeAttr('name')
						.data({
							'placeholder-password': true,
							'placeholder-id': id
						})
						.bind('focus.placeholder', clearPlaceholder);
					$input
						.data({
							'placeholder-textinput': $replacement,
							'placeholder-id': id
						})
						.before($replacement);
				}
				$input = $input.removeAttr('id').hide().prev().attr('id', id).show();
				// Note: `$input[0] != input` now!
			}
			$input.addClass('placeholder');
			$input[0].value = $input.attr('placeholder');
		} else {
			$input.removeClass('placeholder');
		}
	}

}(this, document, jQuery));
;(function ($, window, undefined) {
  'use strict';

  var $doc = $(document),
      Modernizr = window.Modernizr;

  $(document).ready(function() {
    $.fn.foundationAlerts           ? $doc.foundationAlerts() : null;
    $.fn.foundationButtons          ? $doc.foundationButtons() : null;
    $.fn.foundationAccordion        ? $doc.foundationAccordion() : null;
    $.fn.foundationNavigation       ? $doc.foundationNavigation() : null;
    $.fn.foundationTopBar           ? $doc.foundationTopBar() : null;
    $.fn.foundationCustomForms      ? $doc.foundationCustomForms() : null;
    $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
    $.fn.foundationTabs             ? $doc.foundationTabs({callback : $.foundation.customForms.appendCustomMarkup}) : null;
    $.fn.foundationTooltips         ? $doc.foundationTooltips() : null;
    $.fn.foundationMagellan         ? $doc.foundationMagellan() : null;
    $.fn.foundationClearing         ? $doc.foundationClearing() : null;

    $.fn.placeholder                ? $('input, textarea').placeholder() : null;
  });

  // UNCOMMENT THE LINE YOU WANT BELOW IF YOU WANT IE8 SUPPORT AND ARE USING .block-grids
  // $('.block-grid.two-up>li:nth-child(2n+1)').css({clear: 'both'});
  // $('.block-grid.three-up>li:nth-child(3n+1)').css({clear: 'both'});
  // $('.block-grid.four-up>li:nth-child(4n+1)').css({clear: 'both'});
  // $('.block-grid.five-up>li:nth-child(5n+1)').css({clear: 'both'});

  // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
  if (Modernizr.touch && !window.location.hash) {
    $(window).load(function () {
      setTimeout(function () {
        window.scrollTo(0, 1);
      }, 0);
    });
  }

})(jQuery, this);
