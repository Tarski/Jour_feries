<?php
session_start();
$erreurAuth = false;

/* Réinitialise les variables de session*/
if (isset($_POST['reset'])) {
  unset($_SESSION['login']);
  unset($_SESSION['cpt']);
}

/* charge le tableau des utilisateurs et leur mot de passe
  depuis le fichier ./users , convertit en tableau
   avec json_decode(.., true)
 */
$users = json_decode(file_get_contents('users'), true);

/* Crée un compte */
$newLogin = $_POST['newLogin'] ?? '';
$newMdp = $_POST['newMdp'] ?? '';
$newMdpVerif = $_POST['newMdp-verif'] ?? '';

$msgErreurAuth = "";
$msgNewLogin = $msgNewMdpVerif = $msgNewMdp = "";

if (!empty($newLogin)) {
    if (empty($newMdp) || strlen($newMdp) < 5 ) {
      $msgNewMdp = "Entrez un mot de passe d'au moins 5 caractères";
    } elseif (empty($newMdpVerif)) {
      $msgNewMdpVerif = "Répétez le mot de passe";
    } elseif ($newMdp != $newMdpVerif) {
      $msgNewMdpVerif = "Les mots de passe ne correspondent pas";
    } else {//C'est bon
      /* Si $newLogin est déjà pris */
      if (isset($users[$newLogin])) {
        $msgNewLogin = "Login déjà pris, désolé.";
      } else {
        $mdpHash = password_hash($newMdp, PASSWORD_DEFAULT);
        $users[$newLogin] = $mdpHash;
        /* Met à jour le tableau sérialisé */
        $resEcriture = file_put_contents("users", json_encode($users)."\n");
        if (!$resEcriture) {
          $msgErreurAuth = "Erreur d'entrée/sortie";
        } else {
          $_SESSION['login'] = $newLogin;
          $_SESSION['cpt'] = 0;
          $newLogin = $newMdp = $newMdpVerif = '';
        }
      }
    }
}
/* Si déjà loggé */
if (isset($_SESSION['login'])) {
  $_SESSION['cpt']++;
  $nbVisites = $_SESSION['cpt'];
} else if (isset($_POST['login']) || isset($_POST['mdp'])){/*
Tentative de login */
  /* i Les champs sont remplis  */
  if (isset($_POST['login']) && isset($_POST['mdp'])) {
    /*et l'utilisateur existe*/
    if (isset($users[$_POST['login']])) {
      /* Le mot de passe fourni correspond */
      if (password_verify($_POST['mdp'], $users[$_POST['login']])) {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['cpt'] = 1;
        $nbVisites = $_SESSION['cpt'];
      } else {
        $erreurAuth = true;
        $msgErreurAuth = "Nom d'utilisateur ou mot de passe incorrect";
      }
    } else {
        $erreurAuth = true;
        $msgErreurAuth = "Nom d'utilisateur ou mot de passe incorrect";
    }
  } else {
      $erreurAuth = true;
      $msgErreurAuth = "Veuillez entrer un nom d'utilisateur";
    }
}

 ?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,
initial-scale=1, shrink-to-fit=no">
 <title>Exos : authentification</title>
  </head>
  <body>

    <h1>Authentification et session</h1>
<?php

/* Session ouverte */
if (isset($_SESSION['login'])) {
  echo "Bonjour ".$_SESSION['login'].", c'est votre $nbVisites ème visite.";
  echo '
  <form action="index.php" method="post">
    <input type="submit" name="reset" value="Réinitialiser">
  </form>';
}
?>
<?php
if (!isset($_SESSION['login'])) {
echo <<<HTML
    <h2>S'identifier</h2>
    <form action="index.php" method="post">
      <label > Login :
      <input type="text" required name="login"></label>$msgErreurAuth</br>
      <label > Mot de passe :
      <input type="password" required name="mdp"></label></br>
      <input type="submit" value="submit">
    </form>

    <h2>Créer un compte</h2>
    <form action="index.php" method="post">
      <label > Login :
      <input type="text" required name="newLogin"
value="$newLogin">$msgNewLogin</label></br>
      <label > Mot de passe :
      <input type="password" required name="newMdp"
value="$newMdp">$msgNewMdp</label></br>
      <label> Confirmer le mot de passe :
      <input type="password" required name="newMdp-verif"
value="$newMdpVerif">$msgNewMdpVerif</label></br>
      <input type="submit" value="submit">
    </form>

HTML;
  }
  ?>
  </body>
  </html>