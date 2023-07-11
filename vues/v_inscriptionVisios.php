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
                        <a href="index.php?uc=Inscriptionvisio&action=Retour" class="btn btn-primary signup">Retour</a>
                        <br>
                        <br>
                            <img src="images/visioConference.jpg" alt="Image d'une visio conférence" width="200" height="200" ></img>
                            
                            <?php while($produits = $prods->fetch()) { ?>

                                <div style="width: 100%; height:auto; margin: 50px 0px;">
                                    <br>Nom de la visio : <span><?php echo $produits['nomVisio']?></span>
                                    <br>Numéro de la visio : <span> <?php echo $produits['id']?></span>
                                </div>
                                <hr style="width: auto; background-color: black;">
                            <?php } ?>

                            <form method="post" action="index.php?uc=Inscriptionvisio&action=Confirmation">
                            Veuillez entrer le numéro de la visio : <input type="number" name="IdVisio" class="number" value="numero de visio"/>
                            <br>
                            <br>
                            Veuillez entrer votre mail : <input type ="email" name="mailMedecin" class="text" placeholder="mail Medecin" size ="20"/>
                            <br>
                            <br>
                            <input type="submit" name="Confirmer l'inscription" class="btn btn-primary signup" value="Confirmer l'inscription"/>


                        </form>



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