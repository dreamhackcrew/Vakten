<?php
    include('assets/php/start.php');
    include('assets/php/header.php');
?>

        <div class="search-area-wrapper">
            <div class="search-area container">
                <h3 class="search-header">SÖK</h3>
                <p class="search-tag-line">Sök på personnummer, registreringsnummer, namn, nick (smeknamn) eller telefonnummer</p>

                <form autocomplete="off"  class="search-form clearfix" id="search-form" novalidate="novalidate">
                    <input type="text" title="* Please enter a search term!" placeholder="Skriv här för att söka" name="s" id="searchbox" class="search-term required" autocomplete="off">
                    <div id="search-error-container"></div>
                </form>
            </div>
        </div>

<div class="container-fluid main-container" id="main">

</div>

<script>
    $(function() {
        var cache = {};
        $( "#searchbox" ).autocomplete({
            source: function( request, response ) {
                $.get( "search.php", request, function( data, status, xhr ) {
                    $("#main").html(data);
                });
            },
            change: function( event, ui ) {
                
            }
        });
    });
</script>

<?php
    include('assets/php/footer.php');
?>
