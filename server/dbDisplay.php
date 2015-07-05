<?php 
  include 'dbConnect.php';
  $query = "SELECT * FROM browserSessionInfo ORDER BY urlOpeningTime DESC LIMIT 30";
  $query = mysql_query($query);
  $i=1;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Database Display</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>
    <div id="fb-root"></div>
    <script>
      var name="";
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
          testAPI();
        } else if (response.status === 'not_authorized') {
          window.location = "http://www.spgoyal.in/projects/snwccb/login.php";
        } else {
          window.location = "http://www.spgoyal.in/projects/snwccb/login.php";
        }
      });
      };

      // Load the SDK asynchronously
      (function(d){
       var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement('script'); js.id = id; js.async = true;
       js.src = "//connect.facebook.net/en_US/all.js";
       ref.parentNode.insertBefore(js, ref);
      }(document));

      // Here we run a very simple test of the Graph API after login is successful. 
      // This testAPI() function is only called in those cases. 
      function testAPI() {
        //console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
          re = response;
          name = response.name;
          id = response.id;
          $("#userDetails").append("<h2>Welcome "+name+"!!</h2> Your id is: "+ id);

        });
      }
      //testAPI();
    </script>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <ul class="nav navbar-nav">
            <li class="active"><a href="dbDisplay.php">Database Display</a></li>
            <li><a href="contentCategorization.php">Content Categorization</a></li>
            <li><a href="userGroup.php">User Social Group</a></li>
            <li><a href="userGroupData.php">User Group's Data</a></li>
            <li><a href="customContentAggregation.php">Content Aggregation</a></li>
          </ul>
        </div>          
      </div>
    </div>

    <div class="container">
        <div id="userDetails"></div>
        <table class="table table-bordered table-striped table-condensed the-table">
              <thead>
                <tr>
                  <th class="s_col">S. no.</th>
                  <th class="s_col">Facebook User Id</th>
                  <th class="s_col">Session Id</th>
                  <th class="s_col">Tab Id</th>
                  <th class="l_col">URL</th>
                  <th class="m_col">Page Title</th>
                  <th class="s_col">Visit Count</th>
                  <th class="s_col">Typed Count</th>
                  <th class="s_col">Transition Type</th>
                  <th>URL opening time</th>
                  <th>URL closing time</th>
                </tr>
              </thead>
              <tbody>

                <?php 
                  while($result = mysql_fetch_array($query)) { 
                ?>
                
                <tr>
                  <td class="s_col"><?php echo $i; $i++; ?></td>
                  <td class="s_col"><?php echo $result['userId']; ?></td>
                  <td class="s_col"><?php echo $result['browserSessionId']; ?></td>
                  <td class="s_col"><?php echo $result['tabId']; ?></td>
                  <td class="l_col"><?php echo $result['tabUrl']; ?></td>
                  <td class="m_col"><?php echo $result['tabTitle']; ?></td>
                  <td class="s_col"><?php echo $result['visitCount']; ?></td>
                  <td class="s_col"><?php echo $result['typedCount']; ?></td>
                  <td class="s_col"><?php echo $result['transitionType']; ?></td>
                  <td><?php echo $result['urlOpeningTime']; ?></td>
                  <td><?php echo $result['urlClosingTime']; ?></td>
                </tr>
                <?php  $x=$x-1; } ?>
              </tbody>
        </table>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
