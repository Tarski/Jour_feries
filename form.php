<?php
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$age = $_POST['age'] ?? '';

/* Vérif*/
$msgAge ="";
if ($age != '') {
  if ($age < 2 || $age >= 120) {
    $msgAge = "<h2>Merci d'entrez un age correct</h2>";
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
 <title>Exos : message</title>
  </head>
  <body>
    <?php
      if ($msgAge != "") {
        echo $msgAge;
      } else {
        if ($nom != '' || $prenom != '') {
          echo "<h2>Bonjour $prenom $nom.";
          if ($age == '') {
            echo "Entrez un âge.";
          } else {
            if ($age >= 18) {
              echo "Vous êtes majeur.";
            } else {
              echo "Vous êtes mineur.";
            }
            echo "</h2>";
          }
        }
      }
     ?>
    <form action="formMessage.php" method="post">
      <label > Prénom :
      <input type="text" required name="prenom" value="<?php echo
$prenom;?>"></label>
      <label > Nom :
      <input type="text" required name="nom" value="<?php echo $nom;?>"></label>
      <label > Age :
      <input type="text" name="age" value="<?php echo $age;?>"></label>
      <input type="submit" value="submit">
    </form>
  </body>
  </html>