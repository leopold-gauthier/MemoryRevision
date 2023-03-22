<?php
require('card.php');

function CreateCarte($nb)
{
    for ($i = 0; $i < ($nb * 2); $i += 2) {
        $carteUp = 'image/' . $i . '.png';
        $carteDown = 'image/hide.webp';
        $card[$i] = new Card($i, $carteDown, $carteUp, false);
        $card[$i + 1] = new Card($i + 1, $carteDown, $carteUp, false);
    }
    return $card;
}

function melangerCarte($nb, $card)
{
    if (empty($_SESSION['ordre'])) {
        $_SESSION['ordre'] = [];
        for ($i = 0; $i < ($nb * 2); $i++) {
            array_push($_SESSION['ordre'], $card[$i]);
        }
        shuffle($_SESSION['ordre']);
    }
    return $_SESSION['ordre'];
}

function clickedCarte($i)
{
    if (isset($_GET['id'])) {
        if ($_GET['id'] == $i->get_id_card()) {
            return true;
        }
    }
}

function comparerCarte($i)
{

    if (isset($_SESSION['carte'])) {
        if (count($_SESSION['carte']) < 2) {
            if (clickedCarte($i)) {
                $i->set_state(true);
                array_push($_SESSION['carte'], $i);
                // header('refresh 1');
            }
            // header('refresh 1');
        } else {
            if ($_SESSION['carte'][0]->img_face_up == $_SESSION['carte'][1]->img_face_up) {
                if (isset($_SESSION['trueCartes'])) {
                    $_SESSION['carte'][0]->set_state(true);
                    $_SESSION['carte'][1]->set_state(true);
                    header('refresh 1');
                } else {
                    $_SESSION['trueCartes'] = [];
                    header('refresh 1');
                }
                array_push($_SESSION['trueCartes'], $_SESSION['carte']);
                $_SESSION['carte'] = [];
                header('refresh 1');
            } else {
                $_SESSION['carte'][0]->set_state(false);
                $_SESSION['carte'][1]->set_state(false);
                $_SESSION['carte'] = [];
                header('refresh 1');
            }
            if (isset($_SESSION['nbcoup'])) {
                $_SESSION['nbcoup']++;
                header('refresh 1');
            } else {
                $_SESSION['nbcoup'] = 1;
            }
        }
    } else {
        $_SESSION['carte'] = [];
        header('memory.php');
    }
}





function AfficherCarte($nb)
{ ?>
    <form method="GET">
        <div>
            <?php
            $card = CreateCarte($nb);
            $tab = melangerCarte($nb, $card);
            foreach ($tab as $i) {
                comparerCarte($i);
                if ($i->get_state() == false) { ?>

                    <button type="submit" value="<?= $i->get_id_card() ?>" name="id">
                        <img src=<?php echo $i->get_img_face_down(); ?> height="200px" width="133px">
                    </button>
                <?php } else {
                ?>
                    <img src=<?php echo $i->get_img_face_up(); ?> height="200px" width="133px">
            <?php }
            }
            ?>
        </div>
    </form> <?php
        }
            ?>

<!-- HTML -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory</title>
    <?php include_once('./inc/head-inc.php') ?>
</head>

<body>
    <?php
    include_once('./inc/nav-inc.php');
    ?>
    <div id="container">
        <?php
        if (!isset($_SESSION['level'])) { ?>
            <form method="post">
                <select name="level">
                    <option value="3">Level 1 </option>
                    <option value="4">Level 2</option>
                    <option value="6">Level 3</option>
                </select>
                <button class="button" type="submit">Start</button>
            </form>

            <?php

            if (isset($_POST['level'])) {
                $_SESSION['level'] = $_POST['level'];
                header('Location: memory.php');
            }
        } else { ?>

            <?php

            AfficherCarte($_SESSION['level']);

            if (isset($_SESSION['trueCartes'])) {
                if (count($_SESSION['trueCartes']) >= intval($_SESSION['level'])) {
                    $score = $_SESSION['nbcoup'] / intval($_SESSION['level']);
            ?>
                    <div id="endgame">
                        <form method="POST">
                            <h1>Congratulation you win !</h1>
                            <p>You want to register ?</p>
                            <label for="username">Username :</label>
                            <input id="username" name="username" type="text">
                            <label for="score">Score :</label>
                            <input type="text" name="score" value="<?= $score ?>" disabled="disabled" />
                            <input type="submit" name="register" value="Enregistrer">
                            <button class="button" type="submit" name="reset">Réesayez</button>
                        </form>
                    </div>
            <?php
                }
            }
            ?>
            <form method="POST">
                <button class="button" type="submit" name="reset">Reinitialiser la partie</button>
            </form>
        <?php
        }


        ?>

        <?php
        ?>

    </div>
</body>
<?php
// ENREGISTREMENT
if (isset($_POST['register'])) {
    $servername = 'localhost';
    $username = 'root';
    $password = '';

    try {
        $bdd = new PDO("mysql:host=$servername;dbname=memory_revisions", $username, $password);

        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    $request = $bdd->prepare("INSERT INTO `score` (`username`, `score` , `date` ) VALUES (?, ? , ?)");
    $request->execute([$_POST['username'], $score, date('d/m/y h:i:s')]);
    if ($request == true) {
        session_destroy();
        header("Location: ./score.php");
    }
}


// RESET
if (isset($_POST['reset'])) {
    session_destroy();
    $_SESSION['coup'] = 0;
    header("Location: ./memory.php");
}
