<img src="images/visioConference.jpg" alt="Image d'une visio conférence" width="200" height="200"></img>
<?php if ($visios->rowCount() >0) {
    while ($lesvisios = $visios->fetch()) { ?>
        <hr style="width: auto; background-color: black; border-top: 1px solid black;">
        <div style="width: 100%; height:auto; margin: 50px 0px;">
            Numéro de la visio : <span><?php echo $lesvisios['id'] ?></span>
            <br>Nom de la visio : <span><?php echo $lesvisios['nomVisio'] ?></span>
            <br>Objectif de la visio : <span><?php echo $lesvisios['objectif'] ?></span>
            <br>Url de la visio : <span><?php echo $lesvisios['url'] ?></span>
            <br>date de la visio : <span><?php echo $lesvisios['dateVisio'] ?></span>
        </div>
        <form method="post" action="index.php?uc=Inscriptionvisio&action=consulterAvis&id=<?php echo $lesvisios['id'] ?>">
            <input name="consulter" class="btn btn-primary signup" type="submit" value="Consulter les avis sur la visio" />
        </form>
        <?php
        foreach ($lesVisiosInscrits as $visio) {
            if ($_SESSION['id'] == $visio['idMedecin'] && $lesvisios['id'] == $visio['id']) { ?>
                <form method="post" action="index.php?uc=Inscriptionvisio&action=avisVisio&id=<?php echo $lesvisios['id'] ?>">
                    <input name="donnerAvis" class="btn btn-primary signup" type="submit" value="donner un avis sur la visio" />
                </form>
<?php
            }
        }
    }
} else {
    echo "<h4>Il n'y a pas de visio actuellement</h4>";
} ?>