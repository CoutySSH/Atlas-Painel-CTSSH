<?php


error_reporting(0);
session_start();
include "header2.php";
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
unset($_SESSION["addquantidade"]);
$login = $_SESSION["login"];
$senha = $_SESSION["senha"];
$sql4 = "SELECT * FROM accounts WHERE login = '" . $login . "' AND senha = '" . $senha . "'";
$result4 = $conn->query($sql4);
if (0 < $result4->num_rows) {
    while ($row4 = $result4->fetch_assoc()) {
        $_SESSION["iduser"] = $row4["id"];
        $_SESSION["byid"] = $row4["byid"];
    }
}
$sql5 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$result5 = $conn->query($sql5);
if (0 < $result5->num_rows) {
    while ($row5 = $result5->fetch_assoc()) {
        $_SESSION["validade"] = $row5["expira"];
        $_SESSION["limite"] = $row5["limite"];
        $_SESSION["tipoconta"] = $row5["tipo"];
    }
}
if ($_SESSION["tipoconta"] == "Credito") {
    echo "<script>window.location.href = \"adicionar.php\";</script>";
}
$sql6 = "SELECT * FROM accounts WHERE id = '" . $_SESSION["byid"] . "'";
$result6 = $conn->query($sql6);
if (0 < $result6->num_rows) {
    while ($row6 = $result6->fetch_assoc()) {
        $_SESSION["valorrevenda"] = $row6["valorrevenda"];
        $_SESSION["access_token"] = $row6["access_token"];
    }
}
if ($_SESSION["valorrevenda"] == 0) {
    echo "<script>alert(\"Seu revendedor não Esta cadrastado para Pagamento Automatico\")</script><script>window.location.href = \"../home.php\";</script>";
}
if (isset($_SESSION["valoradd"])) {
    unset($_SESSION["valoradd"]);
}
if (isset($_SESSION["valor"])) {
    unset($_SESSION["valor"]);
}
$renovacao = $_SESSION["valorrevenda"] * $_SESSION["limite"];
echo "<!-- END: Head-->\r\n\r\n<!-- BEGIN: Body-->\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n\r\n\r\n    <!-- END: Main Menu-->\r\n\r\n    <!-- BEGIN: Content-->\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <!-- app invoice View Page -->\r\n                <section class=\"invoice-view-wrapper\">\r\n                    <div class=\"row\">\r\n                        <!-- invoice view page -->\r\n                        <div class=\"col-xl-9 col-md-8 col-12\">\r\n                            <div class=\"card invoice-print-area\">\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body pb-0 mx-25\">\r\n                                        <!-- header section -->\r\n                                        <div class=\"row\">\r\n                                            <div class=\"col-xl-8 col-md-12\">\r\n                                            </div>\r\n                                        </div>\r\n                                        <!-- logo and title -->\r\n                                        <div class=\"row my-3\">\r\n                                            <div class=\"col-6\">\r\n                                                <h4 class=\"text-primary\">Pagamento</h4>\r\n                                                <span></span>\r\n                                            </div>\r\n                                            <div class=\"col-6 d-flex justify-content-end\">\r\n                                                <img src=\"";
echo $logo;
echo "\" alt=\"logo\" height=\"46\" width=\"134\">  \r\n                                            </div>\r\n                                        </div>\r\n                                            <div class=\"invoice-action-btn\">\r\n                                        <button class=\"btn btn-primary btn-print\" onclick=\"window.location.href = 'adicionar.php';\">\r\n                                            <i class='bx bx-dollar'></i>\r\n                                            <span>Adicionar Login</span>\r\n                                        </button>\r\n                                    </div>\r\n                                        </div>\r\n\r\n                                    <div class=\"card-body pt-0 mx-25\">\r\n                                        <hr>\r\n                                        <div class=\"row\">\r\n                                            <div class=\"col-4 col-sm-6 mt-75\">\r\n                                                <p>Seu Login: <code>";
echo $_SESSION["login"];
echo "</code></p>\r\n                                                \r\n                                            </div>\r\n                                            <!-- cupom -->\r\n                                            \r\n                                            \r\n                                            \r\n                                            <div class=\"col-8 col-sm-6 d-flex justify-content-end mt-75\">\r\n                                                <div class=\"invoice-subtotal\">\r\n                                                    <div class=\"invoice-calc d-flex justify-content-between\">\r\n                                                        <span class=\"invoice-title\">Seu Limite é ";
echo $_SESSION["limite"];
echo "</span>\r\n                                                    </div>\r\n                                                    <div class=\"invoice-calc d-flex justify-content-between\">\r\n                                                        <span class=\"invoice-title\">Sua Mensalidade é ";
echo $renovacao;
echo " Reais</span>\r\n                                                    </div>\r\n                                                    <hr>\r\n                                                    \r\n                                                    <form action=\"pagamento.php\" method=\"POST\">\r\n                                                    <div class=\"form-group\">\r\n                                                        <label for=\"cupom\">Cupom de desconto:</label>\r\n                                                        <input type=\"text\" class=\"form-control\" id=\"cupom\" name=\"cupom\">\r\n                                                    </div>\r\n                                                    <div class=\"invoice-action-btn\">\r\n                                                        <button class=\"btn btn-success btn-block\" name=\"renovar\">\r\n                                                            <i class='bx bx-dollar'></i>\r\n                                                            <span>Renovar</span>\r\n                                                        </button>\r\n                                                    </div>\r\n                                                </form>\r\n                                                </div>\r\n                                            </div>\r\n                                        </div>\r\n                                        ";
if (isset($_POST["renovar"])) {
    $_SESSION["valor"] = $renovacao;
    $_SESSION["cupom"] = $_POST["cupom"];
    if ($_SESSION["valor"] == 0) {
        echo "<script>alert('Você não tem limite para renovar')</script>";
    } else {
        echo "<script>location.href='processando.php'</script>";
    }
}
if (isset($_POST["adicionar"])) {
    echo "<script>location.href='adicionar.php'</script>";
    $_SESSION["email"] = $_POST["email"];
}
echo "                                    </div>\r\n                                    \r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n\r\n\r\n\r\n\r\n \r\n";

?>