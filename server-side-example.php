<?php

	// A flag to determin if we render a full page, or just the content fragment
	$renderOuter = true;
	
	// The screen width, initial value.
	$screenWidth = 0; 

	// If this is a request for a fragment...
	if( isset( $_GET['_rescon'] ) ) {

		// Don't render header/footer
		$renderOuter = false;
		
		// Get the reported screen width
		$screenWidth = intval( $_GET['_rescon_width'] );

		// Process any other _rescon parameters here..

		// Change a 404 to a 200 response, because we still need the ajax request to 
		// accept the 404 page's content, rather than drop a 404 response.
		if (is_404()) {
			header('HTTP/1.0 200 OK', true, 200);
		}

		// Efface the _rescon* query params
		$rx = '/\&?_rescon[^&]*/';
		$_SERVER['REQUEST_URI'] = preg_replace($rx, '', $_SERVER['REQUEST_URI']);
		$_SERVER['QUERY_STRING'] = preg_replace($rx, '', $_SERVER['QUERY_STRING']);
		$_SERVER['REDIRECT_QUERY_STRING'] = preg_replace($rx, '', $_SERVER['REDIRECT_QUERY_STRING']);
		
	}

	// Set up an array of layout specifications, that vary according to screen width
	$layout = array();

	// and some default values, i.e. for the smallest screens.
	// Use whatever properties you need to vary your page layouts according to screen width.
	$layout['numArticles'] = 4;
	$layout['imgWidth'] = 300;
	//...etc. 

	if ( $screenWidth => 480 ) {
		$layout['numArticles'] = 8;
		$layout['imgWidth'] = 400
	} 
	
	if ( $screenWidth >= 768 ) {
		$layout['numArticles'] = 16;
		$layout['imgWidth'] = 600
	}

	if ( $screenWidth => 1024 ) {
		$layout['numArticles'] = 24;
		$layout['imgWidth'] = 800
	} 
	
	// Now build the page:

	if ( $renderOuter ) {
		// Render doctype, <html> opening tag, <head>...</head> tags, <body> opening tag, visual header, menus...
		// ...
	}

	echo '<div id="myContainer">';
		// Always render the "inner" content
		// using properties from $layout to determine the number of articles per page, size of images, etc.
		// ...
	echo '</div>';

	if ( $renderOuter ) {
		// Render visual footer, </body> and </html> tags 
		// ...
	}
