<?php

function histogram($data){
        $max = max($data);
        echo '<table>';
        foreach ($data as $k=>$v)
        {
            echo "<tr><td>$k</td><td><img src='bar.php?max=$max&val=$v'> $v</td></tr>";
        }
        echo '</table>';
    }
?>
