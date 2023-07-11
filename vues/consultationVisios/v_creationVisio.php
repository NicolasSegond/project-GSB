<!DOCTYPE html>
<html lang="fr">

<head>
    <title>GSB -extranet</title>
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
                            <legend>Création d'une visio conference</legend>
                            <form method="post" action="index.php?uc=Inscriptionvisio&action=validerCreation" enctype="multipart/form-data">
                                <input name="nom" class="form-control" type="text" placeholder="nom de la visio" />
                                <input name="objectif" class="form-control" type="text" placeholder="objectif de la visio"/>
                                <input name="url" class="form-control" type="text" placeholder="url de la visio" />
                                <input name="dateVisio" class="form-control" type="date" placeholder="Date de la visio" />
                                <br>
                                <input type="submit" name="creer" class="btn btn-primary signup" value="Créer" />
                                <a href="index.php?uc=Inscriptionvisio&action=consultation" class="btn btn-primary signup">Retour</a>
                            </form>
                            </br>

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