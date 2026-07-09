<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n";
error_reporting(0);
session_start();
include "conexao.php";
include "header2.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
$sql = "SELECT * FROM cupons WHERE byid = '" . $_SESSION["iduser"] . "'";
$result = $conn->query($sql);
$cupon = gerarcupom(8, true, true, false);
echo "<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                        <div class=\"card-header\">\r\n                        </div>\r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para ver mais detalhes! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n                        <div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                  \r\n\r\n                    <h4 class=\"card-title\">Cupom Desconto</h4><!-- botao adicionar servidor -->\r\n                    <form action=\"cupons.php\" method=\"post\">\r\n                    <input type=\"text\" name=\"nome\" placeholder=\"Nome Cupom\" class=\"form-control\" style=\"width: 200px; display: inline-block;\" required>\r\n                    <input type=\"text\" name=\"cupom\" placeholder=\"Cupom\" class=\"form-control\" style=\"width: 200px; display: inline-block;\" value=\"";
echo $cupon;
echo "\">\r\n                    <select name=\"desconto\" class=\"form-control\" style=\"width: 200px; display: inline-block; color: azure;\">\r\n                    <option value=\"1\">1%</option>\r\n                    <option value=\"2\">2%</option>\r\n                    <option value=\"3\">3%</option>\r\n                    <option value=\"4\">4%</option>\r\n                    <option value=\"5\">5%</option>\r\n                    <option value=\"6\">6%</option>\r\n                    <option value=\"7\">7%</option>\r\n                    <option value=\"8\">8%</option>\r\n                    <option value=\"9\">9%</option>\r\n                    <option value=\"10\">10%</option>\r\n                    <option value=\"11\">11%</option>\r\n                    <option value=\"12\">12%</option>\r\n                    <option value=\"13\">13%</option>\r\n                    <option value=\"14\">14%</option>\r\n                    <option value=\"15\">15%</option>\r\n                    <option value=\"16\">16%</option>\r\n                    <option value=\"17\">17%</option>\r\n                    <option value=\"18\">18%</option>\r\n                    <option value=\"19\">19%</option>\r\n                    <option value=\"20\">20%</option>\r\n                    <option value=\"21\">21%</option>\r\n                    <option value=\"22\">22%</option>\r\n                    <option value=\"23\">23%</option>\r\n                    <option value=\"24\">24%</option>\r\n                    <option value=\"25\">25%</option>\r\n                    <option value=\"26\">26%</option>\r\n                    <option value=\"27\">27%</option>\r\n                    <option value=\"28\">28%</option>\r\n                    <option value=\"29\">29%</option>\r\n                    <option value=\"30\">30%</option>\r\n                    <option value=\"31\">31%</option>\r\n                    <option value=\"32\">32%</option>\r\n                    <option value=\"33\">33%</option>\r\n                    <option value=\"34\">34%</option>\r\n                    <option value=\"35\">35%</option>\r\n                    <option value=\"36\">36%</option>\r\n                    <option value=\"37\">37%</option>\r\n                    <option value=\"38\">38%</option>\r\n                    <option value=\"39\">39%</option>\r\n                    <option value=\"40\">40%</option>\r\n                    <option value=\"41\">41%</option>\r\n                    <option value=\"42\">42%</option>\r\n                    <option value=\"43\">43%</option>\r\n                    <option value=\"44\">44%</option>\r\n                    <option value=\"45\">45%</option>\r\n                    <option value=\"46\">46%</option>\r\n                    <option value=\"47\">47%</option>\r\n                    <option value=\"48\">48%</option>\r\n                    <option value=\"49\">49%</option>\r\n                    <option value=\"50\">50%</option>\r\n                    <option value=\"51\">51%</option>\r\n                    <option value=\"52\">52%</option>\r\n                    <option value=\"53\">53%</option>\r\n                    <option value=\"54\">54%</option>\r\n                    <option value=\"55\">55%</option>\r\n                    <option value=\"56\">56%</option>\r\n                    <option value=\"57\">57%</option>\r\n                    <option value=\"58\">58%</option>\r\n                    <option value=\"59\">59%</option>\r\n                    <option value=\"60\">60%</option>\r\n                    <option value=\"61\">61%</option>\r\n                    <option value=\"62\">62%</option>\r\n                    <option value=\"63\">63%</option>\r\n                    <option value=\"64\">64%</option>\r\n                    <option value=\"65\">65%</option>\r\n                    <option value=\"66\">66%</option>\r\n                    <option value=\"67\">67%</option>\r\n                    <option value=\"68\">68%</option>\r\n                    <option value=\"69\">69%</option>\r\n                    <option value=\"70\">70%</option>\r\n                    <option value=\"71\">71%</option>\r\n                    <option value=\"72\">72%</option>\r\n                    <option value=\"73\">73%</option>\r\n                    <option value=\"74\">74%</option>\r\n                    <option value=\"75\">75%</option>\r\n                    <option value=\"76\">76%</option>\r\n                    <option value=\"77\">77%</option>\r\n                    <option value=\"78\">78%</option>\r\n                    <option value=\"79\">79%</option>\r\n                    <option value=\"80\">80%</option>\r\n                    <option value=\"81\">81%</option>\r\n                    <option value=\"82\">82%</option>\r\n                    <option value=\"83\">83%</option>\r\n                    <option value=\"84\">84%</option>\r\n                    <option value=\"85\">85%</option>\r\n                    <option value=\"86\">86%</option>\r\n                    <option value=\"87\">87%</option>\r\n                    <option value=\"88\">88%</option>\r\n                    <option value=\"89\">89%</option>\r\n                    <option value=\"90\">90%</option>\r\n                    <option value=\"91\">91%</option>\r\n                    <option value=\"92\">92%</option>\r\n                    <option value=\"93\">93%</option>\r\n                    <option value=\"94\">94%</option>\r\n                    <option value=\"95\">95%</option>\r\n                    <option value=\"96\">96%</option>\r\n                    <option value=\"97\">97%</option>\r\n                    <option value=\"98\">98%</option>\r\n                    <option value=\"99\">99%</option>\r\n                    <option value=\"100\">100%</option>\r\n                </select>\r\n                    <input type=\"submit\" name=\"adicionarcupom\" value=\"Adicionar\" class=\"btn btn-primary btn-md\" style=\"display: inline-block;\">\r\n                    </form>\r\n                    ";
if (isset($_POST["adicionarcupom"])) {
    $nome = $_POST["nome"];
    $cupom = $_POST["cupom"];
    $desconto = $_POST["desconto"];
    $sql = "INSERT INTO cupons (nome, cupom, desconto, byid, usado) VALUES ('" . $nome . "', '" . $cupom . "', '" . $desconto . "', '" . $_SESSION["iduser"] . "', '0')";
    if ($conn->query($sql) === true) {
        echo "<script>swal('Sucesso!', 'Cupom Adicionado!', 'success').then((value) => {\r\n                window.location.href = 'cupons.php';\r\n              });</script>";
    } else {
        echo "<script>swal('Erro!', 'Cupom Não Adicionado!', 'error').then((value) => {\r\n                window.location.href = 'cupons.php';\r\n              });</script>";
    }
}
echo "                    <!-- <p class=\"card-description\"> Add class <code>Usuarios</code>\r\n                    </p> -->\r\n                    <div class=\"card-content\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                    <th> Nome </th>\r\n                                                    <th> Codigo Cupom </th>\r\n                                                    <th> Desconto </th>\r\n                                                    <th> Usado </th>\r\n                                                    <th> Ações </th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
while ($user_data = mysqli_fetch_assoc($result)) {
    if ($user_data["usado"] == "") {
        $user_data["usado"] = "0 ";
    }
    echo "<td>" . $user_data["nome"] . "</td>";
    echo "<td>" . $user_data["cupom"] . "</td>";
    echo "<td>" . $user_data["desconto"] . "%</td>";
    echo "<td>" . $user_data["usado"] . " Vezes</td>";
    echo "<td><form action='cupons.php' method='post'><input type='hidden' name='id' value='" . $user_data["id"] . "'><input type='submit' name='deletar' value='Deletar' class='btn btn-outline-danger btn-fw'></form></td>";
    echo "</tr>";
}
if (isset($_POST["deletar"])) {
    $id = $_POST["id"];
    $sql = "DELETE FROM cupons WHERE id='" . $id . "'";
    if ($conn->query($sql) === true) {
        echo "<script>swal('Sucesso!', 'Cupom Deletado!', 'success').then((value) => {\r\n                                            window.location.href = 'cupons.php';\r\n                                          });</script>";
    } else {
        echo "<script>swal('Erro!', 'Cupom Não Deletado!', 'error').then((value) => {\r\n                                            window.location.href = 'cupons.php';\r\n                                          });</script>";
    }
}
echo "  </tbody>\r\n    </table>\r\n    </div>\r\n    </div>\r\n    </div>\r\n    \r\n\r\n                                        \r\n                    \r\n                          \r\n                                        \r\n                        \r\n    <!-- END: Content-->\r\n    <script src=\"cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"../app-assets/js/scripts/datatables/datatable.js\"></script>\r\n    <script>\r\n    \$('#myTable').DataTable({\r\n\r\n        /* traduzir somente */\r\n        \"language\": {\r\n            \"lengthMenu\": \"Mostrar _MENU_ registros por página\",\r\n            \"zeroRecords\": \"Nenhum registro encontrado\",\r\n            \"info\": \"Mostrando página _PAGE_ de _PAGES_\",\r\n            \"infoEmpty\": \"Nenhum registro disponível\",\r\n            \"infoFiltered\": \"(filtrado de _MAX_ registros no total)\",\r\n            \"search\": \"Pesquisar:\",\r\n            \"paginate\": {\r\n                \"first\": \"\",\r\n                \"last\": \"\",\r\n                \"next\": \"\",\r\n                \"previous\": \"\"\r\n            }\r\n        }\r\n    \r\n    });\r\n\r\n</script>\r\n<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!-- ajax -->\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\" integrity=\"sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>\r\n";
function gerarCupom($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    $lmin = "abcdefghijklmnopqrstuvwxyz";
    $lmai = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $num = "1234567890";
    $simb = "!@#\$%*-";
    $retorno = "";
    $caracteres = "";
    $caracteres .= $lmin;
    if ($maiusculas) {
        $caracteres .= $lmai;
    }
    if ($numeros) {
        $caracteres .= $num;
    }
    if ($simbolos) {
        $caracteres .= $simb;
    }
    $len = strlen($caracteres);
    for ($n = 1; $n < $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}

?>