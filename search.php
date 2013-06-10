<?php

    include('assets/php/start.php');

    $result = $oauth->get('http://api.crew.dreamhack.se/1/eventinfo/search/'.$_GET['term']);

    if ( isset($result['oauth_problem']) )
        die('Kommunikationsproblem: '.$result['oauth_problem']);

    if (!$result)
        die('Hittade inga träffar, pröva sök på något annat!');

    foreach($result as $key => $line ) {
        include("assets/php/box.php");
    }

?>
