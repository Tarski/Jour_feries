<h2>Jour de la semaine, jours fériés</h2>
  <?php
    /* Tableau des jours fériés */
    $joursFeries = array(
      "01-01" => "Nouvel an : 1er janvier",
      "01-05" => "1er mai",
      "08-05" => "8 mai",
      "10-05" => "Ascension : 10 mai",
      "14-07" => "14 juillet",
      "15-08" => "Assomption : 15 aout",
      "11-11" => "Armistice 14-18 : 11 novembre",
      "25-12" => "Noël : 25 décembre"
    );
    /* Tableau indexés des noms de jours de la semaine*/
    $joursSemaine = ['dimanche', 'lundi', 'mardi', 'mercredi',
'jeudi', 'vendredi', 'samedi'];

    $annee = $_POST['annee'] ?? '';
    $jourF = $_POST['jourFerie'] ?? '';
    /* Vérification (rapide) des données */
    if ($annee != '' && is_numeric($annee) && $jourF != '') {
      echo "Le ".$joursFeries[$jourF]." de $annee : ";

      /* Timestamp du jour férié demandé*/
      $dateFerie = strtotime("$jourF-$annee");
      /* Récupère son index dans la semaine */
      $idxJour = date('w', $dateFerie);
      echo $joursSemaine[$idxJour];
    }
  ?>
  <form action="dates.php" method="post">
    <label>Jour férié:
      <select name="jourFerie">

      <?php
          foreach ($joursFeries as $date => $nom) {
            echo "<option value='$date'>$nom</option>";
          }
         ?>
      </select>
    <label>Année :
    <input type="number" name="annee"></br></label>
    <input type="submit" value="submit">
  </form>
