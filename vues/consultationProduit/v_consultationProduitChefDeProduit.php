<a href="index.php?uc=produit&action=creationProduits" class="btn btn-primary signup">Ajouter un produit</a>
<?php if ($prods->rowCount() > 0) {
    while ($produits = $prods->fetch()) { ?>
        <hr style="width: auto; background-color: black;">
        <div style="width: 100%; height:auto; margin: 50px 0px;">
            <span><b>Nom du produit : </b><?php echo $produits['nom'] ?></span>
            <br><img style="width: 100%; height: auto;" src="<?php echo "images/" . $produits['image'] ?>" />
            <br><span><b>objectif : </b><?php echo $produits['objectif'] ?></span>
            <br><span><b>informations : </b><?php echo $produits['information'] ?></span>
            <br><span><b>Effets indésirables : </b><?php echo $produits['effetIndesirable'] ?></span>
            <br><span><b>Vues du produit : </b><?php echo $produits['vuesProduits'] ?></span>
            <br><br>
            <form method="POST" action="index.php?uc=produit&action=validerSuppression&id=<?php echo $produits['id'] ?>">
                <input type="submit" name="submit" class="btn btn-primary signup" value="Supprimer le produit" />
            </form>
            <form method="POST" action="index.php?uc=produit&action=updateProduit&id=<?php echo $produits['id'] ?>">
                <input type="submit" name="maj" class="btn btn-primary signup" value="Mettre à jour le produit" />
            </form>
        </div>
<?php }
} else {
    echo "<h4>Il n'y a pas de produit actuellement</h4>";
} ?>