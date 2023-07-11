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
                            <legend>Valider les avis</legend>
                            <?php while($lesAvis = $lesAvisNonVerifies->fetch()){?>
                            <span>Nom et prénom du médecin :</span> <?php echo $lesAvis['nom']." ".$lesAvis['prenom'] ?>
                            <br><span>Nom de la visioconférence :</span> <?php echo $lesAvis['nomVisio'] ?>
                            <br><span>Avis :</span> <?php echo $lesAvis['avis']?>
                            <form method="POST" action="index.php?uc=Inscriptionvisio&action=validerAvis&idMedecin=<?php echo $lesAvis['idMedecin'] ?>&idVisio=<?php echo $lesAvis['idVisio'] ?>">
                                <input name="accepter" class="btn btn-primary signup" type="submit" value="Accepter l'avis"/>
                            </form>
                            <form method="POST" action="index.php?uc=Inscriptionvisio&action=refuserAvis&idMedecin=<?php echo $lesAvis['idMedecin'] ?>&idVisio=<?php echo $lesAvis['idVisio'] ?>">
                                <input name="refuser" class="btn btn-primary signup" type="submit" value="Refuser l'avis"/>
                            </form>
                            <?php }?>
                            </br>
                            <br/>
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