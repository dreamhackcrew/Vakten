<?php


	error_reporting(E_ALL);
ini_set('display_errors', 1);

    include('assets/php/start.php');
    include('assets/php/header.php');
?>

        <div class="search-area-wrapper">
            <div class="search-area container">
                <h3 class="search-header">SÖK</h3>
                <p class="search-tag-line">Sök på personnummer, registreringsnummer, namn, nick (smeknamn) eller telefonnummer</p>

                <form autocomplete="off"  class="search-form clearfix" id="search-form" novalidate="novalidate">
                    <input type="text" title="* Please enter a search term!" placeholder="Skriv här för att söka" name="s" id="searchbox" class="search-term required" autocomplete="off" value="<?php echo isset($_GET['s'])?$_GET['s']:''; ?>">
                    <div id="search-error-container"></div>
                    <img src="/assets/images/spinner.gif" class="spinner">
                </form>
            </div>
        </div>

<div class="container-fluid main-container" id="main">
<?php
	if ( isset($_GET['s']) ) {
		try {
	        $result = $oauth->get('http://api.crew.dreamhack.se/1/eventinfo/search/'.$_GET['s']);
		} catch (Exception $err) {
			die($err->getMessage());
		}
		
        if ( isset($result['oauth_problem']) ) {
            die('Kommunikationsproblem: '.$result['oauth_problem']);
        }

        if (!$result)
            die('Hittade inga träffar, pröva sök på något annat!');

        foreach($result as $key => $line ) {
            include("assets/php/box.php");
        }
    }
?>
</div>

<script>
    $(function() {
        var cache = {};
        $( "#searchbox" ).autocomplete({
            source: function( request, response ) {
                $.get( "search.php", request, function( data, status, xhr ) {
                    $('.search-area').removeClass('searching');
                    $("#main").html(data);
                });
            },
            change: function( event, ui ) {
                
            },
            search: function( event, ui ) {
                $('.search-area').addClass('searching');
            }
        });
    });
</script>

<?php
    include('assets/php/footer.php');
?>
