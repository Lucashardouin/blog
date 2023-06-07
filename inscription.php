<?php
require_once('inc/init.php');

if(!empty($_POST)){

    $erreurs = array();

    // controle du nom
    if(empty(trim($_POST['nom']))){
        $erreurs[] = 'merci de saisir votre nom';
    }else{
        if(getUserByName($_POST['nom'])){
            $erreurs[] = 'ce nom est deja utiliser, merci d\'en choisir un autre';
        }
    }

    // controle de l'email
    if(empty(trim($_POST['email']))){
        $erreurs[] = 'merci de saisir votre email';
    }else{
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $erreurs[] = 'Adresse mail incorrecte';
        }
        if(getUserByName($_POST['email'])){
            $erreurs[] = 'cette email est deja utiliser, merci d\'en choisir une autre';
        }
    }

    // controle du mdp
    $pattern = '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9_]{8,20}#';
    if(empty(trim($_POST['mdp']))){
        $erreurs[] = 'merci de saisir votre mot de passe';
    }else{
        if(!preg_match($pattern,$_POST['mdp'])){
            $erreurs[] = 'le mot de passe doit contenir entre 8 et 20 caracteres dont au moins une minuscule, une majuscule et un chiffre';
        }
    }

    if(empty($erreurs)){
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        goSQL(" INSERT INTO users (nom, email, mdp) VALUES (:nom, :email, :mdp)", $_POST);
        $_SESSION['message'] = 'Inscription reussi, vous pouvez vous connecter';
        $_SESSION['typemessage'] = 'success';
        header('location:'.URLSITE.'connexion.php');
        exit();
    }
}


$pagetitle= 'Inscription';

require_once('inc/header.php');
?>

<div class="container">
    <div class="row row-cols-1 row-cols-lg-2 justify-content-center">
        <div class="col my-4">
            <h1>Inscription</h1>
            <hr>

            <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger">
                    <?php echo implode('<br>', $erreurs) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" id="nom" name="nom" value="<?php echo $_POST['nom'] ?? '' ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="text" id="email" name="email" value="<?php echo $_POST['email'] ?? '' ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe *</label>
                    <div class="input-group">
                        <input type="password" id="mdp" name="mdp" class="form-control">
                        <span class="input-group-text" id="oeil">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
</div>





<?php
require_once('inc/footer.php');
?>