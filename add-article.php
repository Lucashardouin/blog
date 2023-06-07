<?php
require_once('inc/init.php');

if(!isConnected()){ 
    header('Location:' . URLSITE . 'connexion.php');
    exit();
}

if(!empty($_POST)){

    $erreurs = array();

    if(empty(trim($_POST['titre']))){
        $erreurs[]= "Le titre est obligatoire."; }

    if(empty(trim($_POST['contenu']))){
    $erreurs[]= "L'article ne peut pas vide."; }

    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!empty($_FILES['photo']['name'])) {
        $extensionFichier = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($extensionFichier, $extensionsAutorisees)) {
            $erreurs[] = 'Format de photo incorrect. Merci de choisir une image JPEG, JPG, PNG ou WEBP.';
        }
    }

    if(empty($erreurs)){ 
        // Gérer l'éventuelle photo 
            if(!empty($_FILES['photo']['name'])){ 
                $nomfichier =  uniqid() . '_' . $_FILES['photo']['name'];
                $emplacement = $_SERVER['DOCUMENT_ROOT'] . URLSITE . 'photos/';
                // $emplacement = __DIR__ . 'photos/';
                // move_uploaded_file($_FILES['photo']['tmp_name'], $emplacement.$nomfichier);
                move_uploaded_file($_FILES['photo']['tmp_name'],$emplacement.$nomfichier);
            }
        // Insertion en BDD
        global $nomfichier;
            $_POST['id_user'] = $_SESSION['user']['id_user'];
            $_POST['photo'] = $nomfichier;

            goSQL("INSERT INTO posts  (id_user, date_post, titre, contenu, photo) VALUES (:id_user, NOW(), :titre, :contenu, :photo)", $_POST);

            $_SESSION['message'] = "L'article a été ajouté.";
            $_SESSION['typemessage'] = 'success';

        // Rédirection sur la mm page
        header("Location:" . URLSITE . 'mes-articles.php');
        exit();
    }

}


$pagetitle= 'Ajouter un article';

require_once('inc/header.php');
?>

<?php if(isConnected()) : ?>

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
            <h1>Ajouter</h1>
            <hr>

            <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger">
                    <?php echo implode('<br>', $erreurs) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre *</label>
                    <input type="text" id="titre" name="titre" value="<?php echo $_POST['titre'] ?? '' ?>" class="form-control">
                </div>

                <div class="row">
                    <div class="mt-5 d-flex flex-column">
                        <label class="form-label" for="contenu">Contenu *</label>
                        <textarea name="contenu" id="contenu" cols="50" rows="6" style="resize:none;"><?php echo $_POST['contenu'] ?? '' ?></textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Photos</label>
                    <input type="file" id="photo" name="photo" class="form-control" >
                </div>

                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Ajouter l'article</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php else :
    header('location:'.URLSITE.'connexion.php');
    exit();
endif; ?>




<?php
require_once('inc/footer.php');
?>