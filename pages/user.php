<?php
    include('../assets/php/start.php');
    include('../assets/php/header.php');?>

<div class="container-fluid main-container">

<?php 

    if ( !isset($_SESSION['user']) )
        return trigger_error('Not logged in!',E_USER_ERROR);

    echo '<pre>';
    print_r($_SESSION);
    print_r($_COOKIE);
    echo '</pre>';

    ?>

</div>

<?php 

    include('../assets/php/footer.php');

?>
