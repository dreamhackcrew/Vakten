<div class="result <?php echo ((strtotime($line['allowed_arrive']) <= mktime(0,0,0) && isset($line['teams']) )?'allowed':'denied'); ?>">
    <div class="images">
        <img src="http://<?php echo $line['badge_picture']; ?>?height=300&width=300" class="badge_picture">
        <img src="http://<?php echo $line['profile_picture']; ?>?height=300&width=300" class="profile">
    </div>
    <h2><?php echo $line['firstname']; ?> <?php echo $line['lastname']; ?></h2>
    <div>Smeknamn: <strong><?php echo $line['username']; ?></strong></div>
    <div>Bostadsord: <strong><?php echo $line['city']; ?></strong></div>
    <div>Tidigaste insläppsdatum: <strong><?php echo $line['allowed_arrive']; ?></strong></div>
    <div>Registeringsnummer: <strong><?php echo $line['car']; ?></strong></div>

    <h4>Team tillhörighet</h4>
    <?php 
    if ( !isset($line['teams']) ) {
        echo '<div class="alert alert-block">Personen är inte medlem i dreamhack crew!</div>';
    } else {
        foreach($line['teams'] as $key2 => $line2) { ?>
        <div>
            <?php 
                foreach($line2 as $key3 => $line3) { 
                    if ( $line3['is_team'] == 'Y' ) 
                        $line2[$key3] = '<strong>'.$line3['name'].'</strong>';
                    else
                        $line2[$key3] = $line3['name'];
                }
            ?>
            <?php echo implode(' > ',$line2); ?> 
        </div>
    <?php } 
    } ?>
</div>
