<?php 
  include("lib/textCategorization/phptextcat.class.php");
  $analyser = new PhpTextCat('business_finance', 'entertainment', 'knowledge', 'lifestyle', 'news', 'sports');
  include 'dbConnect.php';
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
            <li><a href="contentCategorization.php">Content Categorization</a></li>
            <li><a href="userGroup.php">User Social Group</a></li>
            <li><a href="userGroupData.php">User Group's Data</a></li>
            <li class="active"><a href="customContentAggregation.php">Content Aggregation</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container">
        <div id="userDetails">
        </div>
        <form method="post" action="" class="form-inline">
            Users:
            <select class="input-medium" name="users">
              <option value="0">All Users</option>
              <option value="1">My Group Users</option>
              <?php while ($data[$i]) { ?>
                <option value="<?php echo $data[$i][uid]; ?>"><?php echo $data[$i][name]; ?></option>
              <?php $i++; } $i = 0; ?>
            </select>&nbsp;&nbsp;
            Gender:
            <select class="input-medium" name="gender">
              <option value="">All</option>
              <option value="female">Female</option>
              <option value="male">Male</option>
            </select>&nbsp;&nbsp;
            Age Group:
            <select class="input-medium" name="age_group">
              <option value="0">All age groups</option>
              <option value="13">13 - 17</option>
              <option value="18">18 - 20</option>
              <option value="21">21+</option>
            </select>&nbsp;&nbsp;
          
            Content Category:
            <select class="input-medium" name="category">
              <option value="">All Categories</option>
              <option value="news">News</option>  
              <option value="educational">Educational</option>
              <option value="sports">Sports</option>
              <option value="entertainment">Entertainment</option>
              <option value="lifestyle">Lifestyle</option>
              <option value="business_finance">Business and Finance</option>
            </select>&nbsp;&nbsp;
          
            <input type="submit" value="Show Data" class="btn" name="aggregate_data">
        </form>
      
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
              $query = "SELECT * FROM browserSessionInfo ORDER BY urlOpeningTime DESC LIMIT 30";
              if($_POST[aggregate_data]){
                $users = $_POST[users];
                $category = $_POST['category'];
                $age_group = $_POST['age_group'];
                $gender = $_POST['gender'];
                $andStr = "";
                $whereStr = " WHERE";
                $query = "SELECT * FROM browserSessionInfo";

                if($users!=0){
                  if($users==1 && $gender==''){
                    $x=0;
                    $str = "";
                    while ($data[$x]){ 
                      if($x==0)
                        $str = $str.$data[$x][uid];
                      else
                        $str = $str.", ".$data[$x][uid];
                      $x++;
                    } 
                    $query = $query.$whereStr." userId IN(".$str.")";
                  }elseif($users==1 && $gender!='') {
                    $x=0;
                    $str = "";
                    $y=0;
                    while ($data[$x]){ 
                      if($data[$x][sex]==$gender){
                        if($y==0){
                          $str = $data[$x][uid];
                          $y++;
                        }
                        else
                          $str = $str.", ".$data[$x][uid];
                      }
                      $x++;
                    } 
                    if($str!='')
                      $query = $query.$whereStr." userId IN(".$str.")";
                    else
                      $query = $query.$whereStr." userId IN(1)";
                  }
                  else{
                    $query = $query.$whereStr." userId=".$users;
                  }
                  $andStr = " AND";
                  $whereStr = "";
                }
                if ($category!='') {
                  //echo $category;
                  $query = $query.$whereStr.$andStr." category='".$category."'";
                  $andStr = " AND";
                  $whereStr = "";
                }
                
                $query = $query." ORDER BY urlOpeningTime DESC LIMIT 30";
                //echo $query;
              }
              $query = mysql_query($query);
              $i=1;
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
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
