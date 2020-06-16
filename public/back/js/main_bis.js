(function() {
	
    /*
    # =============================================================================
    #   Mobile Nav
    # =============================================================================
    */

    $('.navbar-toggle').click(function() {
		return $('body, html').toggleClass("nav-open");
    });
    
	/*
    # =============================================================================
    #   Fancybox Modal
    # =============================================================================
    */

	$( ".fancybox" ).fancybox({
		maxWidth: 											700,
		height: 											"auto",
		fitToView: 											false,
		autoSize: 											true,
		padding: 											2,
		nextEffect: 										"fade",
		prevEffect: 										"fade",
		helpers: {
			title: {
				type: 										"outside"
			}
		}
	});

	/*
	# =============================================================================
	#   Ladda loading buttons
	# =============================================================================
	*/
	
	/*Ladda.bind( ".ladda-button-direct:not(.progress-demo)", {
		timeout: 											200
	});
	Ladda.bind(".ladda-button-direct.progress-demo", {
      callback: 											function(instance) {
        var interval, progress;
        progress = 											0;
        return interval = 									setInterval(function() {
          progress = 										Math.min(progress + Math.random() * 0.1, 1);
          instance.setProgress(progress);
          if (progress === 1) {
            instance.stop();
            return clearInterval(interval);
          }
        }, 200);
      }
    });*/
	
}).call(this);

$( ".select2able" ).select2();

$( ".ttip" ).tooltip({
	html:													true,
	placement: 												"top"
});

function checkEmail( adr ) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(adr)) {
		return (true);
	}
	return (false);
}

function launch_progress( elem, duree ) {
	
	//alert( "launch_progress" );
	/*Ladda.bind( ".ladda-button-direct:not(.progress-demo)", {
		timeout: 											1000
	});*/
	
	var l = 												Ladda.create( document.querySelector( elem ) );
	l.start();
	in_progress( l, duree );
	
}

function stop_progress( elem ) {
	var l = 												Ladda.create( document.querySelector( elem ) );
	l.stop();
}

function in_progress( instance, duree ) {
	var progress = 											0;
	var interval =											setInterval( function() {
		progress = 											Math.min( progress + Math.random() * 0.1, 1);
		instance.setProgress(progress);
		if (progress === 1) {
			instance.stop();
			return clearInterval( interval );
		}
	}, duree );
}
