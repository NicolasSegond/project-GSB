<a href="index.php?uc=Inscriptionvisio&action=creationVisios" class="btn btn-primary signup">Ajouter une Visio</a>
<br>
<img src="images/visioConference.jpg" alt="Image d'une visio conférence" width="200" height="200"></img>
<?php if ($visios->rowCount() >0) {
    while ($lesvisios = $visios->fetch()) { ?>
        <div style="width: 100%; height:auto; margin: 50px 0px;">
            Numéro de la visio : <span><?php echo $lesvisios['id'] ?></span>
            <br>Nom de la visio : <span><?php echo $lesvisios['nomVisio'] ?></span>
            <br>Objectif de la visio : <span><?php echo $lesvisios['objectif'] ?></span>
            <br>Url de la visio : <span><?php echo $lesvisios['url'] ?></span>
            <br>date de la visio : <span><?php echo $lesvisios['dateVisio'] ?></span>

            <form method="POST" action="index.php?uc=Inscriptionvisio&action=validerSuppression&id=<?php echo $lesvisios['id'] ?>">
                <input type="submit" name="submit" class="btn btn-primary signup" value="Supprimer la visio" />
            </form>

            <form method="POST" action="index.php?uc=Inscriptionvisio&action=updateVisio&id=<?php echo $lesvisios['id'] ?>">
                <input type="submit" name="maj" class="btn btn-primary signup" value="Mettre à jour la visio" />
            </form>
        </div>
        <hr style="width: auto; background-color: black;">

<?php }
} else {
    echo "<h4>Il n'y a pas de visio actuellement</h4>";
} ?>