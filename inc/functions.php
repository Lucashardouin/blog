<?php 

function isConnected(){
    return isset($_SESSION['user']);
}

function goSQL($requete, $params =array()){

    global $pdo;
    $stmt = $pdo->prepare($requete);

    if(!empty($params)){
        foreach($params as $index => $valeur){
            $params[$index] = htmlspecialchars(trim($valeur));
            // $stmt->bindValue($index, $params[$index], PDO::PARAM_STR);
        }
    }
    $stmt->execute($params);
    // $stmt->execute()
    return $stmt;
    var_dump($stmt);
    
}


function getUserByName($nom){
    $stmt = goSQL("SELECT * FROM users WHERE nom = :nom", array('nom' => $nom));
    if($stmt->rowCount() == 0){
        return false;
    }else{
        return $stmt->fetch();
    }
}

function getUserByEmail($email){
    $stmt = goSQL("SELECT * FROM users WHERE email = :email", array('email' => $email));
    if($stmt->rowCount() == 0){
        return false;
    }else{
        return $stmt->fetch();
    }
}