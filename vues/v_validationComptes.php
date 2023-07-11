<!DOCTYPE html>
<html lang="fr">

<head>
    <title>GSB - extranet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body background="assets/img/laboratoire.jpg">

    <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

    <div class="page-content container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-wrapper">
                    <div class="box">
                        <div class="content-wrap">
                            <legend>Valider l'inscription d'un medecin</legend>
                            <a href="index.php?uc=connexion&action=connecter" class="btn btn-primary signup">Retour</a><br><br>
                            <?php
                            if ($lesMedecins->rowCount() == 1) {
                                while ($lesMedecinsNonValider = $lesMedecins->fetch()) { ?>
                                    <span> Nom du médecin : </span> <?php echo $lesMedecinsNonValider['nom'] . " " . $lesMedecinsNonValider['prenom'] ?>
                                    <br><span> rpps du médecin : </span> <?php echo $lesMedecinsNonValider['rpps'] ?>
                                    <br><br>
                                    <form method="POST" action="index.php?uc=validation&action=validerMedecin&idMedecin=<?php echo $lesMedecinsNonValider['id'] ?>">
                                        <input type="submit" name="valider" class="btn btn-primary signup" style="margin-bottom: 10px;" value="Valider inscription">
                                    </form>
                                    <form method="POST" action="index.php?uc=validation&action=validerMedecin&idMedecin=<?php echo $lesMedecinsNonValider['id'] ?>">
                                        <input type="submit" name="refuser" class="btn btn-primary signup" style="margin-bottom: 10px;" value="Refuser inscription">
                                    </form>
                                    <hr>
                            <?php
                                }
                            } else {
                                echo "<h4>Il n'y a aucun médecins à valider</h4>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>