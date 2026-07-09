<?php


include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $csspersonali = $row["corfundologo"];
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["css"])) {
        $css = $_POST["css"];
        $sql = "UPDATE configs SET corfundologo = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $css);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    } else {
        $cssContent = $csspersonali;
        header("Content-Type: application/json");
        echo json_encode($cssContent);
    }
} else {
    echo json_encode(["error" => "Requisição inválida"]);
}

?>