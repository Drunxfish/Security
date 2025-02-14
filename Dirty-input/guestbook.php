<?php


// SS
session_start();

// Connectie
$servername = "127.0.0.1";
$username = "root";
$password = "";
$db = "leaky_guest_book";
$conn;


try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
} catch (Exception $e) {
    die("Failed to open database connection, did you start it and configure the credentials properly?");
}

// unieke sessie token genereren
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];


// Form behandeling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $text = $_POST['text'];
    $color = $_POST['color'];


    // valideer email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Geen valide email')</script>";
        exit;
    }


    // entries opnemen
    $conn->query(
        "INSERT INTO entries (email, color, text) VALUES ('$email', '$color', '$text');"
    );
}



?>
<html>

<head>
    <title>Leaky-Guestbook</title>
    <style>
        body {
            width: 100%;
        }

        .body-container {
            background-color: aliceblue;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 100px;
            padding-right: 100px;
            padding-bottom: 20px;
        }

        .heading {
            text-align: center;
        }

        .disclosure-notice {
            color: lightgray;
        }
    </style>
</head>

<body>
    <div class="body-container">
        <h1 class="heading">Gastenboek 'De lekkage'</h1>
        <form action="guestbook.php" method="post">
            <input type="email" name="email"><br />
            <input type="hidden" value="red" name="color">
            <textarea name="text" minlength="4"></textarea><br />
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit">
        </form>
        <hr />
        <?php


        // gegevens weergeven van entries
        $result = $conn->query("SELECT `email`, `text`, `color`, `admin` FROM `entries`");
        foreach ($result as $row) {
            print "<div style=\"color: " . $row['color'] . "\">Email: " . $row['email'];


            // als admin bestaat krijg hij een Kroon
            if ($row['admin']) {
                print '&#9812;';
            }
            print ": " . $row['text'] . "</div><br/>";
        }
        ?>
        <hr />
        <div class="disclosure-notice">
            <p>
                Hierbij krijgt iedereen expliciete toestemming om dit Gastenboek zelf te gebruiken voor welke doeleinden
                dan
                ook.
            </p>
            <p>
                Onthoud dat je voor andere websites altijd je aan de princiepes van
                <a href="https://en.wikipedia.org/wiki/Responsible_disclosure" target="_blank"
                    style="color: lightgray;">
                    Responsible Disclosure
                </a> wilt houden.
            </p>
        </div>
    </div>
</body>

</html>