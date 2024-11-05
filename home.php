<?php
//Initialize default page state.

$show_confirmation = False;

// feedback message
$form_feedback_classes = array(
  'name' => True,
  'type' => True,
  'total' => True
);

// values
$form_values = array(
  'name' => '',
  'type' => '',
  'total' => ''
);

// sticky values
$sticky = array(
  'name' => '',
  'type' => '',
  'total' => ''
);


const TYPES = array(
  1 => 'Normal',
  2 => 'Fire',
  3 => 'Water',
  4 => 'Grass',
  5 => 'Electric',
  6 => 'Ice',
  7 => 'Fighting',
  8 => 'Poison',
  9 => 'Ground',
  10 => 'Flying',
  11 => 'Psychic',
  12 => 'Bug',
  13 => 'Rock',
  14 => 'Ghost',
  15 => 'Dark',
  16 => 'Dragon',
  17 => 'Steel',
  18 => 'Fairy'
);
$db = open_sqlite_db('secure/site.sqlite');
if (isset($_POST['submit'])) {


  $form_values['name'] = trim($_POST['name']); // untrusted
  $form_values['type'] = trim($_POST['type']); // untrusted
  $form_values['total'] = trim($_POST['total']) ; // untrusted


  $form_valid = True;
  // Validate each piece of required data:

  // Name is required.
  // If $data is empty ($data == ''):
  if ($form_values['name'] == '') {
    //Mark form as invalid.
    $form_valid = False;
    //Show corrective feedback for $data.
    $form_feedback_classes['name'] = False;
  }


  if ($form_values['type'] == '') {
    // Mark form as invalid.
    $form_valid = False;
    // Show corrective feedback for $data.
    $form_feedback_classes['type'] = False;
  }

  if ($form_values['total'] == '') {

    $form_valid = False;

    $form_feedback_classes['total'] = False;
  }

  // If form data is valid:
  if ($form_valid) {

    $result = exec_sql_query(
      $db,
      "INSERT INTO pokemon (name, type, total) VALUES (:name, :type, :total);",
      array(
        ':name' => $form_values['name'], // tainted
        ':type' => $form_values['type'], // tainted
        ':total' => $form_values['total'], // tainted
      )
    );

    $show_confirmation = True;
  } else {
    // Set sticky values
    $sticky['name'] = $form_values['name'];
    $sticky['type'] = ($form_values['type']);
    $sticky['total'] = $form_values['total'];
  }
}

$result = exec_sql_query($db, 'SELECT * FROM pokemon;');
$records = $result->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Pokemon Pokedex</title>
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
</head>

<body>

<main class="pokemon">
<div class = "header">

    <h2>The Pokemon Pokedex</h2>

</div>
<div class = "flex-horiztonal">
<div class = 'pokedex'>
    <table>
      <tr>
        <th>ID</th>
        <th>Name of Pokemon</th>
        <th>Type of Pokemon</th>
        <th>Total Points</th>
      </tr>


    <?php
      foreach ($records as $record) { ?>
        <tr class="pokemon">
          <td><div class="pokemon-header">
          <p><?php echo htmlspecialchars( $record['ID']  ); ?></p> </td>
          <td><p class="pokemon-id"><?php echo htmlspecialchars( $record['name']); ?>
          </p></td>
          <td><div class="pokemon-type"><?php echo htmlspecialchars(TYPES[$record['type']]); ?></div></td>
          <td><p class="pokemon-name"><?php echo htmlspecialchars( $record['total']); ?>
        </p></td>

      </tr>
      <?php } ?>

      </table>

      </div>

<div class = "flex-vertical">
<div class = "intro">

      <p> The original Pokémon is a role-playing game based around building a small team of monsters to battle other monsters in a quest to become the best. Pokémon are divided into types, such as water and fire, each with different strengths. Battles between them can be likened to the simple hand game rock-paper-scissors. For example, to gain an advantage over a Pokémon that cannot beat an opponent’s Charizard character because of a weakness to fire, a player might substitute a water-based Pokémon. With experience, Pokémon grow stronger, gaining new abilities. By defeating Gym Leaders and obtaining Gym Badges, trainers garner acclaim. </p>
      <p>The Pokémon Trading Card Game (TCG) is a collectible card game with a goal similar to a Pokémon battle in the video game series. Players use Pokémon cards, with individual strengths and weaknesses, in an attempt to defeat their opponent by "knocking out" their Pokémon cards.</p>
      <!-- Source: https://en.wikipedia.org/wiki/Pok%C3%A9mon -->
<cite><a href="https://en.wikipedia.org/wiki/Pok%C3%A9mon"> Source</a></cite>
      </div>

      <div class = 'form'>
      <section>

      <?php if(!$show_confirmation) {?>
        <h3>Pokemon Entry</h3>

        <p>Know a Pokemon that's not on this table? Fill out this form to include it!</p>

        <form method="post" action="/" novalidate>

              <?php if(!$form_feedback_classes['name']) {?>
                <p class="feedback"> *Please provide the name of the Pokemon.*</p>
                <?php } ?>
                <div class="label-input">
                  <label for="name">Name of Pokemon:</label>
                  <input id="name" type="text" name="name" value="<?php echo $sticky['name']; ?>">
                </div>
              <br>

          <?php if(!$form_feedback_classes['type']) {?>
          <div class="feedback"> *Please select a type of the Pokemon.*</div>
          <?php } ?>
          <div class="label-input">
            <label for="type">Type of Pokemon:</label>
            <select name = "type" id="type" ?>">
              <option value="" <?php if ($form_values['type'] == '') { echo $form_feedback_classes['type']; } ?>> </option>
                <option value = 1 <?php if ($form_values['type']=='1') {echo 'selected';}?>> Normal </option>
                <option value = 2 <?php if ($form_values['type']=='2') {echo 'selected';}?>> Fire </option>
                <option value = 3 <?php if ($form_values['type']== '3') {echo 'selected';}?>> Water </option>
                <option value = 4 <?php if ($form_values['type']=='4') {echo 'selected';}?>> Grass </option>
                <option value = 5 <?php if ($form_values['type']=='5') {echo 'selected';}?>> Electric </option>
                <option value = 6 <?php if ($form_values['type']=='6') {echo 'selected';}?>> Ice </option>
                <option value = 7 <?php if ($form_values['type']=='7') {echo 'selected';}?>> Fighting </option>
                <option value = 8 <?php if ($form_values['type']=='8') {echo 'selected';}?>> Posion </option>
                <option value = 9 <?php if ($form_values['type']=='9') {echo 'selected';}?>> Ground </option>
                <option value = 10 <?php if ($form_values['type']=='10') {echo 'selected';}?>> Flying </option>
                <option value = 11 <?php if ($form_values['type']=='11') {echo 'selected';}?>> Psychic </option>
                <option value = 12 <?php if ($form_values['type']=='12') {echo 'selected';}?>> Bug </option>
                <option value = 13 <?php if ($form_values['type']=='13') {echo 'selected';}?>> Rock </option>
                <option value = 14 <?php if ($form_values['type']=='14') {echo 'selected';}?>> Ghost </option>
                <option value = 15 <?php if ($form_values['type']=='15') {echo 'selected';}?>> Dark </option>
                <option value = 16 <?php if ($form_values['type']=='16') {echo 'selected';}?>> Dragon </option>
                <option value = 17 <?php if ($form_values['type']=='17') {echo 'selected';}?>> Steel </option>
                <option value = 18 <?php if ($form_values['type']=='18') {echo 'selected';}?>> Fairy </option>
                </select>

            </div>
          <br>

            <?php if(!$form_feedback_classes['total']) {?>
                  <p class="feedback"> *Please provide the total number of points the Pokemon has.*</p>
                  <?php }?>
                  <div class="label-input">
                    <label for="total">Total Points:</label>
                    <input id="total" type="int" name="total" value="<?php echo $sticky['total']; ?>">
                  </div>
                      <br>
                <div class = "align">
                <input type="submit" value="Submit Pokemon" name='submit'> </div>
                  </form>
                </section>
                </div>
                </div>
            </div>
                <br>
            <?php }
     else { ?>
      <section>
     </div>
        <h3>Pokemon Submission Confirmation</h3>

        <p>Thank you for your entry to the Pokemon Pokedex! <strong><?php echo htmlspecialchars($form_values['name']); ?></strong> is excited to join the list! This Pokemon is a <strong> <?php if ($form_values['type']== '1') {echo 'Normal';} if ($form_values['type']== '2') {echo 'Fire';} if ($form_values['type']== '3') {echo 'Water';} if ($form_values['type']== '4') {echo 'Grass';} if ($form_values['type']== '5') {echo 'Electric';} if ($form_values['type']== '6') {echo 'Ice';} if ($form_values['type']== '7') {echo 'Fighting';} if ($form_values['type']== '8') {echo 'Poison';} if ($form_values['type']== '9') {echo 'Ground';} if ($form_values['type']== '10') {echo 'Flying';} if ($form_values['type']== '11') {echo 'Psychic';}if ($form_values['type']== '12') {echo 'Bug';} if ($form_values['type']== '13') {echo 'Rock';} if ($form_values['type']== '14') {echo 'Ghost';} if ($form_values['type']== '15') {echo 'Dark';} if ($form_values['type']== '16') {echo 'Dragon';} if ($form_values['type']== '17') {echo 'Steel';} if ($form_values['type']== '18') {echo 'Fairy';}?></strong> type and has <strong><?php echo htmlspecialchars($form_values['total']); ?></strong> total points!</p>

        <p><a href="/">Add another Pokemon</a>.</p>
      </section>

      </section>
    <?php } ?>


    </main>



</body>

</html>
