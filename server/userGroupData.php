<?php 
	require_once('lib/facebook/facebook.php');
		$config = array(
		'appId' => '465523613530810',
		'secret' => 'f4a5f0f3b6fd87ad38ba7cb9ea4a95ba',
		);
	$facebook = new Facebook($config);
	$user_id = $facebook->getUser();
	if($user_id!=0){
		$access_token = $facebook->getAccessToken();
		$fql = 'SELECT uid, name, sex FROM user WHERE is_app_user = 1 AND uid IN (SELECT uid2 FROM friend WHERE uid1 = me())';
		$ret_obj = $facebook->api(array(
                		'method' => 'fql.query',
		    	        'access_token' => $access_token,
        	          	'query' => $fql,
    	            ));
		$i=0;
		$data = $ret_obj;
	}
?>
<!DOCTYPE html>
<html lang="en">
  	<head>
    	<title>User Group Data Display</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    	<link href="css/style.css" rel="stylesheet">
  	</head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <ul class="nav navbar-nav">
            <li><a href="dbDisplay.php">Database Display</a></li>
            <li><a href="contentCategorization.php">Content Categorization</a></li>
            <li><a href="userGroup.php">User Social Group</a></li>
            <li class="active"><a href="userGroupData.php">User Group's Data</a></li>
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
        			echo "<h2>" . $user_profile['name'] ."'s Group Data</h2>";
    			}
    		?>
    	</div>
        <table class="table table-bordered table-striped table-condensed the-table">
            <thead>
                <tr>
                  <th>Facebook User Id</th>
                  <th>User Name</th>
                  <th>Gender</th>
                  <th>Data URL</th>
                </tr>
            </thead>
            <tbody>
            	<?php
            		while ($data[$i]) {
            	?>
                <tr>
  					<td><?php echo $data[$i][uid]; ?></td>
  					<td><?php echo $data[$i][name]; ?></td>
  					<td><?php echo $data[$i][sex]; ?></td>
            <td><a href="userData.php?uid=<?php echo $data[$i][uid]; ?>">userData.php?uid=<?php echo $data[$i][uid]; ?></a></td>
				</tr>
				<?php $i++; } ?>
            </tbody>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
