<?php
//Debug mode On in case of 1 and off when 0
require_once 'debug.php';
require_once 'attributes.php';


    function attr_to_perm_conv($text, $interdependent_partner, $attributes){
        for($i=0; $i < sizeof($attributes) ;$i++){
            $x .= $text.$interdependent_partner."_".$attributes[$i]."` ";
        }
        return($x);
    }


    // Create connection
    require_once 'connect_to_db.php';
    //retrive the query
    require_once 'query.php';
            
            if(query_debug) print ($query_interdependent_apps)."</BR></BR>";
            $results_interdependent_apps = mysqli_query($link, $query_interdependent_apps) or die(mysqli_error($link));
            if(query_debug) print_r(mysqli_fetch_array($results_interdependent_apps));
            
            
            foreach ($attributes as $values){
                $App_complement[$values] = 1;
            }
            //print_r($App_complement);echo "</BR></BR>";
            
            while($row_interdependent_app = mysqli_fetch_array($results_interdependent_apps)){
                
                
                if(attr_to_perms_debug){ echo"</br> </br> Query result per row: "; print_r($row_interdependent_app);}
                
                //Create P_j^u
                foreach ($attributes as $values){
                    $App_user[$row_interdependent_app['company']][$values] = $App_user[$row_interdependent_app['company']][$values]|$row_interdependent_app["user_".$values];
                }
                if(attr_to_perms_debug){ echo"</br> </br> App_user: "; print_r($App_user);}
                
                //Create P'_j^u
                foreach ($attributes as $values){
                    $App_prime_user[$row_interdependent_app['company']][$values] = $App_user[$row_interdependent_app['company']][$values] ^ $App_complement[$values];
                    
                }
                if(attr_to_perms_debug){ echo"</br> </br> App_prime_user: "; print_r($App_prime_user);}
                
                //Create P'_j^Fu
                foreach ($attributes as $values){
                    if($row_interdependent_app['user_friends'] == 1 && in_array($values, $user_friends_attributes)){
                        $App_friends[$row_interdependent_app['company']][$values] = 1;
                        }else{
                            $App_friends[$row_interdependent_app['company']][$values] = $App_friends[$row_interdependent_app['company']][$values]|$row_interdependent_app["friends_".$values];
                        }
                    }
                if(attr_to_perms_debug){ echo"</br> </br> App_friends: "; print_r($App_friends);}
                //Create the degree of interdependency matrix as a result of P'_j^u and P_j^F
                foreach ($attributes as $values){
                    $degree_of_interdenpendency[$row_interdependent_app['company']][$values] = $App_prime_user[$row_interdependent_app['company']][$values] & $App_friends[$row_interdependent_app['company']][$values]; 
                }
                if(attr_to_perms_debug){ echo"</br> </br> Degree_of_interdenpendency per AppP: "; print_r($degree_of_interdenpendency);}
                
                
                //Sum all the attributes (value 1) of the of P_j^Fu
                $App_friends_sum[$row_interdependent_app['company']]= array_count_values($App_friends[$row_interdependent_app['company']]);
                if(attr_statistics_debug){ echo"</br> </br> App_friends_sum: "; print_r($App_friends_sum);}
                
                //Store the frequencies of P_j^Fu (attributes that can be collected)
                $App_friends_values_all[$row_interdependent_app['company']] = $App_friends_sum[$row_interdependent_app['company']][1];
                
                //Sum all the attributes (value 1) of the degree of the interdependent privacy for the P_j
                $degree_of_interdenpendency_sum[$row_interdependent_app['company']]= array_count_values($degree_of_interdenpendency[$row_interdependent_app['company']]);
                if(attr_statistics_debug){ echo"</br> </br> degree_of_interdenpendency_sum: "; print_r($degree_of_interdenpendency_sum);}
                
                //Store all P_js in a matrix
                $degree_of_interdenpendency_attr_all[$row_interdependent_app['company']] = $degree_of_interdenpendency[$row_interdependent_app['company']];
                
                //Store all the P_j ids
                $Company_id[] = $row_interdependent_app['company'];
                
                //Store the frequencies of the degree of interdependent privacy (attributes that can be collected)
                $degree_of_interdenpendency_values_all[$row_interdependent_app['company']] = $degree_of_interdenpendency_sum[$row_interdependent_app['company']][1];
            }
            //Store the unique P_j id's
            $Company_ids = array_unique($Company_id);
            
            if(attr_statistics_debug){ echo"</br> </br> AppPs: "; print_r($Company_ids);}
            if(attr_statistics_debug){ echo"</br> </br> App_friends: "; print_r($App_friends);}
            
            foreach($attributes as $values_i){
                $num = 0;
                foreach($Company_ids as $values_j){
                    $Spread_P_F[$values_i] += $App_friends[$values_j][$values_i];
                    //$num ++;
                }
                                
            }
            
            foreach($attributes as $values_i){
                $num = 0;
                foreach($Company_ids as $values_j){
                    $Spread_interdependent[$values_i] += $degree_of_interdenpendency_attr_all[$values_j][$values_i];
                    //$num ++;
                }
                                
            }
            
            require_once ('plot.php');
            print("</BR></BR> Number of Applications Providers: ".count($Company_ids)." for more than: ".$mau[mau_case]." MAUs");
            $App_friends_values_sum_all = array_count_values($App_friends_values_all);
            echo "</BR></BR> All P_j^Fus: "; print_r($App_friends_values_sum_all);
            $degree_of_interdenpendency_values_sum_all = array_count_values($degree_of_interdenpendency_values_all);
            echo "</BR></BR> Degree of int privacy for all P_js: "; print_r($degree_of_interdenpendency_values_sum_all);
            echo "</BR></BR> Spread interdependent: "; print_r($Spread_interdependent);
            echo "</BR></BR> Spread P_F: "; print_r($Spread_P_F);
            //print(array_sum($Spread));
            //print(array_sum($degree_of_interdenpendency_values_values_sum));
            
              //Percentage
            foreach($App_friends_values_sum_all as $key => $value){
                $App_friends_values_percentage[$key] = ($value / count($App_friends))*100;                       
            }
            
            foreach($degree_of_interdenpendency_values_sum_all as $key => $value){
                $degree_of_interdenpendency_values_percentage[$key] = ($value / count($App_friends))*100;                       
            }
            
            foreach($Spread_P_F as $value){
                $Spread_P_F_sum += $value;                       
            }
            
            foreach($Spread_P_F as $key => $value){
                $Spread_P_F_percentage[$key] = ($value / $Spread_P_F_sum)*100;                       
            }
            
            $P_F_sensitivity = $Spread_P_F['relationships']+$Spread_P_F['relationship_details']
            +$Spread_P_F['location']+$Spread_P_F['hometown']+$Spread_P_F['education_history']+$Spread_P_F['work_history']
            +$Spread_P_F['religion_politics'];
            
            $P_F_sensitivity_percentage = ($P_F_sensitivity / $Spread_P_F_sum)*100;                       
            
            $P_F_location = $Spread_P_F['location']+$Spread_P_F['hometown']+$Spread_P_F['education_history']+$Spread_P_F['work_history'];
            
            $P_F_location_percentage = ($P_F_location / $Spread_P_F_sum)*100;
            
            //Apps that enable interdependency 
            foreach($Spread_interdependent as $value){
                $Spread_interdependent_sum += $value;                       
            }
            //print_r($Spread_interdependent_sum);
            foreach($Spread_interdependent as $key => $value){
                $Spread_interdependent_percentage[$key] = ($value / $Spread_interdependent_sum)*100;                       
            }
            
            $Degree_sensitivity = $Spread_interdependent['relationships']+$Spread_interdependent['relationship_details']
            +$Spread_interdependent['location']+$Spread_interdependent['hometown']+$Spread_interdependent['education_history']+$Spread_interdependent['work_history']
            +$Spread_interdependent['photos']+$Spread_interdependent['videos'];
            
            $Degree_sensitivity_percentage = ($Degree_sensitivity / $Spread_interdependent_sum)*100;                       
            
            $Degree_location = $Spread_interdependent['location']+$Spread_interdependent['hometown']+$Spread_interdependent['education_history']+$Spread_interdependent['work_history'];
            
            $Degree_location_percentage = ($Degree_location / $Spread_interdependent_sum)*100;
         
            
            echo "</BR></BR> P_j^Fu statistics"; histogram($App_friends_values_sum_all);
            echo "</BR></BR> P_j^Fu percentage"; histogram($App_friends_values_percentage);
            echo "</BR></BR> Degree of interedependent privacy"; histogram($degree_of_interdenpendency_values_sum_all);
            echo "</BR></BR> Degree of interedependent privacy percentage"; histogram($degree_of_interdenpendency_values_percentage);
            
            echo "</BR></BR> Spread of interedependent privacy attr"; histogram($Spread_interdependent);
            echo "</BR></BR> Spread of interedependent privacy percentage attr"; histogram($Spread_interdependent_percentage);
            echo "</BR></BR> Degree sensitive percentage: "; print($Degree_sensitivity_percentage);
            echo "</BR></BR> Degree location percentage: "; print($Degree_location_percentage);
           
            echo "</BR></BR> Spread of P_F"; histogram($Spread_P_F);
            echo "</BR></BR> Spread of P_F percentage"; histogram($Spread_P_F_percentage);
            echo "</BR></BR> P_j^Fu* sensitive percentage: "; print($P_F_sensitivity_percentage);
            echo "</BR></BR> P_j^Fu* location percentage: "; print($P_F_location_percentage);
 ?>