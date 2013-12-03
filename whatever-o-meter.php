<?php
/**
 * Plugin Name: Whatever-o-meter
 * Plugin URI: http://billthefarmer.users.sourceforge.net/wordpress/whatever-o-meter
 * Description: Lets you create a whatever-o-meter using your data.
 * Version: 0.2
 * Author: Bill Farmer
 * Author URI: http://billthefarmer.users.sourceforge.net
 * License: GPL2
*/

/*
 * Created by Bill Farmer
 * Licensed under the LGPL.
 * Copyright (C) 2013 Bill Farmer
*/

// Add scripts hook, also adds shortcodes and further action

add_action('wp_enqueue_scripts', 'whatever_enqueue_scripts');

// Queue scripts and styles. Wordpress includes jquery-ui script files,
// but not all the styles

function whatever_enqueue_scripts() {

    // Check on a page

    if (is_page()) {

	wp_enqueue_style('jquery-ui',
			 plugins_url('/css/jquery-ui.min.css', __FILE__));

	wp_enqueue_script('jquery-rotate',
			  plugins_url('/js/jquery.rotate.min.js', __FILE__),
			  array('jquery'));

	wp_enqueue_script('whatever-o-meter',
			  plugins_url('/js/whatever-o-meter.min.js', __FILE__),
			  array('jquery-ui-core', 'jquery-ui-widget',
				'jquery-ui-mouse', 'jquery-ui-button',
				'jquery-ui-slider', 'jquery-effects-core',
				'jquery'));

	// Add the shortcodes and action to insert the code into the page
	// and add the javascript data

	add_shortcode('whateverometer', 'whatever_shortcode');
	add_shortcode('whatever-o-meter', 'whatever_shortcode');

	add_action('wp_footer', 'whatever_footer');
    }
}

// Add the content if the shortcode is found.

function whatever_shortcode($atts) {

    // Buffer the output

    ob_start();

    $custom = get_post_custom();

    // Check there's at least one question defined, no point else

    if ($custom['question']) {

	// Output the tacho dial

	$image_url = plugins_url('/images/tacho-circle.png', __FILE__);

	echo "\n<!-- whatever-o-meter html -->
<div class=\"whatever-o-meter\" style=\"margin: 0 auto; width: 520px;\">
  <div style=\"position: relative\">
    <img alt=\"tacho-circle\" src=\"$image_url\" width=\"520\" height=\"520\" style=\"max-width: 100%;\" />
    <svg width=\"520\" height=\"520\"
	 style=\"position: absolute; left: 0; top: 0;\">
      <defs>
	<radialgradient id=\"radial\" r=\"50%\">
	  <stop offset=\"0%\" stop-color=\"white\" />
	  <stop offset=\"100%\" stop-color=\"black\" />
	</radialgradient>
      </defs>
      <g transform=\"translate(260,260)\">
	<text id=\"digit1\" class=\"digit\"
	      x=\"-57\" y=\"71\" font-size=\"24\"
	      fill=\"white\" stroke=\"white\">0</text>
	<text id=\"digit2\" class=\"digit\"
	      x=\"-33\" y=\"71\" font-size=\"24\"
	      fill=\"white\" stroke=\"white\">0</text>
	<text id=\"digit3\" class=\"digit\"
	      x=\"-9\" y=\"71\" font-size=\"24\"
	      fill=\"white\" stroke=\"white\">0</text>
	<text id=\"digit4\" class=\"digit\"
	      x=\"15\" y=\"71\" font-size=\"24\"
	      fill=\"white\" stroke=\"white\">0</text>
	<text id=\"digit5\" class=\"digit\"
	      x=\"39\" y=\"71\" font-size=\"24\"
	      fill=\"white\" stroke=\"white\">0</text>
	<g id=\"pointer\">
	  <path d=\"M0,55 L8,47 L3,-185 L0,-189 L-3,-185 L-8,47 Z\"
		fill=\"#ede4c7\" stroke=\"black\" />
	</g>
	<circle r=\"35\" fill=\"black\" stroke=\"black\" />
	<circle cx=\"-15\" cy=\"-10\" r=\"9\" fill=\"url(#radial)\" />
      </g>
    </svg>
  </div>\n";

	// Output the intro panel

	$intro = $custom['intro'][0];
	$start = plugins_url('/images/start.png', __FILE__);

	echo "\t<div id=\"first\" style=\"text-align: center\">
    <h3>$intro</h3>
    <input type=\"image\" src=\"$start\" alt=\"Start\" width=\"164\" class=\"start\" id=\"start\" />
  </div>\n";

	$questions = $custom['question'];
	$left_texts = $custom['left'];
	$centre_texts = $custom['centre'];
	$right_texts = $custom['right'];

	$length = count($questions);

	$next = plugins_url('/images/next.png', __FILE__);
	$results = plugins_url('/images/results.png', __FILE__);

	$count = 1;

	// Output the question panels

	foreach ($questions as $key => $question)
	{
	    $left = $left_texts[$key]? $left_texts[$key]: "No";
	    $centre = $centre_texts[$key];
	    $right = $right_texts[$key]? $right_texts[$key]: "Yes";

	    echo "\t<div id=\"panel$count\" style=\"display: none; text-align: center\">
    <h3>$question</h3>
    <div class=\"slider\" id=\"value$count\"></div>
    <div>\n";

	    if (($centre) && (strcmp($centre, '-') != 0))
		echo "\t<div class=\"left\"
	   style=\"text-align: left; float: left; width: 33%\">$left</div>
      <div class=\"centre\" 
	  style=\"float: left; width: 34%\">$centre</div>
      <div class=\"right\"
	   style=\"text-align: right; float: left; width: 33%\">$right</div>
    </div>
    <div style=\"width: 100%; clear: left; padding: 20px 0 0;\">\n";

	    else
		echo "\t<div class=\"left\"
	   style=\"text-align: left; float: left; width: 50%\">$left</div>
      <div class=\"right\"
	   style=\"text-align: right; float: left; width: 50%\">$right</div>
    </div>
    <div style=\"width: 100%; clear: left; padding: 24px 0 0;\">\n";

	    if ($key < $length - 1)
		echo "\t<input type=\"image\" src=\"$next\" alt=\"Next\" width=\"164\" class=\"next\" id=\"next$count\" />
    </div>
  </div>\n";

	    else
		echo "\t<input type=\"image\" src=\"$results\" alt=\"Get Results\" width=\"265\" class=\"result\" id=\"result\" />
    </div>
  </div>\n";

	    $count++;
	}

	$again = plugins_url('/images/again.png', __FILE__);
	$facebook = plugins_url('/images/facebook_box_blue.png', __FILE__);

	// Output the results panel

	echo "\t<div id=\"last\" style=\"display: none; text-align: center;\">
    <h3 id=\"answer\"></h3>
    <input type=\"image\" src=\"$again\" alt=\"Again\" width=\"164\" class=\"again\" id=\"again\" />\n";

	if ($custom['facebook-appid'])
	    echo "    <input type=\"image\" src=\"$facebook\" alt=\"Facebook\" width=\"90\" class=\"facebook\" id=\"facebook\" />
  </div>\n";

	else
	    echo "  </div>\n";

	echo "</div>\n";

	// Debug output if defined

	if ($custom['debug'])
	    the_meta();
    }

    // Show this message if no questions defined

    else
	echo "<p>No whatever-o-meter questions defined, you need to define some custom fields.</p>";

    // Return the output

    return ob_get_clean();

}

// Output javascript structure defining the custom variables for the
// whatever-o-meter.js script to use

function whatever_footer() {

    $custom = get_post_custom();

    // Check the results are defined, not much point else

    if ($custom['result']) {

	// Get the variables

	// $weights = 1;

	if ($custom['weights'])
	    $weights = explode(',', $custom['weights'][0]);

	foreach ($weights as $key => $weight)
	    $weights[$key] = floatval($weight);

	$results = $custom['result'];

	if ($custom['duration'])
	    $duration = intval($custom['duration'][0]);

	if ($custom['easing'])
	    $easing = $custom['easing'][0];

	if ($custom['facebook-appid'])
	    $appid = $custom['facebook-appid'][0];

	if ($custom['facebook-caption'])
	    $caption= $custom['facebook-caption'][0];

	if ($custom['facebook-description'])
	    $description = $custom['facebook-description'][0];

	if ($custom['facebook-picture'])
	    $picture = $custom['facebook-picture'][0];

	$json_array= array('weights' => $weights, 'results' => $results,
			   'duration' => $duration, 'easing' => $easing,
			   'appid' => $appid, 'caption' => $caption,
			   'description' => $description,
			   'picture' => $picture);

	$json = json_encode($json_array);

	// Output the javascript

	echo "<!-- whatever-o-meter javascript data -->
<script type=\"text/javascript\">
    var whatever_data = $json;
</script>\n\n";
    }

    // Check for facebook

    if ($custom['facebook-appid']) {

	// Get the appid

	$appid = $custom['facebook-appid'][0];

	// Output the code

	echo "<!-- whatever-o-meter facebook -->
<div id=\"fb-root\"></div>\n";

    }
}
