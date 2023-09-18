
(() => {
	
	function each(selector, cb)
	{
		const items = document.querySelectorAll(selector);
		
		for (let i = 0, z = items.length; i < z; i++) {
			cb.call(items[i], items[i]);
		}
	}

	function lazyAttr(elem, to, from)
	{
		elem.setAttribute(to, elem.getAttribute(from));
		elem.removeAttribute(from);
	}
	
	window.addEventListener('load', () => {

		each('link[rel="preload"][as="style"]', link => {
			link.setAttribute('rel', 'stylesheet');
		});
		
		each('script[data-src]', script => {
			script.async = false;
			lazyAttr(script, 'src', 'data-src');
		});
		
	});
	
})();
