<?php
//Debug mode On in case of 1 and off when 0
require_once 'debug.php';
require_once 'permissions.php';
require_once 'attributes.php';


// Create connection
    require_once 'connect_to_db.php';
    
    foreach ($permission as $values){
    
        $query_all_permissions =
                "SELECT count(`fbapps_perms_v1.2`.`".$values."`) ".
                "FROM `fbapps` INNER JOIN  `fbapps_perms_v1.2` ON `fbapps`.`app_id`=`fbapps_perms_v1.2`.`app_id` ".
                " WHERE".
                "`fbapps_perms_v1.2`.`".$values."` ".
               "AND `monthly_active_users` >=".$mau[mau_case];

        if(query_debug) print ($query_all_permissions)."</BR></BR>";
        $results_all_permissions = mysqli_query($link, $query_all_permissions) or die(mysqli_error($link));
        //if(query_debug) print_r(mysqli_fetch_array($results_permissions));
        $results_all_permissions_row = mysqli_fetch_array($results_all_permissions);
        $results_all_permissions_rows[$values] = $results_all_permissions_row[0];
        }
        
    foreach ($attributes as $values){
        $query_friends_permissions =
                "SELECT count(`fbapps_perms_v1.2`.`friends_".$values."`) ".
                "FROM `fbapps` INNER JOIN  `fbapps_perms_v1.2` ON `fbapps`.`app_id`=`fbapps_perms_v1.2`.`app_id` ".
                " WHERE".
                "`fbapps_perms_v1.2`.`friends_".$values."` ".
               "AND `monthly_active_users` >=".$mau[mau_case];

        if(query_debug) print ($query_friends_permissions)."</BR></BR>";
        $results_friends_permissions = mysqli_query($link, $query_friends_permissions) or die(mysqli_error($link));
        //if(query_debug) print_r(mysqli_fetch_array($results_permissions));
        $results_friends_permissions_row = mysqli_fetch_array($results_friends_permissions);
        $results_friends_permissions_rows["friends_".$values] = $results_friends_permissions_row[0];
        }
        
        $query_friends_permissions =
                "SELECT count(`fbapps_perms_v1.2`.`user_friends`) ".
                "FROM `fbapps` INNER JOIN  `fbapps_perms_v1.2` ON `fbapps`.`app_id`=`fbapps_perms_v1.2`.`app_id` ".
                " WHERE".
                "`fbapps_perms_v1.2`.`user_friends` ".
               "AND `monthly_active_users` >=".$mau[mau_case];
        $results_friends_permissions = mysqli_query($link, $query_friends_permissions) or die(mysqli_error($link));
        //if(query_debug) print_r(mysqli_fetch_array($results_permissions));
        $results_friends_permissions_row = mysqli_fetch_array($results_friends_permissions);
        $results_friends_permissions_rows["user_friends"] = $results_friends_permissions_row[0];
        
        $query_number_of_apps =
                "SELECT count(app_id) ".
                "FROM `fbapps`".
                " WHERE".
               "`monthly_active_users` >=".$mau[mau_case];
        $results_number_of_apps = mysqli_query($link, $query_number_of_apps) or die(mysqli_error($link));
        //if(query_debug) print_r(mysqli_fetch_array($results_permissions));
        $results_number_of_apps_row = mysqli_fetch_array($results_number_of_apps);
        
        
        
            require_once ('plot.php');
            echo "</BR></BR> All Apps: "; print_r($results_number_of_apps_row[0]); echo " for more than: ".$mau[mau_case]." MAUs";
            echo "</BR></BR> All Permissions: "; print_r($results_all_permissions_rows); echo " for more than: ".$mau[mau_case]." MAUs";
            echo "</BR></BR> Friends Permissions: "; print_r($results_friends_permissions_rows); echo " for more than: ".$mau[mau_case]." MAUs";
            
            echo "</BR></BR> Number of all Permissions: "; print(array_sum($results_all_permissions_rows)); echo " for more than: ".$mau[mau_case]." MAUs";          
            echo "</BR></BR> Number of friends Permissions: "; print(array_sum($results_friends_permissions_rows)); echo " for more than: ".$mau[mau_case]." MAUs";          
            echo "</BR></BR> All Permissions"; histogram($results_all_permissions_rows);
            echo "</BR></BR   > Friends Permissions"; histogram($results_friends_permissions_rows);
            
            foreach ($results_all_permissions_rows as $keys => $values){
                $results_all_permissions_rows_per[$keys] = ($values / $results_number_of_apps_row[0])*100;   
            }
            echo "</BR></BR> All Permissions percentage"; histogram($results_all_permissions_rows_per);
            
            foreach ($results_friends_permissions_rows as $keys => $values){
                $results_friends_permissions_rows_per[$keys] = ($values / $results_number_of_apps_row[0])*100;   
            }
            echo "</BR></BR> friends Permissions percentage"; histogram($results_friends_permissions_rows_per);
            
            foreach ($results_friends_permissions_rows as $keys => $values){
                $results_friends_permissions_rows_per[$keys] = ($values / array_sum($results_friends_permissions_rows))*100;   
            }
            echo "</BR></BR> friends Permissions percentage over the friends permissions"; histogram($results_friends_permissions_rows_per);
            
            
 ?>