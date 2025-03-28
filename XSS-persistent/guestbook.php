<?php

session_start();
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

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];



// #Voorkom XSS persistent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && $_POST['token'] === $token) {
    $email = $_POST['email'];
    $text = $_POST['text'];
    $color = $_POST['color'];


    $conn->prepare(
        "INSERT INTO `entries`(`email`, `color`, `text`) VALUES (:email, :color, :text)"
    )->execute([
                ':email' => $email,
                ':color' => $color,
                ':text' => $text
            ]);
}

// Weergave
$data = $conn->query("SELECT * FROM entries")->fetchAll();
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
            Email: <input type="email" name="email"><br />
            <input type="hidden" value="red" name="color">
            Bericht: <textarea name="text" minlength="4"></textarea><br />
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit">
        </form>
        <hr />
        <?php if (isset($data) && $data): ?>
            <?php foreach ($data as $row): ?>
                <div
                    style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; background-color: <?= htmlspecialchars($row['color']); ?>;">
                    <strong>Email:</strong> <?= htmlspecialchars($row['email']); ?>
                    <?= $row['admin'] ? '<span style="font-size: 1.2em;">&#9812;</span>' : ''; ?>
                    <br /><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['text'])); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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