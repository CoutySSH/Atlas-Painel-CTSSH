<?php


include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
error_reporting(0);
if ($_POST["action"] == "Ativado") {
    $sql = "UPDATE whatsapp SET ativo = '1'";
    if (mysqli_query($conn, $sql)) {
        echo "Tabela whatsapp atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar tabela whatsapp: " . mysqli_error($conn);
    }
} else {
    if ($_POST["action"] == "Desativado") {
        $sql = "UPDATE whatsapp SET ativo = '0'";
        if (mysqli_query($conn, $sql)) {
            echo "Tabela whatsapp atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar tabela whatsapp: " . mysqli_error($conn);
        }
    } else {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            mysqli_set_charset($conn, "utf8mb4");
            $stmt = $conn->prepare("SELECT * FROM mensagens WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if (0 < $result->num_rows) {
                $row = $result->fetch_assoc();
                $detalhes = ["mensagem" => $row["mensagem"], "funcao" => $row["funcao"], "ativo" => $row["ativo"], "hora" => $row["hora"]];
                header("Content-Type: application/json");
                echo json_encode($detalhes);
                exit;
            }
        } else {
            echo "<script>window.location.href='whatsconect.php';</script>";
        }
    }
}

?>