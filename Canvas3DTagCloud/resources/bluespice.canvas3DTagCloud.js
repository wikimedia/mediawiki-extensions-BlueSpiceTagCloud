(function(mw, $){
	$(document).ready(function() {
        if(!$('#bs-tagcloud3dcanvas-3dcanvas-0').tagcanvas({
          textColour: '#3e5389',
          outlineColour: '#3e5389',
          reverse: true,
          depth: 0.8,
          maxSpeed: 0.05,
		  weight: true,
		  weightMode: 'size',
		  wheelZoom: false,
		  weightFrom: "data-weight",
		  shadowBlur: 10,
		  shadowOffset: [1,1]
        })) {
          // something went wrong, hide the canvas container
          $('#bs-tagcloud3dcanvas-3dcanvas-0').hide();
        }
      });
}( mediaWiki, jQuery ));