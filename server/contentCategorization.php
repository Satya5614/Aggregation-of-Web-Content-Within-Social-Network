<?php 
  include("lib/textCategorization/phptextcat.class.php");
  include('dbConnect.php');
  require_once('lib/facebook/facebook.php');
  $config = array(
    'appId' => '465523613530810',
    'secret' => 'f4a5f0f3b6fd87ad38ba7cb9ea4a95ba',
  );
  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
  $analyser = new PhpTextCat('business_finance', 'entertainment', 'knowledge', 'lifestyle', 'news', 'sports');
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
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <ul class="nav navbar-nav">
            <li><a href="dbDisplay.php">Database Display</a></li>
            <li class="active"><a href="contentCategorization.php">Content Categorization</a></li>
            <li><a href="userGroup.php">User Social Group</a></li>
            <li><a href="userGroupData.php">User Group's Data</a></li>
            <li><a href="customContentAggregation.php">Content Aggregation</a></li>
          </ul>
        </div>
          
      </div>
    </div>

    <div class="container">
      <div id="userDetails">
        <?php
          if($user_id){
            $user_profile = $facebook->api('/me','GET');
              echo "<h2>" . $user_profile['name'] ."'s Categorised Data</h2>";
          }
        ?>
      </div>
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
                  <th>Content Category</th>
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
                  <td><?php echo $result['category']; ?></td>
                  <td><?php echo $result['urlOpeningTime']; ?></td>
                  <td><?php echo $result['urlClosingTime']; ?></td>
                </tr>
                <?php  $x=$x-1; } ?>
              </tbody>
        </table>
    </div><!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
