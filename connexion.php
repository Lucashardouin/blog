<?php
require_once('inc/init.php');

if(isset($_GET['action']) && $_GET['action'] == 'disconnect'){
    unset($_SESSION['user']); // destruction de variable
    $_SESSION['message'] = "vous etes maintenant deconnecté(e)";
    $_SESSION['typemessage'] = 'info';
    header('location:'.URLSITE);
    exit();
} 

if(!empty($_POST)){
    $erreurs = array();

    if(empty(trim($_POST['nom'])) || empty($_POST['mdp'])){
        $erreurs[] = 'Merci de saisir les deux champs';
    }
    if(empty($erreurs)){
        $user = getUserByName($_POST['nom']);
        if(!$user){
            $erreurs[] = 'Erreur sur les identifiants';
        }
        else{
            if(!password_verify($_POST['mdp'], $user['mdp'])){
                $erreurs[] = 'Erreur sur les identifiants';
            }
            else{
                $_SESSION['user'] = $user;
                $_SESSION['message'] = 'Connexion reussie. Bienvenue ' . $_SESSION['user']['nom'];
                $_SESSION['typemessage'] = 'success';
                header('location:'.URLSITE);
                exit();
            }
        }
    }
}

$pagetitle= 'Connexion';


require_once('inc/header.php');
?>


<div class="container">
    <div class="row">
        <div class="col my-5">
            <?php if(isset($_SESSION['message'])) : ?>
                <div class="alert alert-<?php echo $_SESSION['typemessage'] ?>">
                    <?php echo $_SESSION['message']; ?>
                </div>
            <?php
                unset($_SESSION['message']); 
                endif; ?>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-lg-2 justify-content-center">
        <div class="col my-4">
            <h1>Connexion</h1>
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
                    <label for="mdp" class="form-label">Mot de passe *</label>
                    <div class="input-group">
                        <input type="password" id="mdp" name="mdp" class="form-control">
                        <span class="input-group-text" id="oeil">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</div>






<?php
require_once('inc/footer.php');
?>