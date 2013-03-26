requirejs.config({
    baseUrl: '/public',
    paths: {
        jquery: ['//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min'],
        bootstrap: '/codini/public/bootstrap/js/bootstrap.min',
		data_appender: '/codini/public/codini/jquery.data_appender',
    },
    shim: {
        'bootstrap':{deps: ['jquery']}
    }
});
 
requirejs(
	['jquery', 'data_appender', 'bootstrap'], function($, data_appender, _bootstrap)
	{
		$(document).ready(function() {
			$('.code_ref').dataAppender('#template_content');
		});
	}
);