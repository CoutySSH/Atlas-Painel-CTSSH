<?php


error_reporting(0);
session_start();
include "header2.php";
include "conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["iduser"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION["limite"] = $row["limite"];
        $_SESSION["validade"] = $row["expira"];
        $_SESSION["typecont"] = $row["tipo"];
    }
}
$sql2 = "SELECT * FROM accounts WHERE id = '" . $_SESSION["byid"] . "'";
$result2 = $conn->query($sql2);
if (0 < $result2->num_rows) {
    while ($row2 = $result2->fetch_assoc()) {
        $_SESSION["valorrevenda"] = $row2["valorrevenda"];
        $_SESSION["valorcredito"] = $row2["mainid"];
    }
}
$slq2 = "SELECT sum(limite) AS limiterevenda  FROM atribuidos where byid='" . $_SESSION["byid"] . "'";
$result = $conn->prepare($slq2);
$result->execute();
$result->bind_result($limiterevenda);
$result->fetch();
$result->close();
$sql4 = "SELECT * FROM ssh_accounts WHERE byid = '" . $_SESSION["byid"] . "'";
$sql4 = $conn->prepare($sql4);
$sql4->execute();
$sql4->store_result();
$num_rows = $sql4->num_rows;
$usadousuarios = $num_rows;
$sql55 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["byid"] . "'";
$result55 = $conn->query($sql55);
if (0 < $result55->num_rows) {
    while ($row55 = $result55->fetch_assoc()) {
        $limite = $row55["limite"];
    }
}
$soma = $usadousuarios + $limiterevenda;
if ($_SESSION["byid"] == "1") {
    $limitefinal = "Ilimitado";
} else {
    if ($_SESSION["typecont"] == "Credito") {
        $limitefinal = $limite;
    } else {
        $limitefinal = $limite - $soma;
    }
}
$sql = "SELECT * FROM accounts WHERE id = '" . $_SESSION["byid"] . "'";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION["valorrevenda"] = $row["valorrevenda"];
        $_SESSION["valorcredito"] = $row["mainid"];
        $_SESSION["accesstoken"] = $row["accesstoken"];
    }
}
if ($_SESSION["accesstoken"] == "") {
    echo "<script>sweetAlert(\"Oops...\", \"O Revendedor não possui uma conta cadastrada!\", \"error\");</script><script>setTimeout(function(){ window.location.href = \"../home.php\"; }, 3000);</script>";
}
$minimocompra = "1";
$sql_min = "SELECT * FROM configs WHERE id = '1'";
$result_min = $conn->query($sql_min);
if (0 < $result_min->num_rows) {
    while ($row_min = $result_min->fetch_assoc()) {
        $minimocompra = $row_min["minimocompra"];
    }
}
echo "<!-- END: Head-->\r\n\r\n<!-- BEGIN: Body-->\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n\r\n\r\n    <!-- END: Main Menu-->\r\n\r\n    <!-- BEGIN: Content-->\r\n    <div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\">\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\">\r\n                <!-- app invoice View Page -->\r\n                <section class=\"invoice-view-wrapper\">\r\n                    <form action=\"processandoadd.php\" method=\"POST\">\r\n                    <div class=\"row\">\r\n                        <!-- invoice view page -->\r\n                        <div class=\"col-xl-9 col-md-8 col-12\">\r\n                            <div class=\"card invoice-print-area\">\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body pb-0 mx-25\">\r\n                                    <h4 class=\"card-title\">Olá ";
echo $_SESSION["login"];
echo " </h4>\r\n                    <center>\r\n                        <div>\r\n                    <button type=\"button\" class=\"btn btn-outline-warning btn-fw\">Seu Limite é ";
echo $_SESSION["limite"];
echo "</button>\r\n                    </div>\r\n                    <br>\r\n                    <p class=\"card-description\" style=\"font-size: 25px\" > Quantos Deseja Adicionar</p>\r\n                     <center>\r\n                     <div class=\"form-group\">\r\n                        <label for=\"exampleInputUsername1\">Quantidade (DISPONIVEL: ";
echo $limitefinal;
echo ") Minimo de Compra: ";
echo $minimocompra;
echo "</label>\r\n                        <input type=\"number\" class=\"form-control\" name=\"addquantidade\" placeholder=\"Quantidade a Adicionar\" required min=\"";
echo $minimocompra;
echo "\" max=\"";
echo $limitefinal;
echo "\">\r\n                      </div>\r\n                      <div class=\"form-group\">\r\n                        <label for=\"exampleInputUsername1\">Cupom</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"cupom\" placeholder=\"Cupom\">\r\n                        </div>\r\n                    \r\n                    <button type=\"submit\" name=\"addlogin\" class=\"btn btn-primary btn-rounded btn-fw\">Adicionar</button>\r\n                     </center>\r\n                     <br>\r\n                  </div>\r\n</form>\r\n                </div>\r\n                  ";
if (isset($_POST["addlogin"])) {
    $_SESSION["cupom"] = $_POST["cupom"];
    $_SESSION["addquantidade"] = $_POST["addquantidade"];
}
echo "              </div>\r\n              </div>\r\n\r\n\r\n        </div>\r\n        <!-- main-panel ends -->\r\n      </div>\r\n      <!-- page-body-wrapper ends -->\r\n    </div>\r\n\r\n\r\n\r\n \r\n";

?>