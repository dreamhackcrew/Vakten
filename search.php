<?php

    include('assets/php/start.php');

    $result = $oauth->get('http://api.crew.dreamhack.se/1/user/search/'.$_GET['term']);

?>
<pre>
<?php
    print_r($result);
?></pre>
