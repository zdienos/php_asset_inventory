$(document).ready(function(){

    
    // show / hide menu
    $( '#nav-toggle-button' ).on( "click", function( event ){
        
        console.log(this);
        console.log("button clicked");
        
        // grab items
        var menuDiv = $( '#nav-menu-links' );
        console.log(menuDiv);
        var menuButton = $( this );
        console.log(menuButton);
        
        // grab mouse pos
        var mouseX = event.pageX;
        var mouseY = event.pageY;
    
        // get dimensions of menu
        var menuWidth = menuDiv.width();
        var menuHeight = menuDiv.height();
        var menuTop = $( '#content-wrap' ).position().top;
        //var menuLeft = $( '#content-wrap' ).width() - menuWidth;
        var fullWidth = $( '.container' ).outerWidth(true)
        var offsetX = ( fullWidth - $( '.container' ).width() ) / 2;
        var menuLeft = ( $( '.container' ).width() + offsetX ) - menuWidth;

        console.log(menuTop);
        console.log(menuLeft);
        menuDiv.css({top: menuTop, left: menuLeft, position:'absolute'});
        console.log(menuDiv);
        $( menuDiv ).toggle();
        
    });
    
    
    $( document ).ready(function(){});
    
});