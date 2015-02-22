<?php

    include('assets/php/start.php');

    $result = $oauth->get('http://api.crew.dreamhack.se/1/eventinfo/search/current/'.$_GET['term']);

    if ( isset($result['oauth_problem']) )
        die('Kommunikationsproblem: '.$result['oauth_problem']);

    if (!$result)
        die('Hittade inga träffar, pröva sök på något annat!');


	$members = $nonmembers = array();
	foreach($result as $key => $line ) {
		if ( isset($line['teams']) ) {
			$members[] = $line;
		} else {
			$nonmembers[] = $line;
		}
    }

    foreach($members as $key => $line ) {
		include("assets/php/box.php");
	}
	echo '<hr>';
    foreach($nonmembers as $key => $line ) {
		include("assets/php/box.php");
	}

?>
