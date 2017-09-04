<?php 
//helpers go here

function display_errors($errors) {
	$display = '<ul class="bg-danger">';
	foreach($errors as $error) {
		// concat the error on to the display string
		$display .= '<li class="text-danger">'.$error.'</li>';
	}
	$display .= "</ul>";
	return $display;
}


function sanitize($dirtyString) {
	//pre-built php functions which prints tags instead of enacts them
	return htmlentities($dirtyString, ENT_QUOTES, "UTF-8");
}

?>