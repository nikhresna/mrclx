console.time('script');

elementList = document.querySelectorAll('.comment-link');
// console.log(elementList.length);

for (i = 0; i < elementList.length; i++) {
	// console.log(elementList[i]);
	elementList[i].onclick = copyLink;
}

function copyLink() {
	event.preventDefault();
	var input = this.nextElementSibling.nextElementSibling;
	input.select();
	document.execCommand('copy');

	var p = this.nextElementSibling;
	// console.log(p);
	p.innerHTML='copied';
	
	setTimeout(function() {
			p.innerHTML='';
		},
		2000
	)
}

hash = window.location.hash;
hash = hash.replace('#', '');
if (hash) {
	id = document.getElementById(hash);
	// console.log(id);
	id.style.background= '#ddd';

	setTimeout(function() {
			id.style.background= 'initial';
		},
		2000
	)
}

var cookie = document.cookie;
console.log(cookie);
 // = "username=John Smith; expires=Thu, 18 Dec 2013 12:00:00 UTC; path=/";

console.timeEnd('script');
