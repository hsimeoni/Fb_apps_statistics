<?php
//Debug mode On in case of 1 and off when 0
define("query_debug",0, true); 
define("attr_to_perms_debug",0, true);

$mau = array(10,10000);
    $attributes = array (
    'about_me',
    'actions',
    'actions_books',
    'actions_music',
    'actions_news',
    'actions_video', 
    'activities',
    'birthday',
    'checkins', 
    'education_history',
    'events',
    'games_activity', 
    'groups',
    'hometown',
    'interests',
    'location',
    'likes',
    'notes',
    'online_presence',
    'photos',
    'photo_video_tags', 
    'questions',
    'relationships',
    'relationship_details',
    'religion_politics',
    'status',
    'subscriptions',
    'videos',
    'website',
    'work_history',
    );
   
$user_friends_attributes = array (
    'about_me', 
    'birthday',
    'education_history',
    'hometown',
    'interests',
    'location',
    'relationships',
    'relationship_details',
    'religion_politics',
    'website',
    'work_history',
    //'languages',
    //'gender',
    //'link',
    //'favorite_teams',
    //'favorite_athlets',
    //'locale',
    //'updated_time',
    );



    function attr_to_perm_conv($text, $interdependent_partner, $attributes){
        for($i=0; $i < sizeof($attributes) ;$i++){
            $x .= $text.$interdependent_partner."_".$attributes[$i]."` ";
        }
        return($x);
    }
    
    function histogram($data){
        $max = max($data);
        echo '<table>';
        foreach ($data as $k=>$v)
        {
            echo "<tr><td>$k</td><td><img src='bar.php?max=$max&val=$v'> $v</td></tr>";
        }
        echo '</table>';
    }
           
	// Create connection
        require_once ('connect_to_db.php');
            $query_interdependent_apps =
                "SELECT `fbapps`.`app_id`,`fbapps_perms_v1.2`.`user_friends`".attr_to_perm_conv(", `fbapps_perms_v1.2`.`","user", $attributes).attr_to_perm_conv(", `fbapps_perms_v1.2`.`","friends", $attributes).
                "FROM `fbapps` INNER JOIN  `fbapps_perms_v1.2` ON `fbapps`.`app_id`=`fbapps_perms_v1.2`.`app_id`".
                "WHERE".
                "(`fbapps_perms_v1.2`.`user_friends`=1 ".attr_to_perm_conv("OR `fbapps_perms_v1.2`.`", "friends", $attributes).")".
              //  " AND `fbapps`.`app_id` = 162729813767876".
                " AND `monthly_active_users`>=".$mau[0];
            if(query_debug) print ($query_interdependent_apps)."</BR></BR>";
            $results_interdependent_apps = mysqli_query($link, $query_interdependent_apps) or die(mysqli_error($link));
            if(query_debug) print_r(mysqli_fetch_array($results_interdependent_apps));
            
            
   
            while($row_interdependent_app = mysqli_fetch_array($results_interdependent_apps)){                
                //Create A'_j^Fu
                foreach ($attributes as $values){
                    if($row_interdependent_app['user_friends'] == 1 && in_array($values, $user_friends_attributes)){
                        $App_friends[$values] = 1;
                        }else{
                            $App_friends[$values] = $row_interdependent_app["friends_".$values];
                        }
                    }
                //Create the degree of interdependency matrix as a result of A'_j^u and A_j^F
                foreach ($attributes as $values){
                    $degree_of_interdenpendency[$values] = $App_friends[$values]; 
                }               
                
                //Sum all the attributes (value 1) of the degree of the interdependent privacy for the A_j
                $degree_of_interdenpendency_sum = array_count_values($degree_of_interdenpendency);
                
                //Store all A_js in a matrix
                $degree_of_interdenpendency_attr_all[$row_interdependent_app['app_id']] = $degree_of_interdenpendency;
                
                //Store all the A_j ids
                $App_ids[] = $row_interdependent_app['app_id'];
               
                //Store the frequencies of the degree of interdependent privacy (attributes that can be collected)
                $degree_of_interdenpendency_values_all[$row_interdependent_app['app_id']] = $degree_of_interdenpendency_sum[1];
            }
            
            //print_r ($degree_of_interdenpendency_attr_all);
            //print_r($App_ids);
            
            
            foreach($attributes as $values_i){
                $num = 0;
                foreach($App_ids as $values_j){
                    $Spread[$values_i] += $degree_of_interdenpendency_attr_all[$values_j][$values_i];
                    //$num ++;
                }
                                
            }
            
            echo "</BR></BR> Number of Applications: "; print(count($App_ids));
            $degree_of_interdenpendency_values_values_sum_all = array_count_values($degree_of_interdenpendency_values_all);
            echo "</BR></BR> Degree of int privacy for all A_js: "; print_r($degree_of_interdenpendency_values_values_sum_all);
            echo "</BR></BR> Spread: "; print_r($Spread);
            //print(array_sum($Spread));
            //print(array_sum($degree_of_interdenpendency_values_values_sum));
            
            
            histogram($degree_of_interdenpendency_values_values_sum_all);
            histogram($Spread);
            
 ?>