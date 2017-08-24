$(document).ready(function() {
  $.ajaxSetup({ cache: true });
  $.getScript('//connect.facebook.net/en_UK/all.js', function(){
    FB.init({
      appId: 'YOUR_APP_ID',
    });     
    $('#loginbutton,#feedbutton').removeAttr('disabled');
    FB.getLoginStatus(updateStatusCallback);
  });
});

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : 'YOUR_APP_ID', // App ID from the app dashboard
      status     : true,          // Check Facebook Login status
      xfbml      : true           // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
  };

  // Load the SDK asynchronously
  (function(){
     // If we've already installed the SDK, we're done
     if (document.getElementById('facebook-jssdk')) {return;}

     // Get the first script element, which we'll use to find the parent node
     var firstScriptElement = document.getElementsByTagName('script')[0];

     // Create a new script element and set its id
     var facebookJS = document.createElement('script'); 
     facebookJS.id = 'facebook-jssdk';

     // Set the new script's source to the source of the Facebook JS SDK
     facebookJS.src = '//connect.facebook.net/en_US/all.js';

     // Insert the Facebook JS SDK into the DOM
     firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
   }());
</script>

FB.ui(
    {
	method: 'feed',
	name: 'Facebook Dialogs',
	link: 'https://developers.facebook.com/docs/dialogs/',
	picture: 'http://fbrell.com/f8.jpg',
	caption: 'Reference Documentation',
	description: 'Dialogs provide a simple, consistent interface for applications to interface with users.'
    },
    function(response) {
	if (response && response.post_id) {
	    alert('Post was published.');
	} else {
	    alert('Post was not published.');
	}
    }
);

FB.ui({
    method: 'feed',
    link: 'https://developers.facebook.com/docs/dialogs/',
    caption: 'An example caption',
    description: 'description'
}, function(response){});

https://developers.facebook.com/docs/reference/dialogs/feed
