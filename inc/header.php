<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo URLSITE ?>/assets/css/style.css">
    <title>Blog - <?php echo $pagetitle ?> </title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo URLSITE ?>">Blog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">

                    <li class="nav-item">
                    <a class="nav-link <?php if($pagetitle =='Accueil') echo 'active' ?>" aria-current="page" href="<?php echo URLSITE ?>">Accueil</a>
                    </li>
                    <?php if (!isConnected()) : ?>
                        <li class="nav-item">
                        <a class="nav-link <?php if($pagetitle =='Inscription') echo 'active' ?>" aria-current="page" href="<?php echo URLSITE ?>inscription.php">Inscription</a>
                        </li>

                        <li class="nav-item">
                        <a class="nav-link <?php if($pagetitle =='Connexion') echo 'active' ?>" aria-current="page" href="<?php echo URLSITE ?>connexion.php">Connexion</a>
                        </li>

                    <?php else : ?>

                        <li class="nav-item">
                            <a class="nav-link <?php if($pagetitle =='Mes articles') echo 'active' ?>" aria-current="page" href="<?php echo URLSITE ?>mes-articles.php">Mes articles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($pagetitle =='Ajouter un article') echo 'active' ?>" aria-current="page" href="<?php echo URLSITE ?>add-article.php">Ajouter un article</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?php echo URLSITE ?>connexion.php?action=disconnect">Deconnexion</a>
                        </li>
                        
                    <?php endif; ?>
                </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>

