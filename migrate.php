<?php

  require_once 'connect_to_db.php';
  $file = 'backups/mytable.sql';
  $migrate = "SELECT * INTO OUTFILE '$file' FROM fbapps25 WHERE app_id NOT IN (SELECT app_id FROM fbapps )";
  
  $results = mysqli_query($link, $migrate) or die(mysqli_error($link));
  
  
  $result = mysql_query("SELECT * INTO OUTFILE '$file' FROM `##table##`");

  while($row = mysqli_fetch_array($results)){
  
      $rows[] = $row;
  }
            
  print_r($rows);
            ?>