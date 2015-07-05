<html>
<head>
  <title>Database Display</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
    FB.init({
      appId      : '465523613530810', 
      channelUrl : '//www.spgoyal.in/projects/snwccb/lib/facebook/channel.html', 
      status     : true, 
      cookie     : true, 
      xfbml      : true  
    });
    FB.Event.subscribe('auth.authResponseChange', function(response) {
      if (response.status === 'connected') {
        window.location = "http://www.spgoyal.in/projects/snwccb/dbDisplay.php";
      } else if (response.status === 'not_authorized') {
        FB.login();
      } else {
        FB.login();
      }
    });
    };
  
    (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
    }(document));
  
    function testAPI() {
      console.log('Welcome!  Fetching your information.... ');
      FB.api('/me', function(response) {
        console.log('Good to see you, ' + response.name + '.');
      });
    }
  </script>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        
      </div>
  </div>
  <div class="container">
    	<fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>