<?php
/**
 * Plugin Name: Whatever-o-meter
 * Plugin URI: http://billthefarmer.users.sourceforge.net/wordpress/whatever-o-meter
 * Description: Lets you create a whatever-o-meter using your data.
 * Version: 0.5
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

	// Add addthis buttons

	$addthis = $custom['addthis'][0];

	if (strcmp($addthis, 'above') == 0)
	{
	    $url = get_permalink();
	    $title = get_the_title();

	    echo "<!-- whatever-o-meter addthis toolbox -->
<div class=\"addthis_toolbox addthis_default_style\"
     style=\"margin: 0 auto; width: 480px;\"
     addthis:url='$url'
     addthis:title='$title'>
  <a class=\"addthis_button_facebook_like\"></a>
  <a class=\"addthis_button_tweet\"></a>
  <a class=\"addthis_button_pinterest_pinit\"></a>
  <a class=\"addthis_button_google_plusone\" g:plusone:size=\"medium\"></a>
  <a class=\"addthis_counter addthis_pill_style\"></a>
</div>\n";
	}

	// Add facebook like button

	$like = $custom['fb-like'][0];

	if (strcmp($like, 'above') == 0)
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

	echo "\n<!-- whatever-o-meter html -->
<div id=\"whatever-o-meter\" style=\"margin: 0 auto; width: 560px;\">
  <!-- SVG dial -->
  <svg id=\"tacho-dial\" width=\"560\" height=\"560\">
    <g  transform=\"translate(280,280)\">
      <circle class=\"background\" r=\"280\" fill=\"palegoldenrod\" />
      <circle class=\"ticks\" r=\"272\" stroke=\"black\" fill=\"palegoldenrod\" stroke-width=\"6\" />
      <circle class=\"ticks\" r=\"140\" stroke=\"black\" fill=\"palegoldenrod\" stroke-width=\"6\" />
      <line class=\"ticks\" x1=\"0\" y1=\"-212\" x2=\"0\" y2=\"-272\"
	    stroke=\"black\" stroke-width=\"6\" />
      <line class=\"ticks\" x1=\"0\" y1=\"-108\" x2=\"0\" y2=\"-138\"
	    stroke=\"black\" stroke-width=\"4\" />\n";

	for ($i = 28; $i <= 140; $i += 28)
	{
	    echo "<g transform=\"rotate($i)\">\n";
	    echo "<line class=\"ticks\" x1=\"0\" y1=\"-212\" x2=\"0\" y2=\"-272\"
		stroke=\"black\" stroke-width=\"6\" />\n";
	    echo "<line class=\"ticks\" x1=\"0\" y1=\"-108\" x2=\"0\" y2=\"-138\"
		stroke=\"black\" stroke-width=\"4\" />\n";
	    for ($j = 5.6; $j < 28; $j += 5.6)
	    {
		echo "<line class=\"ticks\" x1=\"0\" y1=\"-242\" x2=\"0\" y2=\"-272\"
		  stroke=\"black\" stroke-width=\"2\" transform=\"rotate(-$j)\" />\n";
	    }
	    echo "</g>\n";

	    echo "<g transform=\"rotate(-$i)\">\n";
	    echo "<line class=\"ticks\" x1=\"0\" y1=\"-212\" x2=\"0\" y2=\"-272\"
		stroke=\"black\" stroke-width=\"6\" />\n";
	    echo "<line class=\"ticks\" x1=\"0\" y1=\"-108\" x2=\"0\" y2=\"-138\"
		stroke=\"black\" stroke-width=\"4\" />\n";
	    for ($j = 5.6; $j < 28; $j += 5.6)
	    {
		echo "<line class=\"ticks\" x1=\"0\" y1=\"-242\" x2=\"0\" y2=\"-272\"
		  stroke=\"black\" stroke-width=\"2\" transform=\"rotate($j)\" />\n";
	    }
	    echo "</g>\n";
	}

	// Digits

	echo "<!-- Od-o-meter digits -->
          <text class=\"digits\" id=\"digit-1\" x=\"-72\" y=\"82\" font-size=\"48\"
		stroke=\"black\" fill=\"black\">0</text>
	  <text class=\"digits\" id=\"digit-2\" x=\"-42\" y=\"82\" font-size=\"48\"
		stroke=\"black\" fill=\"black\">0</text>
	  <text class=\"digits\" id=\"digit-3\" x=\"-12\" y=\"82\" font-size=\"48\"
		stroke=\"black\" fill=\"black\">0</text>
	  <text class=\"digits\" id=\"digit-4\" x=\"18\" y=\"82\" font-size=\"48\"
		stroke=\"black\" fill=\"black\">0</text>
	  <text class=\"digits\" id=\"digit-5\" x=\"48\" y=\"82\" font-size=\"48\"
		stroke=\"black\" fill=\"black\">0</text>\n";

	// Pointer

	echo "<!-- Pointer -->
          <path d=\"M0,64 L8,56 L3,-196 L0,-200 L-3,-196 L-8,56 Z\"
		  fill=\"black\" class=\"pointer\" id=\"pointer\" />
	  <circle class=\"pointer\" r=\"20\" fill=\"black\" />
      </g>
  </svg>\n";

	// Output the intro panel

	$intro = $custom['intro'][0];

	echo "\t<div id=\"first\" style=\"text-align: center\">
    <h3>$intro</h3>
    <input type=\"button\" value=\"Start\" class=\"start\" id=\"start\" />
  </div>\n";

	$questions = $custom['question'];
	$left_texts = $custom['left'];

	if ($custom['center'])
	    $centre_texts = $custom['center'];

	else
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

	// Add addthis toolbox below

	if (strcmp($addthis, 'below') == 0)
	{
	    $url = get_permalink();
	    $title = get_the_title();

	    echo "<!-- whatever-o-meter addthis toolbox -->
<div class=\"addthis_toolbox addthis_default_style\"
     style=\"margin: 0 auto; width: 480px;\"
     addthis:url='$url'
     addthis:title='$title'>
  <a class=\"addthis_button_facebook_like\"></a>
  <a class=\"addthis_button_tweet\"></a>
  <a class=\"addthis_button_pinterest_pinit\"></a>
  <a class=\"addthis_button_google_plusone\" g:plusone:size=\"medium\"></a>
  <a class=\"addthis_counter addthis_pill_style\"></a>
</div>\n";
	}

	// Facebook like below

	if (strcmp($like, 'below') == 0)
	    echo "<!-- whatever-o-meter facebook like -->
<br />
<div class=\"fb-like\"
     style=\"text-align: center; width: 100%;\"
     data-layout=\"standard\"
     data-action=\"like\"
     data-show-faces=\"true\"
     data-share=\"true\">
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

	if ($custom['colors'])
	    $json_array['colours'] = explode(',', $custom['colors'][0]);

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

	// Output the code

	echo "<!-- whatever-o-meter facebook -->
<div id=\"fb-root\"></div>\n";

    }
}
