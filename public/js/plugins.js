(function ($, root, undefined) {
	
	jQuery.fn.sortElements=function(){var t=[].sort;return function(e,n){n=n||function(){return this};var r=this.map(function(){var t=n.call(this),e=t.parentNode,r=e.insertBefore(document.createTextNode(""),t.nextSibling);return function(){if(e===this)throw new Error("You can't sort elements if any one is a descendant of another.");e.insertBefore(this,r),e.removeChild(r)}});return t.call(this,e).each(function(t){r[t].call(n.call(this))})}}();
	
})(jQuery, this);
