console.time('start');

// (function () {
//   function CustomEvent ( event, params ) {
//     params = params || { bubbles: false, cancelable: false, detail: undefined };
//     var evt = document.createEvent( 'CustomEvent' );
//     evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
//     return evt;
//     console.log('customEvent');
//   }
//   CustomEvent.prototype = window.Event.prototype;
//   window.CustomEvent = CustomEvent;
//   console.log('anonymous function');
// })();


// var fireWhenReady = function(el) {
//   var notCalled = true,
//   event = new CustomEvent('inViewPort'),
//   checkViewport = function() {
//     var rect = el[0].getBoundingClientRect();
//     if (
//       rect.top >= 0 &&
//       rect.left >= 0 &&
//       rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && 
//       rect.right <= (window.innerWidth || document.documentElement.clientWidth)
//     ) {
//       el[0].dispatchEvent(event);
//       notCalled = false;
//     }
//   };
//   window.addEventListener('scroll', function() {
//     notCalled && requestAnimationFrame(checkViewport);
//   })
// };

// var elCount = document.querySelectorAll('.lazyLoad');

// console.log(elCount);
// console.log(elCount[0]);
// console.log(elCount[1]);
// console.log(elCount[2]);

// for (var i = elCount.length - 1; i < elCount.length; i++) {
// 	elCount[i].addEventListener('inViewPort',function() {
// 	  var dsrc = this.getAttribute('data-src');
// 	  this.setAttribute('src', dsrc);
// 	  console.log('switch attr');
// 	});
// }

// fireWhenReady(document.querySelectorAll('.lazyLoad'), 100);

// function default_avatar() {	
// 	var el = document.getElementsByClassName('avatar-40');
	// console.log(el);

	// for (var i = 0; i < el.length; i++) {
		// console.log(el[i].getAttribute('src'));
		// var src = el[i].getAttribute('src').replace('http://1.gravatar.com/avatar/1649210261536ff2b99ae0257703e397?s=40&d=', '');
		// el[i].setAttribute('src')
		// console.log(i + ' ' + src);
// 	}
// }
// default_avatar();

console.timeEnd('start');
