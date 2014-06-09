<?php


    function runOauth() {
        global $oauth;

        // Om vi har en token, läs in den
        /*if ( isset($_SESSION['token']['oauth_token_secret']) ) {
            echo "token<br>";
            $oauth->set_token($_SESSION['token']['oauth_token']);
            $oauth->set_token_secret($_SESSION['token']['oauth_token_secret']);
        }*/

        // We have all information, and are authorized
        if ( isset($_SESSION['access']['oauth_token'] ) ) {
            $oauth->set_token($_SESSION['access']['oauth_token']);
            $oauth->set_token_secret($_SESSION['access']['oauth_token_secret']);
            return true;
        }

        // We have a request token, then use it for signing the requests
        if ( isset($_SESSION['request']['oauth_token_secret']) ) {
            $oauth->set_token_secret($_SESSION['request']['oauth_token_secret']);
        }

        // Take care of a incomming token
        if ( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ) {
            // Remove the token, because now its used
            unset($_SESSION['request']);

            $_SESSION['access'] = $oauth->access_token($_GET['oauth_token'],$_GET['oauth_verifier']);

            $oauth->set_token($_SESSION['access']['oauth_token']);
            $oauth->set_token_secret($_SESSION['access']['oauth_token_secret']);
        }

        // Make shure we have a request_token
        if ( (!isset($_SESSION['request']) || !$_SESSION['request']) && !isset($_SESSION['token']) && !isset($_SESSION['access']) ) {
            $_SESSION['request'] = $oauth->request_token('http://vakten.crew.dreamhack.se/pages/login.php');
        }

        if ( isset($_SESSION['request']['oauth_problem']) ) {
            // There is a problem with the request_token..
			print_r($_SESSION);
            trigger_error('Oauth failed: '.$_SESSION['request']['oauth_problem'],E_USER_WARNING);
			unset($_SESSION['request']);
            return;
        }


        // We have a request_token but no access_token, redirect the user to the login page
        if ( !isset($_SESSION['token']) && isset($_SESSION['request']['oauth_token']) ) {
            // Save the token temporarily
            $token = $_SESSION['request']['oauth_token'];

            // Redirect the user to the login page
            header("Location: http://api.crew.dreamhack.se/oauth/authorize?oauth_token=".$token);
            return;
        }

        // If there are a problem with the access_token, restart the whole auth process
        if ( isset($_SESSION['access']['oauth_problem']) ) {
            trigger_error('Oauth access failed: '.$_SESSION['access']['oauth_problem'],E_USER_WARNING);
            unset($_SESSION['token']);
            unset($_SESSION['access']);
            unset($_SESSION['request']);
            return;
        }

        return true;
    }

    include('../assets/php/start.php');

    $signedin = runOauth();

    if ( $signedin ) {
        setcookie(
            "auth",
            json_encode($_SESSION['access']),
            time() + (10 * 365 * 24 * 60 * 60),
            '/'
        );
        if ( !isset($_SESSION['user']) )
            $_SESSION['user'] = $oauth->get('http://api.crew.dreamhack.se/1/user/get');

        header("Location: /");
    }

    include('../assets/php/header.php'); ?>

<div class="container-fluid main-container">

<?php 
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';

    //echo $result;
    ?>

</div>

<?php 

    include('../assets/php/footer.php');

?>
