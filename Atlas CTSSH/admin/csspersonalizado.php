include('../atlas/conexao.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

$sql = "SELECT * FROM configs";
$result = $conn -> query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $csspersonali = $row["corfundologo"];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['css'])) {
        $css = $_POST['css'];
        
        //salva no banco de dados
        $sql = "UPDATE configs SET corfundologo = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $css);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        

    } else {
        $cssContent = $csspersonali;
        // Retorna o código CSS como resposta JSON
        header('Content-Type: application/json');
        echo json_encode($cssContent);
    }
} else {
    // Responda com uma mensagem de erro se a requisição não for do tipo POST
    echo json_encode(['error' => 'Requisição inválida']);
}

?>
