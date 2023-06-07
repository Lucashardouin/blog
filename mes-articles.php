<?php
require_once('inc/init.php');

$userId = $_SESSION['user']['id_user'];
$showAllArticle = $pdo->prepare("SELECT * FROM posts LEFT JOIN users using (id_user) WHERE id_user = :id_user");
$showAllArticle->bindValue(':id_user', $userId, PDO::PARAM_INT);
$showAllArticle->execute();
$article = $showAllArticle->fetchAll();

$showArticle = $pdo->prepare("SELECT * FROM posts LEFT JOIN users using (id_user) WHERE id_user = :id_user");
$showArticle->bindValue(':id_user', $userId, PDO::PARAM_INT);
$showArticle->execute();
$articleInfos = $showArticle->fetch();


if(array_key_exists('action', $_GET) && $_GET['action'] == 'update'){

    if(!empty($_POST)){

        $erreurs = array();

        // controle du titre
        if(empty(trim($_POST['titre']))){
            $erreurs[] = 'merci de saisir un titre';
        }

        // controle du contenu
        if(empty(trim($_POST['contenu']))){
            $erreurs[] = 'merci de saisir un contenu';
        }

        $postId = $_GET['id_post'];
        $updateArticle = $pdo->prepare("UPDATE posts SET date_post = NOW(), titre = :titre, contenu = :contenu, photo = :photo WHERE id_post = :id_post");
        $updateArticle->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $updateArticle->bindValue(':contenu', $_POST['contenu'], PDO::PARAM_STR);
        $updateArticle->bindValue(':id_post', $postId, PDO::PARAM_INT);

        
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if ($_FILES['photo']['size'] > 0) {
            $extensionFichier = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($extensionFichier, $extensionsAutorisees)) {
                $erreurs[] = 'Seuls les fichiers JPG, JPEG, PNG, GIF et WEBP sont autorisés';
            } else {
                $dossierDestination = $_SERVER['DOCUMENT_ROOT'] . URLSITE . 'photos/';
                $nomFichier = uniqid() . '_' . $_FILES['photo']['name'];
                $cheminFichier = $dossierDestination . $nomFichier;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $cheminFichier)) {
                    if (!empty($_POST['photo'])) {
                        $anciennePhoto = $dossierDestination . $_POST['photo'];
                        if (file_exists($anciennePhoto)) {
                            unlink($anciennePhoto);
                        }
                    }
        
                    // Mettre à jour le nom de la nouvelle photo
                    $_POST['photo'] = $nomFichier;
                    $updateArticle->bindValue(':photo', $_POST['photo'], PDO::PARAM_STR); // Utiliser $nomFichier pour le nom de la photo
                } else {
                    $erreurs[] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier';
                }
            }
        }else {
            // Si aucune nouvelle photo n'est téléchargée, conserver l'ancienne photo
            $updateArticle->bindValue(':photo', $_POST['photo'], PDO::PARAM_STR);
        }

        if (empty($erreurs)) {
            $updateArticle->execute();

            // Autres actions ou redirection après la mise à jour réussie de l'article
            $_SESSION['message'] = "L'article a bien été modifié";
            $_SESSION['typemessage'] = 'success';
            header('location:' . URLSITE . 'mes-articles.php');
            exit();
        }
    }
}

if (isset($_GET['delete_post'])) {
    $postId = $_GET['delete_post'];
    $deletePost = $pdo->prepare("DELETE FROM posts WHERE id_post = :postId");
    $deletePost->bindValue(':postId', $postId, PDO::PARAM_INT);
    $deletePost->execute();
    $_SESSION['message'] = "L'article a bien été supprimé";
    $_SESSION['typemessage'] = 'success';

    header('location:' . URLSITE . 'mes-articles.php' );
    exit();
}

$delete = $pdo->query("SELECT * FROM posts");
$deleteArticle = $delete->fetchAll(PDO::FETCH_ASSOC);


$pagetitle= 'Mes articles';

require_once('inc/header.php');
?>

    <?php if($article) : ?>
       
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
                <?php if(array_key_exists('action', $_GET) && $_GET['action'] == 'update') : ?>
                    <?php $postId = $_GET['id_post'];
                            $postInfo = $pdo->prepare("SELECT * FROM posts LEFT JOIN users using (id_user) WHERE id_post = :id_post");
                            $postInfo->bindValue(':id_post', $postId, PDO::PARAM_INT);
                            $postInfo->execute();
                            $showPost = $postInfo->fetch(); 
                    ?>
                <div class="row row-cols-1 row-cols-lg-2 justify-content-center">
                    <div class="col my-4">
                        <h1>Modifier l'article</h1>
                        <hr>

                        <?php if(!empty($erreurs)) : ?>
                            <div class="alert alert-danger">
                                <?php echo implode('<br>', $erreurs) ?>
                            </div>
                        <?php endif; ?>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre *</label>
                                <input type="text" id="titre" name="titre" value="<?php echo $showPost['titre'] ?? '' ?>" class="form-control">
                            </div>

                            <div class="row">
                                <div class="mt-5 d-flex flex-column">
                                    <label class="form-label" for="contenu">Contenu *</label>
                                    <textarea name="contenu" id="contenu" cols="50" rows="6" style="resize:none;"><?php echo $showPost['contenu'] ?? '' ?></textarea>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photos</label>
                                <input type="file" id="photo" name="photo" class="form-control" >
                                <img id="preview" src="<?php echo URLSITE . 'photos/' . $showPost['photo']?>" alt="Photo actuelle" style="max-width: 200px;max-height: 200px">
                            </div>

                            <div class="mb-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Modifier l'article</button>
                                <a href="mes-articles.php" style="text-decoration:none;color:inherit"><button type="button" class="btn btn-outline-dark btn-secondary text-light">Annuler</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <h2 class="text-center">Tous mes articles</h2>
        <div class="d-flex flex-wrap justify-content-center">
            <?php foreach($article as $value) : ?>
                <div class="card me-5 my-5" style="width: 16rem;">
                <?php if(!empty($value['photo'])) : ?>
                    <img src="<?php echo URLSITE . 'photos/' . $value['photo']?>" class="card-img-top" alt="<?php echo $value['photo']?>" style="width: 250px; height:150px">
                <?php endif ?>
                    <div class="card-body d-flex flex-column justify-content-end">
                        <span class="d-flex justify-content-end fst-italic ">de <?php echo $_SESSION['user']['nom'] ?> le <?php echo $value['date_post'] ?></span>
                        <h5 class="card-title fs-2"><?php echo $value['titre'] ?></h5>
                        <p class="card-text" style="word-break: break-all;"><?php echo substr($value['contenu'], 0, 50) . '...' ?></p>
                        <div class="d-flex justify-content-between">
                            <a href="article.php?continue=<?php echo $value['id_post']?>" class="btn btn-primary">Lire la suite</a>
                            <div>
                                <span><a href='?action=update&id_post=<?= $value['id_post']?>'><i class="bi bi-pen-fill text-warning"></i></a></span>
                                <span><a tabindex="0" data-href='?delete_post=<?= $value['id_post']?>' data-toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger" style="font-size: 1.5rem;"></i></a></span>  
                            </div>  
                        </div>  
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <?php else : ?>
            <h2 class="text-center">Vous n'avez pas encore d'article</h2>
    <?php endif ?>
    
    




<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer un article
            </div>
            <div class="modal-body">
                Etes-vous sur de supprimer cette article ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>




<?php
require_once('inc/footer.php');
?>