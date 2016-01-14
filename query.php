<?php
require_once 'debug.php';
//$test_apps_array = array(
//   12,
//    34,
//    78
  //  );

//if(test_apps_debug==1){
//    foreach ($test_apps_array as $value){
//        $test_apps_sql[] = "`fbapps`.`app_id` = ".$value." OR ";
//    }
//}
//print_r($test_apps_sql);
if(test_apps_debug==1){
    $test_apps = "AND (".
            "`fbapps`.`app_id` = 34".
            " OR `fbapps`.`app_id` = 12".
            " OR `fbapps`.`app_id` = 78".
            " OR `fbapps`.`app_id` = 56".
            ") ";
}else{
   $test_apps = "AND (".
            "`fbapps`.`app_id` = 34".
            " OR `fbapps`.`app_id` != 12".
            " OR `fbapps`.`app_id` != 78".
            " OR `fbapps`.`app_id` != 56".
            ") "; 
}
$query_interdependent_apps =
                "SELECT `fbapps`.`app_id`,`fbapps`.`company`,`fbapps_perms_v1.2`.`user_friends`".attr_to_perm_conv(", `fbapps_perms_v1.2`.`","user", $attributes).attr_to_perm_conv(", `fbapps_perms_v1.2`.`","friends", $attributes).
                "FROM `fbapps` INNER JOIN  `fbapps_perms_v1.2` ON `fbapps`.`app_id`=`fbapps_perms_v1.2`.`app_id`".
                "WHERE".
                "(`fbapps_perms_v1.2`.`user_friends`=1 ".attr_to_perm_conv("OR `fbapps_perms_v1.2`.`", "friends", $attributes).")".
                $test_apps.
               "AND `monthly_active_users`>=".$mau[mau_case];

?>