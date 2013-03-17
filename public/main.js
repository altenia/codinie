requirejs.config({
    baseUrl: '/public',
    paths: {
        jquery: ['//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min'],
        bootstrap: 'bootstrap/js/bootstrap.min'
    },
    shim: {
        'bootstrap':{deps: ['jquery']}
    }
});
 
requirejs([
    'jquery', 
    'bootstrap'
    ], function($, _bootstrap){
        // this is where all the site code should begin
        alert("hello");
});