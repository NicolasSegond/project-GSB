<?php
$pdo->ajouteVueProduit();
?>

<?php if ($prods->rowCount() >0) {
    while ($produits = $prods->fetch()) { ?>
        <hr style="width: auto; background-color: black;">
        <div style="width: 100%; height:auto; margin: 50px 0px;">
            <span><b>Nom du produit : </b><?php echo $produits['nom'] ?></span>
            <br><img style="width: 100%; height: auto;" src="<?php echo "images/" . $produits['image'] ?>" />
            <br><span><b>objectif : </b><?php echo $produits['objectif'] ?></span>
            <br><span><b>informations : </b><?php echo $produits['information'] ?></span>
            <br><span><b>Effets indésirables : </b><?php echo $produits['effetIndesirable'] ?></span>
        </div>
<?php }
} else {
    echo "<h4>Il n'y a pas de produit actuellement</h4>";
} ?>