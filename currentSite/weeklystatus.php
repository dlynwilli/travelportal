Jun<html>
  <header>
    <title>Weekly Status report</title>
    <link href="styles/style.css" rel="stylesheet" type="text/css">
  </header>
  <?php
  @extract($_POST);
  $link = mysql_connect('localhost', 'sthrtrav_bahabin', 'vct2025') or die('Could not connect: ' . mysql_error());
      $db1 = @mysql_select_db('sthrtrav_weeklystatus') or die('Could not select database');
      $db2 = @mysql_select_db('sthrtrav_travelrequest') or die('Could not select database');
      $query1 = 'SELECT firstname, lastname, middleinitial,username FROM users WHERE company="Booz Allen Hamilton" ORDER BY firstname ASC';             
?>
<body>
    <form name="weeklystatus" action="weeklystatus.php" method="post">
      
      <p>
           
       <?php
       
       if (isset($name)) {
         $username = substr($name,0,strpos($name,"."));
         $name = substr($name,strpos($name,".")+1);
         echo "<p>$name ($username)</p>";
         ?>
         <p><h1>Weekly Status for the week ending <select>
         
         <?
         $sun = date("F j, Y",strtotime("next Sunday")) ;
         $nextsun = date("F j, Y",strtotime("last Sunday",strtotime('+7 days')));
         echo '<option>'.$sun.'</option>';
         echo '<option>'.date("F j, Y",strtotime("last Sunday")).'</option>';
         echo '<option>'.date("F j, Y",strtotime("last Sunday",strtotime('-7 days'))).'</option>';
         echo '<option>'.date("F j, Y",strtotime("last Sunday",strtotime('-14 days'))).'</option>';
         echo '</select>';
         $query2 = "SELECT projects.project_description,statusreport.detail from sthrtrav_weeklystatus.statusreport left join sthrtrav_weeklystatus.projects on statusreport.project_id = projects.project_id where username='$username'";
         $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
         mysql_data_seek($result2,0);
         
         echo '<p><h2><u>Projects</u></h2></p>';
         
         while($row = mysql_fetch_row($result2)) {
           echo '<h3><a href="">'.$row[0].'</a></h3>';
           echo '<ul>';
            echo '<li>'.$row[1].'</li>';
         }  
         ?>
        <li><input type="text"><input type="button" value="save"></li>
      </ul>
      <h3><a href="">I2WD Cloud T&E</a></h3>
      <ul>
        <li>Met with WP1 to discuss test data and ingest validation.</li>
        <li>Met with WP2b to discuss Configuration Management processes.</li>
        <li>Met with WP2b to discuss HPTB environment and strategize about the HP Test Suite installation.</li>
        <li>Met with WP5 about requirements, use cases, and test strategy.</li>
        <li>Met with Christine Thorsen about DSC Test strategy.</li>
        <li>Continue to build on the DSC Software Test Plan (STP).</li>
        <li><input type="text"><input type="button" value="save"></li>
      </ul>
      <p><h1>Next Week</h1></p>
      <p><h2><u>Projects</u></h2></p>
      <h3><a href="">Manage Recruiting Process for the Aberdeen Tactical IT Team</a></h3>
      <ul>
        <li>Interviewed SAL candidates for DSC opportunities.</li>
        <li><input type="text"><input type="button" value="save"></li>
      </ul>
      <h3><a href="">I2WD Cloud T&E</a></h3>
      <ul>
        <li><input type="text"><input type="button" value="save"></li>
      </ul>   
      <?
       
       }else{ 
         $result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
         echo '<strong>User: </strong><select name="name"><option></option>';  
         mysql_data_seek($result1,0);
         while($row = mysql_fetch_row($result1)) {
           $name = $row[0];
           if (strlen($row[2]) > 0) {
             $name = $name . " " . $row[2];
           }
           $name = $name . " " . $row[1];
           echo '<option value="'.$row[3].".".$name.'">'.$name.'</option>';
         } 
         echo '</select>';
         echo '<input type="submit" value="Go">';
      }
      ?>
      </form>
      </body>
      </html>