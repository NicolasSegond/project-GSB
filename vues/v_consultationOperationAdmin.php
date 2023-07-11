<?php 
    $lesOperations = $pdo->afficheOpeProduit();
    if ($lesOperations->rowCount() >0) {
    while ($uneOpe = $lesOperations->fetch()) { ?>
        <hr style="width: auto; background-color: black;">
        <div style="width: 100%; height:auto; margin: 50px 0px;">
            <span><b>- Type  de l'opération: </b><?php echo $uneOpe[3] ?></span>
            <br><span><b>- Compte qui a effectué l'opération: </b><?php echo $uneOpe[1] ?></span>
            <br><span><b>- Ip du compte (ipv6): </b><?php echo $uneOpe[2] ?></span>
            <br><span><b>- Nom du produit: </b><?php echo $uneOpe[0] ?></span>
            <br>
        </div>
    <?php }
    } else {
        echo "<h4>Il n'y a pas d'opération réalisé pour le moment</h4>";
    } ?>