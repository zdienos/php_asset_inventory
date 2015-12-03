$(document).ready(function(){
    $( '#nav-toggle-button' ).on( "click", function( event ){
        var mouseX = event.pageX;
        var mouseY = event.pageY;
        var menuButton = $( '#nav-button-toggle' );
        var buttonPos = menuButton.position();
        var buttonWidth = menuButton.width();
        var buttonHeight = menuButton.height();
        var menuTop = buttonPos.top + button
        $( '#nav-menu-links' ).toggle();
        
    });
});