<?php
require('card.php');

// function Score($nb , $cpt)
// {
//     $nb / 

// }


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

function retournerCarteTrue($i)
{
    if ($_SESSION['trueCartes'] != null) {
        if (comparerCarte($i)) {
            $_SESSION['carte'][0]->set_state(true);
            $_SESSION['carte'][1]->set_state(true);
            array_push($_SESSION['trueCartes'], $_SESSION['carte']);
            // header("refresh: 0");
        }
    } else {
        $_SESSION['trueCartes'] = [];
    }
}

function comparerCarte($i)
{

    if (isset($_SESSION['carte'])) {
        if (count($_SESSION['carte']) < 2) {
            if (clickedCarte($i)) {
                $i->set_state(true);
                array_push($_SESSION['carte'], $i);
            }
        } else {
            if ($_SESSION['carte'][0]->img_face_up == $_SESSION['carte'][1]->img_face_up) {
                if (isset($_SESSION['trueCartes'])) {
                    $_SESSION['carte'][0]->set_state(true);
                    $_SESSION['carte'][1]->set_state(true);
                } else {
                    $_SESSION['trueCartes'] = [];
                }
                array_push($_SESSION['trueCartes'], $_SESSION['carte']);
                $_SESSION['carte'] = [];
            } else {
                $_SESSION['carte'][0]->set_state(false);
                $_SESSION['carte'][1]->set_state(false);
                $_SESSION['carte'] = [];
            }
        }
    } else {
        $_SESSION['carte'] = [];
    }
}





function AfficherCarte($nb)
{ ?>
    <form method="GET">
        <?php
        $card = CreateCarte($nb);
        $tab = melangerCarte($nb, $card);
        foreach ($tab as $i) {
            comparerCarte($i);
            if ($i->get_state() == false) { ?>

                <button type="submit" value="<?= $i->get_id_card() ?>" name="id">
                    <img src=<?php echo $i->get_img_face_down(); ?> height="100px" width="50px">
                </button>
            <?php } else {
            ?>
                <img src=<?php echo $i->get_img_face_up(); ?> height="100px" width="50px">
        <?php }
        }
        ?>
    </form> <?php
        }
            ?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>memory</title>
</head>

<body>
    <?php
    $level = 3;
    $cpt = 0;
    AfficherCarte($level);

    if ($_GET['id']) {
        $_SESSION['coup']++;
        header('Refresh: 1; URL=memory.php');
        var_dump($_SESSION);
    }

    if (isset($_SESSION['trueCartes'])) {
        if (count($_SESSION['trueCartes'])  === 3) {
            echo "CONGRATULATION U WIN !";
        }
    }

    if (isset($_POST['reset'])) {
        session_destroy();
        $_SESSION['coup'] = 0;
        header("Location: ./memory.php");
    }


    ?>
    <form method="POST">
        <button class="button" type="submit" name="reset">Reinitialiser la partie</button>
    </form>

</body>