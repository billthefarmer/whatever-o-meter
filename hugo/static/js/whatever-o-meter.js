// whatever-o-meter.js
//
// Version: 0.5
// Author: Bill Farmer
// Author http://billthefarmer.users.sourceforge.net

// Created by Bill Farmer
// Licensed under the LGPL.
// Copyright (C) 2013 Bill Farmer

jQuery(document).ready(function($) {

    // WordPress uses JQuery in script safe mode or whatever it's called
    // Inside of this function, $() will work as an alias for jQuery()
    // and other libraries also using $ will not be accessible under
    // this shortcut

    // Calculate scaling factors for pointer

    var range = 150;
    var scale = range / 20;

    // Get data from the PHP script which originated as custom fields
    // on the page

    try
    {
        var results = whatever_data["results"];
        var weights = whatever_data["weights"];
        var easing = whatever_data["easing"];
        var duration = whatever_data["duration"];
        var colours = whatever_data["colours"];
    }

    // Return if exception

    catch (e)
    {
        return;
    }

    // Centre the header - doesn't work in hugo

    // $('h1.entry-title').css('text-align', 'center');

    // The pointer is drawn pointing to 12 o'clock, Rotate it to the
    // start position

    $("#pointer").css("rotate", -range);

    // Set up the sliders

    $("div.slider").slider({range: "min", max: 20, min: -20,
			    value: 0, step: 10});

    // Set up the buttons

    $("input[type=button]").each(function() {

        $(this).button();
        $(this).wrap('<div style="display: inline-block; border: solid palegoldenrod 8px; border-radius: 24px; margin: 4px;"></div>');
        $(this).css("border", "solid white 4px");
        $(this).css("border-radius", "16px");
        $(this).css("background", "palegoldenrod");
        $(this).css("text-align", "center");
        $(this).css("vertical-align", "middle");
        $(this).css("padding", "4px 24px");
        $(this).css("font-family", "sans-serif");
        $(this).css("font-size", "44px");
        $(this).css("margin", "0");
        $(this).css("cursor", "default");

    });

    $("input[type=image]").css("vertical-align", "middle");

    // Handle colours

    if (colours != null)
    {
        var length = colours.length;

        // Background

        $("circle").attr("fill", colours[0 % length]);

        // Ticks

        $(".ticks").attr("stroke", colours[1 % length]);

        // Digits

        $(".digits").attr("stroke", colours[2 % length]);
        $(".digits").attr("fill", colours[2 % length]);

        // Pointer

        $(".pointer").attr("stroke", colours[3 % length]);
        $(".pointer").attr("fill", colours[3 % length]);

        // Buttons

        $("input[type=button]").each(function() {

            $(this).css("background", colours[4 % length]);
            $(this).parent().css("border-color", colours[4 % length]);

        });
    }

    // Define the function variables

    var panel = 0;
    var value = 0;
    var index = 0;
    var total = 0;

    // Process the start button

    $("#start").click(function() {
        $("#first").slideUp();

        panel++;

        $("#panel-" + panel).slideDown();
    });

    // Process the next buttons

    $("input.next").click(function() {

        $("#panel-" + panel).slideUp();

        // Check if weights are defined

        if (weights == null)
        {
            value += $("#value-" + panel).slider("value");
            total++;
        }

        else
        {
            value += $("#value-" + panel).slider("value") *
	        weights[index];
            total += +weights[index];
        }

        panel++;
        index++;

        $("#panel-" + panel).slideDown();
    });

    // Place to put the final answers (42)

    var answer;
    var digits;

    // Process the result button

    $("#result").click(function() {

        // Check if weights are defined

        if (weights == null)
        {
            value += $("#value-" + panel).slider("value");
            total++;
        }

        else
        {
            value += $("#value-" + panel).slider("value") *
	        weights[index];
            total += +weights[index];
        }

        // Get the value divided by the total weights

        value /= total;

        // Animate the pointer

        $("#pointer").animate({rotate: value * scale},
			      (duration == null)? 2000: duration,
			      (easing == null)? "easeOutQuad": easing);

        // Calculate the digits for the od-o-meter

        digits = ((value + 20) * 2.5) | 0;
        // window.alert(value + "," + digits);

        if (digits == 100) {
            $("#digit-1").empty();
            $("#digit-1").append("1");
        }

        else {
            $("#digit-2").empty();
            $("#digit-3").empty();

            $("#digit-2").append((digits / 10) | 0);
            $("#digit-3").append(digits % 10);
        }

        // Calculate the value for selecting the result text. There is
        // a potential array bounds error here, as without some
        // adjustment the array index will be 0-size, and we want
        // 0-size - 1. So decrement the value if it's not zero.

        value += 20;

        if (value > 0)
            value--;

        var result = ((value * results.length) / 40) | 0;
        
        // Select the result text

        $("#answer").empty();
        $("#answer").append(results[result].replace("%d", digits));

        // Save it for facebook

        answer = results[result].replace("%d", digits);

        $("#panel-" + panel).slideUp();
        $("#last").slideDown();
    });

    // Process the again button

    $("#again").click(function() {

        // Reset the sliders and digits

        $("div.slider").slider("value", 0);
        $("text.digit").empty();
        $("text.digit").append("0");

        // Wind the pointer back

        $("#pointer").animate({rotate: -range},
			      2000, "easeOutQuad");
        $("#last").slideUp();
        $("#first").slideDown();

        // Reset the variables

        value = 0;
        panel = 0;
        total = 0;
        index = 0;
    });
});
