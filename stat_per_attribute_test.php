<?php

//echo "ID: ".$_GET['ID'];
//$array = json_decode($_GET['ID']);
//var_dump($array);
//echo "ID1: ".$_GET['ID1'];
//echo "ID: ".$_GET['ID'];
//$ids_array = explode(',', $_GET['ID']);
//var_dump($ids_array);
//$results = array();
 //Connect to DB !!!  
	$servername = "localhost";
	$username = "starfish_fbapps";
	$password = "RpWBFCoJAFmNqkLk";
	$db = "starfish_fbapps_db";
        
        $mau = array(10,10000);
        $permissions = array (
            'about_me',
            'birthday',
            'relationships',
            'relationship_details',
            'interests',
            'religion_politics',
            'website',
            'online_presence',
            'status',
            'photos',
            'videos',
            'activities',
            'notes',
            'hometown',
            'location',
            'education_history',
            'work_history',
            'activities',
            'events',
            'groups',
            'likes',
            'friends'
            );
   
        
	// Create connection
	$link = mysqli_connect($servername,$username,$password,$db) or die("Error connection".mysqli_error($link));
        for($i=0; $i < sizeof($permissions) ;$i++){
            if($permissions[$i]!='friends'){
                for($j=0; $j < sizeof($mau); $j++){
                    $query_u = 
                        "SELECT COUNT(`fbapps`.`app_id`)".
                        "FROM `fbapps`".
                        "INNER JOIN  `fbapps_perms_v1` ON `fbapps`.`app_id`=`fbapps_perms_v1`.`app_id`".
                        "WHERE ((`fbapps_perms_v1`.`friends_".$permissions[$i]."`=1)"
                        . "AND (`fbapps_perms_v1`.`user_".$permissions[$i]."`IS NULL))"
                        . "AND `monthly_active_users`>=".$mau[$j]." ORDER BY `fbapps`.`company`  DESC";

                    $results_f = mysqli_query($link, $query_u) or die(mysqli_error($link)); 
                    $row_f = mysqli_fetch_array($results_f);

                    $query_uf = 
                        "SELECT COUNT(`fbapps`.`app_id`)".
                        "FROM `fbapps`".
                        "INNER JOIN  `fbapps_perms_v1` ON `fbapps`.`app_id`=`fbapps_perms_v1`.`app_id`".
                        "WHERE (`fbapps_perms_v1`.`friends_".$permissions[$i]."`=1)"
                        . "AND (`fbapps_perms_v1`.`user_".$permissions[$i]."`=1)"
                        . "AND `monthly_active_users`>=".$mau[$j]
                        ." ORDER BY `fbapps`.`company`  DESC";

                    $results_uf = mysqli_query($link, $query_uf) or die(mysqli_error($link)); 
                    $row_uf = mysqli_fetch_array($results_uf);

                    print(preg_replace('/(_)/', '\_', $permissions[$i])." (".$mau[$j].") & ".$row_f["COUNT(`fbapps`.`app_id`)"]." & ".$row_uf["COUNT(`fbapps`.`app_id`)"]."\\\\<BR>");
                }
            }else {
                for($j=0; $j < sizeof($mau); $j++){
                $query_f = 
                    "SELECT COUNT(`fbapps`.`app_id`)".
                    "FROM `fbapps`".
                    "INNER JOIN  `fbapps_perms_v1` ON `fbapps`.`app_id`=`fbapps_perms_v1`.`app_id`".
                    "WHERE (`fbapps_perms_v1`.`user_".$permissions[$i]."`=1)"
                    . "AND `monthly_active_users`>=".$mau[$j]." ORDER BY `fbapps`.`company`  DESC";
                    echo "*";
                $results_f = mysqli_query($link, $query_f) or die(mysqli_error($link)); 
                $row_f = mysqli_fetch_array($results_f);
                
                print(preg_replace('/(_)/', '\_', $permissions[$i])." (".$mau[$j].") & "." "." & ".$row_uf["COUNT(`fbapps`.`app_id`)"]."\\\\<BR>");
                }  
                }
        }
        /* $j=0;
        foreach ($ids_array as $ids_array_value){
            //print 'ID: '.$ids_array_value."\xA";
            $result_perms = mysqli_query($link, "DESCRIBE fbapps_perms") or die(mysqli_error($link)); 
            while($column = mysqli_fetch_array($result_perms)){$scolumn[] = $column[0];}
            $result_perm_values[$ids_array_value] = mysqli_query($link, "SELECT * FROM fbapps_perms WHERE app_id=".$ids_array_value) or die(mysqli_error($link)); 
            //print_r($result_perm_values[$ids_array_value]);
            //echo '&';
            while($row = mysqli_fetch_array($result_perm_values[$ids_array_value])){
            $i = 0;
            //echo '|';
                //while($column = mysqli_fetch_array($result_perms)){}
                foreach ($scolumn as $value){
                    //echo $value;
                    if (isset($row[$i])){
                        $results[$row['app_id']][$value] = $row[$i];
                        //var_dump($results[$row['app_id']][$column[0]]);
                        //echo '*';
                    }
                    $i++;
                }
            }
            $j++;
            
        }
        *  $i=0;
          while($row = mysqli_fetch_array($results)){
              var_dump($row);
              echo "<BR>";
              $i++;
          }
          print $i;
       // echo json_
        
        */ 
   
         
        //encode($results);
 ?>