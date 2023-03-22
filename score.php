<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score</title>
    <?php include_once('./inc/head-inc.php') ?>
</head>

<body>
    <?php
    include_once('./inc/nav-inc.php');
    ?>
    <div id="container">
        <?php
        $servername = 'localhost';
        $username = 'root';
        $password = '';

        try {
            $bdd = new PDO("mysql:host=$servername;dbname=memory_revisions", $username, $password);

            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        $request = $bdd->prepare("SELECT * FROM `score` ORDER BY score DESC");
        $request->execute();
        $result = $request->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $k => $value) :
                ?>
                    <tr>
                        <td><?= $value['username'] ?></td>
                        <td><?= $value['score'] ?></td>
                        <td><?= $value['date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>