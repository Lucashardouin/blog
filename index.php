<?php
require_once('inc/init.php');

$requete = $pdo->query("SELECT * FROM posts LEFT JOIN users USING (id_user)");
$article = $requete->fetchAll(PDO::FETCH_ASSOC);

$pagetitle= 'Accueil';

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
</div>

<h2 class="text-center">Tous les articles</h2>
<?php if($article) : ?>
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
                    <a href="article.php?continue=<?php echo $value['id_post']?>" class="btn btn-primary">Lire la suite</a>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    
<?php else : ?>
    <h2>Il n'y a aucun article</h2>
<?php endif ?>






<?php
require_once('inc/footer.php');
?>