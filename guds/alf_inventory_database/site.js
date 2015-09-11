$(function() {
    $("#content").load("links.php");
});

function setTooltipListener( domElem ) {
    $( document ).tooltip( {
	items: domElem + "[fname]",
	content: function( ) {
	    var elem = $( this );
	    return "<img src='images/" + elem.attr( "fname" ) + ".jpg' />"
	      + "<br />" + elem.attr( "desc" );
	}
    } );
}

function setContent( name ) {
    $( "#content" ).load( name );
}
