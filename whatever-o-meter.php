<?php
/**
 * Plugin Name: Whatever-o-meter
 * Plugin URI: http://billthefarmer.users.sourceforge.net/wordpress/whatever-o-meter
 * Description: Lets you create a whatever-o-meter using your data.
 * Version: 0.3
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

add_action('wp_enqueue_scripts', 'whatever_enqueue_scripts', 11);

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

	wp_enqueue_script('jquery-scale',
			  plugins_url('/js/jquery.scale.min.js', __FILE__),
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

	add_action('wp_footer', 'whatever_footer', 11);
    }
}

// Add the content if the shortcode is found.

function whatever_shortcode($atts) {

    // Buffer the output

    ob_start();

    $custom = get_post_custom();

    // Check there's at least one question defined, no point else

    if ($custom['question']) {

	// Add facebook like button

	if ($custom['fb-appid'])
	    echo "<!-- whatever-o-meter facebook like -->
<div class=\"fb-like\"
     style=\"text-align: center; width: 100%;\"
     data-layout=\"standard\"
     data-action=\"like\"
     data-show-faces=\"true\"
     data-share=\"true\">
</div>
<br />
<br />\n";

	// Output the tacho dial
?>
<!-- whatever-o-meter html -->
<div id="whatever-o-meter" style="margin: 0 auto; width: 560px;">
  <!-- SVG Dial -->
  <div>
    <svg id="tacho-dial" width="560" height="560">
      <!-- Gradients for highlights -->
      <defs>
	<radialgradient id="black" r="50%">
	  <stop offset="0%" stop-color="white" />
	  <stop offset="100%" stop-color="#1d1d1b" />
	</radialgradient>
	<radialgradient id="left" r="50%">
	  <stop offset="0%" stop-color="white" />
	  <stop class="left" offset="100%" stop-color="firebrick" />
	</radialgradient>
	<radialgradient id="centre" r="50%">
	  <stop offset="0%" stop-color="white" />
	  <stop class="centre" offset="100%" stop-color="goldenrod" />
	</radialgradient>
	<radialgradient id="right" r="50%">
	  <stop offset="0%" stop-color="white" />
	  <stop class="right" offset="100%" stop-color="greenyellow" />
	</radialgradient>
      </defs>
      <!-- Transform to the centre -->
      <g id="tacho-group" transform="translate(280,280)">
	<!-- Outer black ring -->
	<circle r="280" stroke="#1d1d1b" />
	<circle r="276" stroke="white" stroke-width="2" />
	<circle r="256" stroke="white" stroke-width="2" />
	<circle r="250" stroke="#9a9a9a" fill="#9a9a9a" />
	<!-- Coloured segments -->
	<path class="segment outer"
	      d="M0,0 L-147,202 A250,250 1 0,1 -250,0 Z"
	      stroke="firebrick" fill="firebrick" />
	<path class="segment outer"
	      d="M0,0 L-250,0 A250,250 1 0,1 -125,-216 Z"
	      stroke="darkorange" fill="darkorange" />
	<path class="segment outer"
	      d="M0,0 L-125,-216 A250,250 1 0,1 125,-216 Z"
	      stroke="goldenrod" fill="goldenrod" />
	<path class="segment outer"
	      d="M0,0 L125,-216 A250,250 1 0,1 250,0 Z"
	      stroke="gold" fill="gold" />
	<path class="segment outer"
	      d="M0,0 L250,0 A250,250 1 0,1 147,202 Z"
	      stroke="greenyellow" fill="greenyellow" />
	<!-- Inner black ring -->
	<circle r="148" stroke="#1d1d1b" />
	<circle r="120" stroke="white" stroke-width="6" />
	<circle r="112" stroke="#9a9a9a" fill="#9a9a9a" />
	<!-- Ticks -->
	<line x1="-128" y1="0" x2="-144" y2="0"
	      stroke="white" stroke-width="5" />
	<line x1="0" y1="-128" x2="0" y2="-144"
	      stroke="white" stroke-width="5" />
	<line x1="128" y1="0" x2="144" y2="0"
	      stroke="white" stroke-width="5" />
	<line x1="0" y1="128" x2="0" y2="144"
	      stroke="white" stroke-width="2" />
	<line x1="-90" y1="90" x2="-101" y2="101"
	      stroke="white" stroke-width="5" />
	<line x1="-90" y1="-90" x2="-101" y2="-101"
	      stroke="white" stroke-width="5" />
	<line x1="90" y1="-90" x2="101" y2="-101"
	      stroke="white" stroke-width="5" />
	<line x1="90" y1="90" x2="101" y2="101"
	      stroke="white" stroke-width="5" />
	<line x1="-118" y1="49" x2="-132" y2="55"
	      stroke="white" stroke-width="2" />
	<line x1="-118" y1="-49" x2="-132" y2="-55"
	      stroke="white" stroke-width="2" />
	<line x1="118" y1="49" x2="132" y2="55"
	      stroke="white" stroke-width="2" />
	<line x1="118" y1="-49" x2="132" y2="-55"
	      stroke="white" stroke-width="2" />
	<line x1="-49" y1="-118" x2="-55" y2="-132"
	      stroke="white" stroke-width="2" />
	<line x1="49" y1="-118" x2="55" y2="-132"
	      stroke="white" stroke-width="2" />
	<!-- Inner segments -->
	<path class="segment inner"
	      d="M0,0 L-65,91 A112,112 1 0,1 -112,0 Z"
	      stroke="firebrick" fill="firebrick" />
	<path class="segment inner"
	      d="M0,0 L-112,0 A112,112 1 0,1 -56,-97 Z"
	      stroke="darkorange" fill="darkorange" />
	<path class="segment inner"
	      d="M0,0 L-56,-97 A112,112 1 0,1 56,-97 Z"
	      stroke="goldenrod" fill="goldenrod" />
	<path class="segment inner"
	      d="M0,0 L56,-97 A112,112 1 0,1 112,0 Z"
	      stroke="gold" fill="gold" />
	<path class="segment inner"
	      d="M0,0 L112,0 A112,112 1 0,1 65,91 Z"
	      stroke="greenyellow" fill="greenyellow" />
	<!-- Warning lights -->
	<circle class="left" cx="-62" cy="190" r="13" stroke="#1d1d1b"
		stroke-width="3" fill="firebrick" />
	<circle cx="-66" cy="186" r="5" fill="url(#left)" />
	<circle class="centre" cx="0" cy="200" r="13" stroke="#1d1d1b"
		stroke-width="3" fill="goldenrod" />
	<circle cx="-4" cy="195" r="5" fill="url(#centre)" />
	<circle class="right" cx="62" cy="190" r="13" stroke="#1d1d1b"
		stroke-width="3" fill="greenyellow" />
	<circle cx="58" cy="186" r="5" fill="url(#right)" />
	<!-- Od-o-meter -->
	<rect x="-56" y="50" width="16" height="24"
	      stroke="#3d3d3d" fill="#3d3d3d" />
	<rect x="-32" y="50" width="16" height="24"
	      stroke="#3d3d3d" fill="#3d3d3d" />
	<rect x="-8" y="50" width="16" height="24"
	      stroke="#3d3d3d" fill="#3d3d3d" />
	<rect x="16" y="50" width="16" height="24"
	      stroke="#3d3d3d" fill="#3d3d3d" />
	<rect x="40" y="50" width="16" height="24"
	      stroke="#3d3d3d" fill="#3d3d3d" />
	<!-- Od-o-meter digits -->
	<text id="digit-1" class="digit" x="-55" y="70" font-size="24"
	      stroke="white" fill="white">0</text>
	<text id="digit-2" class="digit" x="-31" y="70" font-size="24"
	      stroke="white" fill="white">0</text>
	<text id="digit-3" class="digit" x="-7" y="70" font-size="24"
	      stroke="white" fill="white">0</text>
	<text id="digit-4" class="digit" x="17" y="70" font-size="24"
	      stroke="white" fill="white">0</text>
	<text id="digit-5" class="digit" x="41" y="70" font-size="24"
	      stroke="white" fill="white">0</text>
	<!-- Pointer -->
	<g id="pointer">
	  <path d="M0,64 L8,56 L3,-196 L0,-200 L-3,-196 L-8,56 Z"
		fill="#ede4c7" stroke="#1d1d1b" />
	</g>
	<!-- Pointer dome -->
	<circle r="36" fill="#1d1d1b" stroke="#1d1d1b" />
	<circle cx="-16" cy="-10" r="10" fill="url(#black)" />
      </g>
    </svg>
  </div>
  <br />
<?php

	// Output the intro panel

	$intro = $custom['intro'][0];

	echo "\t<div id=\"first\" style=\"text-align: center\">
    <h3>$intro</h3>
    <input type=\"button\" value=\"Start\" class=\"start\" id=\"start\" />
  </div>\n";

	$questions = $custom['question'];
	$left_texts = $custom['left'];
	$centre_texts = $custom['centre'];
	$right_texts = $custom['right'];

	$last = end($questions);
	$last = each($questions);

	$count = 1;

	// Output the question panels

	foreach ($questions as $key => $question)
	{
	    $left = $left_texts[$key]? $left_texts[$key]: "No";
	    $centre = $centre_texts[$key];
	    $right = $right_texts[$key]? $right_texts[$key]: "Yes";

	    echo "\t<div id=\"panel-$count\" style=\"display: none; text-align: center\">
    <h3>$question</h3>
    <div class=\"slider\" id=\"value-$count\"></div>
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

	    if ($key < $last['key'])
		echo "\t<input type=\"button\" value=\"Next\" class=\"next\" id=\"next$count\" />
    </div>
  </div>\n";

	    else
		echo "\t<input type=\"button\" value=\"Get Results\" class=\"result\" id=\"result\" />
    </div>
  </div>\n";

	    $count++;
	}

	$facebook = plugins_url('/images/facebook.png', __FILE__);

	$url = $custom['more'][0];

	// Output the results panel

	echo "\t<div id=\"last\" style=\"display: none; text-align: center;\">
    <h3 id=\"answer\"></h3>
    <input type=\"button\" value=\"Again\" class=\"again\" id=\"again\" />\n";

	if ($custom['fb-appid'])
	    echo "    <input type=\"image\" src=\"$facebook\" alt=\"Facebook\" width=\"83\" class=\"facebook\" id=\"facebook\" />\n";

	if ($custom['more'])
	    echo "    <br />
    <a href=\"$url\"><input type=\"button\" value=\"Find Out More\" id=\"more\" class=\"more\" /></a>\n";

	echo "  </div>
</div>\n";

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

    if ($custom['result'])
    {
	// Create the array

	$json_array = array('results' => $custom['result']);

	// Get the variables

	if ($custom['colours'])
	    $json_array['colours'] = explode(',', $custom['colours'][0]);

	if ($custom['weights'])
	{
	    $weights = explode(',', $custom['weights'][0]);

	    foreach ($weights as $key => $weight)
		$weights[$key] = floatval($weight);

	    $json_array['weights'] = $weights;
	}

	if ($custom['duration'])
	    $json_array['duration'] = intval($custom['duration'][0]);

	if ($custom['easing'])
	    $json_array['easing'] = $custom['easing'][0];

	if ($custom['fb-appid'])
	    $json_array['appid'] = $custom['fb-appid'][0];

	if ($custom['fb-caption'])
	    $json_array['caption'] = $custom['fb-caption'][0];

	if ($custom['fb-description'])
	    $json_array['description'] = $custom['fb-description'][0];

	if ($custom['fb-picture'])
	    $json_array['picture'] = $custom['fb-picture'][0];

	$json = json_encode($json_array);

	// Output the javascript

	echo "<!-- whatever-o-meter javascript data -->
<script type=\"text/javascript\">
    var whatever_data = $json;
</script>\n\n";
    }

    // Check for facebook

    if ($custom['fb-appid']) {

	// Get the appid

	$appid = $custom['fb-appid'][0];

	// Output the code

	echo "<!-- whatever-o-meter facebook -->
<div id=\"fb-root\"></div>\n";

    }
}
