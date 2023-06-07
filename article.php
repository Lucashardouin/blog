<?php
require_once('inc/init.php');

if($_POST){
    $erreurs = array();

    if(empty(trim($_POST['contenu']))){
        $erreurs[] = 'Votre commentaire est vide';
    }

    if(empty($erreurs)){
        $_POST['id_user'] = $_SESSION['user']['id_user'];
        $_POST['id_post'] = $_GET['continue'];
        goSQL("INSERT INTO comments (id_user, id_post, date_comment, contenu) VALUES (:id_user, :id_post, NOw(), :contenu)", $_POST);
        header('location:'.$_SERVER['REQUEST_URI']);
        exit();
        $_SESSION['message'] = "Votre commentaire a bien été ajouté";
        $_SESSION['typemessage'] = 'success';  

    }
}



$pagetitle= 'Article';

require_once('inc/header.php');
?>
<?php if (isset($_GET['continue'])) :
    $postId = $_GET['continue'];
    $showArticle = $pdo->prepare("SELECT * FROM posts LEFT JOIN users USING (id_user) WHERE id_post = :id_post");
    $showArticle->bindValue(':id_post', $postId, PDO::PARAM_INT);
    $showArticle->execute();
    $article = $showArticle->fetch();

    $showCom = $pdo->prepare("SELECT comments.contenu, users.nom, date_comment FROM comments LEFT JOIN users USING (id_user) LEFT JOIN posts USING (id_post) WHERE id_post = :id_post");
    $showCom->bindValue(':id_post', $postId, PDO::PARAM_INT);
    $showCom->execute();
    $com = $showCom->fetchAll();
?>
    <?php if($article) : ?>
        <div class="mx-5">
            <h2><?php echo $article['titre'] ?></h2>
            <p><?php echo $article['contenu']?><a href="article.php?continue=<?php echo $article['id_post']?>"></a></p>
            <img src="<?php echo URLSITE . 'photos/' . $article['photo']?>" alt="">
            <span class="d-flex justify-content-end">ecrit par <?php echo ($article['nom'] == NULL) ? 'Utilisateur supprimé' : $article['nom']  ?> le <?php echo $article['date_post'] ?></span>
        </div>
        <div class="ms-5">
            
            <h3>Commentaires</h3>
            
            <?php if($com) :?>
            <?php foreach($com as $value) : ?>
                <div class="row">
                    <div class="col-4 my-5">
                        <p><?php echo $value['contenu'] ?></p>
                        <span class="d-flex justify-content-end">ecrit par <?php echo ($value['nom'] == NULL) ? 'Utilisateur supprimé' : $value['nom']  ?> le <?php echo $value['date_comment'] ?></span>
                    </div>
                </div>
            <?php endforeach ?>
            <?php else : ?>
                <h4>Il n'y a aucun commentaire</h4>
            <?php endif ?>
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
        </div>
        <?php if(isConnected()) : ?>
            <h4 class="ms-5">Ajouter un commentaire</h4>

            <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger">
                    <?php echo implode('<br>', $erreurs) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="ms-5">
                <textarea name="contenu" id="contenu" cols="50" rows="5" style="resize: none;" placeholder="votre commentaire..."><?php echo $_POST['contenu'] ?? '' ?></textarea>
                <div class="mb-3 ">
                    <button type="submit" class="btn btn-primary">Ajouter le commentaire</button>
                </div>
            </form>
            
        <?php endif ?>
    <?php endif ?>
    

<?php endif ?>






<?php
require_once('inc/footer.php');
?>