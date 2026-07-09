<?php


session_start();
error_reporting(0);
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM accounts WHERE tokenvenda = '" . $_SESSION["tokenrevenda"] . "'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if (0 < mysqli_num_rows($result)) {
    $_SESSION["valorrevenda"] = $row["valorrevenda"];
    $_SESSION["byid"] = $row["id"];
    if ($_SESSION["byid"] != "1") {
        $sql2 = "SELECT * FROM atribuidos WHERE userid = '" . $_SESSION["byid"] . "'";
        $result2 = $conn->query($sql2);
        if (0 < $result2->num_rows) {
            while ($row2 = $result2->fetch_assoc()) {
                $_SESSION["tipo"] = $row2["tipo"];
            }
        }
    }
    if ($_SESSION["tipo"] == "Credito") {
        echo "<script>alert('Modo Credito não disponível para Compra!');</script>";
        exit;
    }
    echo "\r\n";
    $sql = "SELECT * FROM configs";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $nomepainel = $row["nomepainel"];
    $icon = $row["icon"];
    echo "\r\n<!DOCTYPE html>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">\r\n    <title>";
    echo $nomepainel;
    echo " - Compra</title>\r\n    <link rel=\"apple-touch-icon\" href=\"";
    echo $icon;
    echo "\">\r\n    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"";
    echo $icon;
    echo "\">\r\n    <link href=\"https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700\" rel=\"stylesheet\">\r\n\r\n    <!-- BEGIN: Vendor CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/vendors.min.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/charts/apexcharts.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/vendors/css/extensions/dragula.min.css\">\r\n    <!-- END: Vendor CSS-->\r\n\r\n    <!-- BEGIN: Theme CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/bootstrap-extended.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/colors.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/components.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/dark-layout.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/themes/semi-dark-layout.css\">\r\n    <!-- END: Theme CSS-->\r\n\r\n    <!-- BEGIN: Page CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/core/menu/menu-types/vertical-menu.css\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../app-assets/css/pages/dashboard-analytics.css\">\r\n    <!-- END: Page CSS-->\r\n\r\n    <!-- BEGIN: Custom CSS-->\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"../../../atlas-assets/css/style.css\">\r\n    <!-- END: Custom CSS-->\r\n\r\n</head>\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\" >\r\n   \r\n      <h2 class=\"text-center mb-3 mt-0 mt-md-4\">Preecha as Informações</h2>\r\n      <p class=\"text-center\">Preencha os campos abaixo para realizar a compra.</p>\r\n\r\n      <form action='formulariocompra.php' method='post'>\r\n      <div class=\"row mx-4 gy-3\">\r\n        <script>\r\n            //se for desktop definir width: 18rem; nessa classe card border shadow-none\r\n\r\n            if (window.innerWidth > 768) {\r\n                document.write(\"<style>.card {width: 30rem;}</style>\");\r\n            }\r\n        </script>\r\n        <!-- Starter -->\r\n        <div class=\"col-xl mb-lg-0 lg-4\">\r\n            <center>\r\n          <div class=\"card border shadow-none\">\r\n            <div class=\"card-body\">\r\n              <h5 class=\"text-start text-uppercase\">Plano de ";
    echo $_SESSION["plano"];
    echo " Usuarios</h5>\r\n             \r\n              <div class=\"col-md-4\">\r\n              <label>Email</label>\r\n              </div>\r\n               <div class=\"col-md-8 form-group\">\r\n                <input type=\"email\" class=\"form-control\" name=\"email\" placeholder=\"Email\" required>\r\n              </div>  \r\n              <div class=\"col-md-4\">\r\n              <label>Usuário</label>\r\n              </div>\r\n               <div class=\"col-md-8 form-group\">\r\n                <input type=\"text\" class=\"form-control\" name=\"usuario\" placeholder=\"Usuário\" required>\r\n              </div> \r\n              <div class=\"col-md-4\">\r\n              <label>Senha</label>\r\n              </div>\r\n               <div class=\"col-md-8 form-group\">\r\n                <input type=\"text\" class=\"form-control\" name=\"senha\" placeholder=\"Senha\" required>\r\n              </div> \r\n              <div class=\"col-md-4\">\r\n              <label>Possui um Cupom?</label>\r\n              </div>\r\n               <div class=\"col-md-8 form-group\">\r\n                <input type=\"text\" class=\"form-control\" name=\"cupom\" placeholder=\"Cupom\">\r\n              </div> \r\n              <p>Salve seu usuário e senha, pois você precisará dele para acessar o painel.</p>\r\n              \r\n                                                                                                                                \r\n\r\n              <button type=\"submit\" name=\"comprar\" class=\"btn btn-primary d-grid w-100\">Comprar</button>\r\n            </div>\r\n          </div>\r\n        </div>\r\n        </div>\r\n\r\n\r\n          \r\n        \r\n        \r\n    </div>\r\n</form>\r\n    ";
    if (isset($_POST["comprar"])) {
        $verifica = "SELECT * FROM accounts WHERE login = '" . $_POST["usuario"] . "'";
        $verifica = $conn->query($verifica);
        if (0 < $verifica->num_rows) {
            echo "<script>alert('Usuário já existe!');</script><script>location.href='formulariocompra.php'</script>";
            exit;
        }
        $verifica = "SELECT * FROM cupons WHERE cupom = '" . $_POST["cupom"] . "' AND byid = '" . $_SESSION["byid"] . "'";
        $verifica = $conn->query($verifica);
        if (0 < $verifica->num_rows) {
            echo "<script>alert('Cupom Aplicado!');</script>";
        }
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["usuario"] = $_POST["usuario"];
        $_SESSION["senha"] = $_POST["senha"];
        $_SESSION["cupom"] = $_POST["cupom"];
        echo "<script>location.href='processapag.php'</script>";
    }
    echo "  </div>\r\n  <!--/ Pricing Plans -->\r\n  </div>\r\n  </body>\r\n\r\n    <script src=\"../../../app-assets/vendors/js/vendors.min.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js\"></script>\r\n    <script src=\"../../../app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/configs/vertical-menu-dark.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app-menu.js\"></script>\r\n    <script src=\"../../../app-assets/js/core/app.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/components.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/footer.js\"></script>\r\n    <script src=\"../../../app-assets/js/scripts/forms/number-input.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/datatables.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js\"></script>\r\n    <script src=\"../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js\"></script>\r\n    <script src=\"../app-assets/sweetalert.min.js\"></script>\r\n \r\n \r\n</body>\r\n</html>";
} else {
    echo "<script>sweetAlert('Oops...', 'Link inválido!', 'error');</script>";
    exit;
}

?>