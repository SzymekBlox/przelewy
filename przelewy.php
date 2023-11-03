<?php
// Połączenie z bazą danych MySQL
$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("<p style="color: red">Błąd połączenia z bazą danych: </p>" . $conn->connect_error);
}

// Obsługa operacji bankowych
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $akcja = $_POST["akcja"];
    $kwota = $_POST["kwota"];
    $id = $_POST["id"];
    $id2 = $userRow['id'];

    if ($akcja == "przelew") {
        // Rozpocznij transakcję
        $conn->begin_transaction();

        // Realizuj przelew z jednego konta na drugie
        $sql1 = "UPDATE user SET saldo = saldo - $kwota WHERE id = $id2";
        $sql2 = "UPDATE user SET saldo = saldo + $kwota WHERE id = $id";

        if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
            // Zatwierdź transakcję
            $conn->commit();
            echo "<p style="color: red">Przelew został zrealizowany.</p>";
        } else {
            // Wycofaj transakcję w przypadku błędu
            $conn->rollback();
            die("<p style="color: red">Wystąpił błąd systemu.</p>");
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>MTBank - Przelewy</title>
</head>
<body>
    <h1>Przelewy</h1>
    <form method="post" action="przelewy.php">
        <label for="id">ID:</label>
        <input type="number" name="id" id="id" placeholder="ID"><br><br>
        <label for="kwota">Kwota:</label>
        <input type="number" name="kwota" id="kwota" placeholder="Kwota"><br><br>
        <button type="submit" name="akcja" value="przelew">Przelej pieniądze</button>
    </form>
</body>
</html>
