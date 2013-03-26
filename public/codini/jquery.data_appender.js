(function( $ ) {
	$.fn.dataAppender = function(targetSelector) {
		var $this = $(this);
		
		$this.click(function(event) {
			event.preventDefault();
			var $target = $(targetSelector);
			var data = $(this).data('code');
			var text = $target.val();
//alert(data + " " + text);
			$target.val(text + " " + data);
		})
	};
})( jQuery );