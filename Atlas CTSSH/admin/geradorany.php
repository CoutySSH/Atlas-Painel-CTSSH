<?php


echo "<script src=\"../app-assets/sweetalert.min.js\"></script>\r\n<!DOCTYPE html>\r\n<html lang=\"pt-br\">\r\n  <head>\r\n    <!-- Required meta tags -->\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">\r\n    ";
session_start();
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
echo "    <!-- plugins:css -->\r\n    <link rel=\"stylesheet\" href=\"../atlas-assets/vendors/mdi/css/materialdesignicons.min.css\">\r\n    <link rel=\"stylesheet\" href=\"../atlas-assets/vendors/css/vendor.bundle.base.css\">\r\n    <!-- endinject -->\r\n    <link rel=\"stylesheet\" href=\"../atlas-assets/css/style.css\">\r\n    <!-- End layout styles -->\r\n    <link rel=\"shortcut icon\" href=\"";
echo $icon;
echo "\" />\r\n  </head>\r\n  <body>\r\n  ";
$pasta = "SELECT * FROM configs WHERE id = 1";
$result = $conn->query($pasta);
$row = $result->fetch_assoc();
$pasta = $row["cornavsuperior"];
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["jsonFile"])) {
    if ($_FILES["jsonFile"]["error"] === UPLOAD_ERR_OK) {
        $tempFilePath = $_FILES["jsonFile"]["tmp_name"];
        $originalFileName = $_FILES["jsonFile"]["name"];
        $destinationPath = $pasta . "/configs.json";
        if (move_uploaded_file($tempFilePath, $destinationPath)) {
            http_response_code(200);
            echo "<script>sweetAlert(\"Sucesso!\", \"Configuração Salva!\", \"success\").then(function () {\r\n                window.location.href = \"geradorany.php\";\r\n            });</script>";
            $jsonFile = "" . $pasta . "/configs.json";
            $data = json_decode(file_get_contents($jsonFile), true);
            $data["ConfigVersion"] = $_SESSION["ConfigVersion"];
            file_put_contents($jsonFile, json_encode($data));
        } else {
            http_response_code(500);
        }
    } else {
        http_response_code(400);
    }
}
include "headeradmin2.php";
date_default_timezone_set("America/Sao_Paulo");
if ($_POST["addPayload"]) {
    echo "<script>sweetAlert(\"Sucesso!\", \"Payload Adicionado!\", \"success\").then(function () {\r\n        window.location.href = \"geradorany.php\";\r\n    });</script>";
} else {
    if ($_POST["add_network"]) {
        echo "<script>sweetAlert(\"Sucesso!\", \"Configuração Adicionada!\", \"success\").then(function () {\r\n        window.location.href = \"geradorany.php\";\r\n    });</script>";
    } else {
        if ($_POST["edit_network"]) {
            echo "<script>sweetAlert(\"Sucesso!\", \"Configuração Editada!\", \"success\").then(function () {\r\n        window.location.href = \"geradorany.php\";\r\n    });</script>";
        } else {
            if ($_POST["delete_network"]) {
                echo "<script>sweetAlert(\"Sucesso!\", \"Configuração Deletada!\", \"success\").then(function () {\r\n        window.location.href = \"geradorany.php\";\r\n    });</script>";
            } else {
                if ($_POST) {
                    echo "<script>sweetAlert(\"Sucesso!\", \"Configuração Salva!\", \"success\").then(function () {\r\n        window.location.href = \"geradorany.php\";\r\n    });</script>";
                }
            }
        }
    }
}
$pasta = "SELECT * FROM configs WHERE id = 1";
$result = $conn->query($pasta);
$row = $result->fetch_assoc();
$pasta = $row["cornavsuperior"];
if ($pasta == NULL || $pasta == "") {
    $pasta = rand(0, 0);
    mkdir((int) $pasta);
    $sql = "UPDATE configs SET cornavsuperior = '" . $pasta . "' WHERE id = 1";
    if ($conn->query($sql) === true) {
        echo "<script>window.location.reload();</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
if (!file_exists("" . $pasta . "")) {
    mkdir((int) $pasta);
}
if (!file_exists("" . $pasta . "/configs.json")) {
    $url = "https://cdn.discordapp.com/attachments/942800753309921290/1114098973972639804/configs.json";
    $content = file_get_contents($url);
    file_put_contents("" . $pasta . "/configs.json", $content);
}
$dominio = $_SERVER["HTTP_HOST"];
$jsonFile = "" . $pasta . "/configs.json";
$data = json_decode(file_get_contents($jsonFile), true);
$_SESSION["ConfigVersion"] = $data["ConfigVersion"];
echo "<script src=\"https://code.jquery.com/jquery-3.5.1.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js\"></script>\r\n\r\n<body class=\"vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  \" data-open=\"click\" data-menu=\"vertical-menu-modern\" data-col=\"2-columns\" data-layout=\"dark-layout\">\r\n<div class=\"app-content content\">\r\n    <div class=\"content-overlay\"></div>\r\n    <div class=\"content-wrapper\">\r\n        <section id=\"basic-datatable\">\r\n            <div class=\"row\">\r\n             \r\n                <div class=\"col-12\">\r\n                    <div class=\"card\">\r\n                    \r\n                        <div class=\"card-header\">\r\n                            <h4 class=\"card-title\">Gerador AnyVpn</h4>\r\n                            <!-- description -->\r\n                            <div class=\"card-content\">\r\n                                <div class=\"card-body card-dashboard\">\r\n                                    <p class=\"card-text\">Gerador Para AnyVpn Mod. Version Update ";
echo $data["ConfigVersion"];
echo "<a href=\"https://coutyssh.com\" target=\"_blank\"> Para Comprar Original Clique Aqui</a></p>\r\n                                </div>\r\n                 \r\n                        </div>\r\n                        \r\n                        <script>\r\n\r\n\r\nif (window.innerWidth < 678) {\r\n\r\n    document.write('<div class=\"alert alert-warning\" role=\"alert\"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');\r\n    window.setTimeout(function() {\r\n        \$(\".alert\").fadeTo(500, 0).slideUp(500, function(){\r\n            \$(this).remove(); \r\n        });\r\n    }, 3000);\r\n}\r\n\r\n</script>\r\n\r\n<div class=\"card-content\">\r\n    \r\n<form method=\"POST\" action=\"";
echo $_SERVER["PHP_SELF"];
echo "\">\r\n                            <div class=\"card-body card-dashboard\">\r\n                            <button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#addNetworkModal\">\r\n            Adicionar Payload\r\n        </button>\r\n        <button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#Configurarapp\">\r\n            Configurar App\r\n        </button>\r\n        <input type=\"text\" id=\"copyText\" style=\"display: none;\" value=\"";
echo "https://" . $dominio . "/admin/" . $pasta . "/configs.json";
echo "\">\r\n        <button type=\"button\" class=\"btn btn-primary\" onclick=\"copyToClipboard()\">\r\n            Copiar Link Update\r\n        </button>\r\n        <button type=\"button\" class=\"btn btn-primary\" onclick=\"baixar()\">\r\n            Baixar Json\r\n        </button>\r\n        <button type=\"button\" class=\"btn btn-primary\" onclick=\"importar()\">\r\n            Importar Json\r\n        </button>\r\n        <script>\r\n            function copyToClipboard() {\r\n    var copyText = document.getElementById(\"copyText\");\r\n\r\n    // Exibe o elemento de input de texto\r\n    copyText.style.display = \"block\";\r\n    copyText.select();\r\n\r\n    // Tenta copiar o texto\r\n    var successful = document.execCommand(\"copy\");\r\n\r\n    // Oculta o elemento de input de texto novamente\r\n    copyText.style.display = \"none\";\r\n\r\n    // Exibe uma mensagem de sucesso ou erro\r\n    if (successful) {\r\n        alert(\"URL copiada com sucesso!\");\r\n    } else {\r\n        alert(\"Falha ao copiar a URL. Por favor, selecione e copie manualmente.\");\r\n    }\r\n}\r\n\r\n        </script>\r\n        <script>\r\n            function baixar() {\r\n        // Perform the necessary actions to download the JSON file\r\n        // You can use the 'fetch' API or create a hidden link element with a download attribute\r\n        // Example using the fetch API:\r\n        fetch('https://";
echo $dominio;
echo "/admin/";
echo $pasta;
echo "/configs.json')\r\n            .then(function(response) {\r\n                return response.blob();\r\n            })\r\n            .then(function(blob) {\r\n                // Create a temporary link element\r\n                var link = document.createElement('a');\r\n                link.href = URL.createObjectURL(blob);\r\n                link.download = 'configs.json';\r\n\r\n                // Programmatically click the link to trigger the download\r\n                link.click();\r\n\r\n                // Cleanup\r\n                URL.revokeObjectURL(link.href);\r\n            })\r\n            .catch(function(error) {\r\n                console.log('Error downloading JSON:', error);\r\n            });\r\n    }\r\n    function importar() {\r\n        var input = document.createElement('input');\r\n        input.type = 'file';\r\n\r\n        input.addEventListener('change', function(event) {\r\n            var file = event.target.files[0];\r\n            var formData = new FormData();\r\n            formData.append('jsonFile', file);\r\n\r\n            // Envie o formulário para a mesma página PHP para processar o arquivo JSON\r\n            var xhr = new XMLHttpRequest();\r\n            xhr.open('POST', '";
echo $_SERVER["PHP_SELF"];
echo "', true); // Envie para a mesma página PHP\r\n            xhr.onload = function() {\r\n                if (xhr.status === 200) {\r\n                    console.log('Arquivo JSON importado com sucesso');\r\n                    sweetAlert(\"Sucesso!\", \"Arquivo JSON importado com sucesso!\", \"success\").then(function() {\r\n                        window.location.reload();\r\n                    });\r\n                    \r\n                } else {\r\n                    console.log('Erro ao importar o arquivo JSON');\r\n                }\r\n            };\r\n            xhr.send(formData);\r\n        });\r\n\r\n        input.click();\r\n    }\r\n</script>\r\n\r\n\r\n        \r\n\r\n\r\n\r\n    \r\n        \r\n    \r\n        <a class=\"btn btn-primary\" href=\"https://cdn.discordapp.com/attachments/942800753309921290/1114039536117362718/ANYVPN_Mod.apk\" target=\"_blank\">Baixar Apk</a>\r\n                                <!-- nao mostar o sroll -->\r\n                                <div class=\"table-responsive\" style=\" overflow: auto; overflow-y: hidden;\">\r\n                                    <table class=\"table zero-configuration\" id=\"myTable\">\r\n                                                <thead>\r\n                                                    <tr>\r\n                                                    <th>Nome</th>\r\n                                                    <th>Ação</th>\r\n                                                    </tr>\r\n                                                </thead>\r\n                                                <tbody>\r\n                                                ";
foreach ($data["Networks"] as $index => $network) {
    echo "                        <tr>\r\n                            <td>";
    echo $network["Name"];
    echo "</td>\r\n                            <td>\r\n                                <!-- Botão para editar uma rede -->\r\n                                <button type=\"button\" class=\"btn btn-primary\" onclick=\"editNetwork(";
    echo $index;
    echo ")\">Editar</button>\r\n\r\n                                <!-- Botão para excluir uma rede -->\r\n                                <button type=\"submit\" class=\"btn btn-danger\" name=\"delete_network\" value=\"";
    echo $index;
    echo "\">Excluir</button>\r\n                            </td>\r\n                        </tr>\r\n                    ";
}
echo "\r\n  </tbody>\r\n    </table>\r\n    </form>\r\n\r\n<hr>\r\n<div class=\"modal fade\" id=\"Configurarapp\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"ConfigurarappLabel\" aria-hidden=\"true\">\r\n    <div class=\"modal-dialog\" role=\"document\">\r\n        <div class=\"modal-content\">\r\n            <div class=\"modal-header\">\r\n                <h5 class=\"modal-title\" id=\"ConfigurarappLabel\">Configurar App</h5>\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                    <span aria-hidden=\"true\">&times;</span>\r\n                </button>\r\n            </div>\r\n            <div class=\"modal-body\">\r\n                <form id=\"configForm\" action=\"";
echo $_SERVER["PHP_SELF"];
echo "\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label for=\"Saudacao\">Saudação:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"Saudacao\" value=\"";
echo $data["Saudacao"];
echo "\">\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"ReleaseNotes\">Release Notes:</label>\r\n                        ";
foreach ($data["ReleaseNotes"] as $index => $note) {
    echo "                            <input type=\"text\" class=\"form-control\" name=\"ReleaseNotes[]\" value=\"";
    echo $note;
    echo "\"><br>\r\n                        ";
}
echo "                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"LinkPainel\">Link Painel Checkuser:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"LinkPainel\" value=\"";
echo $data["LinkPainel"];
echo "\">\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"LinkIcone\">Link Ícone: (192x192)</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"LinkIcone\" value=\"";
echo $data["LinkIcone"];
echo "\">\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"LinkBanner\">Link Logo: (900x900)</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"LinkBanner\" value=\"";
echo $data["LinkBanner"];
echo "\">\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"LinkBackground\">Link Background: (800x1200)</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"LinkBackground\" value=\"";
echo $data["LinkBackground"];
echo "\">\r\n                    </div>\r\n                    <!-- Adicione aqui os outros campos -->\r\n\r\n                    <div class=\"modal-footer\">\r\n                        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>\r\n                        <button type=\"submit\" class=\"btn btn-primary\" name=\"save_config\">Salvar</button>\r\n                    </div>\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n\r\n<!-- Campos para editar uma rede -->\r\n<!-- Modal para editar uma rede -->\r\n<div class=\"modal fade\" id=\"editNetworkModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"editNetworkModalLabel\" aria-hidden=\"true\">\r\n<div class=\"modal-dialog\" role=\"document\">\r\n<div class=\"modal-content\">\r\n    <div class=\"modal-header\">\r\n        <h5 class=\"modal-title\" id=\"editNetworkModalLabel\">Editar Payload</h5>\r\n        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\">\r\n            <span aria-hidden=\"true\">&times;</span>\r\n        </button>\r\n    </div>\r\n    <div class=\"modal-body\">\r\n        <!-- Campos para editar uma rede -->\r\n        <form id=\"editNetworkForm\">\r\n            <!-- ...campos de edição... -->\r\n        </form>\r\n    </div>\r\n    <div class=\"modal-footer\">\r\n        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>\r\n        <button type=\"button\" class=\"btn btn-primary\" onclick=\"saveChanges()\">Salvar Alterações</button>\r\n    </div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"modal fade\" id=\"addNetworkModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"addNetworkModalLabel\" aria-hidden=\"true\">\r\n    <div class=\"modal-dialog\" role=\"document\">\r\n        <div class=\"modal-content\">\r\n            <div class=\"modal-header\">\r\n                <h5 class=\"modal-title\" id=\"addNetworkModalLabel\">Adicionar Payload</h5>\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\">\r\n                    <span aria-hidden=\"true\">&times;</span>\r\n                </button>\r\n            </div>\r\n            <div class=\"modal-body\">\r\n                <!-- Campos para adicionar uma nova rede -->\r\n                <form method=\"POST\" action=\"";
echo $_SERVER["PHP_SELF"];
echo "\">\r\n                <div class=\"form-group\">\r\n                        <label for=\"new_network_vpn_mod\">Modo de Conexão:</label>\r\n                        <select class=\"form-control\" name=\"new_network[vpnmod]\">\r\n                            <option value=\"0\">Proxy</option>\r\n                            <option value=\"1\">Ssl</option>\r\n                            <option value=\"2\">Direct</option>\r\n                            <option value=\"3\">Sslpay</option>\r\n                            <option value=\"3\">V2ray</option>\r\n                        </select>\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_vpn_tun_mod\">Modo:</label>\r\n                        <select class=\"form-control\" name=\"new_network[vpntunmod]\">\r\n                            <option value=\"1\">SSH</option>\r\n                            <option value=\"2\">OVPN</option>\r\n                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_name\">Nome da Configuração:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[name]\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_servidor\">Portas Udp Ex: :7300;7400;7500</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[servidor]\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_sport\">Porta SSL:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[sport]\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_proxy\">Proxy:Port</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[prox]\" placeholder=\"144.22.158.190:80\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_payload\">Payload Websocket:</label>\r\n                        <textarea type=\"text\" class=\"form-control\" name=\"new_network[payload]\" rows=\"3\"></textarea>\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_direct\">Payload Direct:</label>\r\n                        <textarea type=\"text\" class=\"form-control\" name=\"new_network[direct]\" rows=\"3\"></textarea>\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_sni\">Sni:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[sni]\" placeholder=\"www.google.com\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_payssl\">Payload SSL:</label>\r\n                        <textarea class=\"form-control\" name=\"new_network[payssl]\" rows=\"3\"></textarea>\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_certopen\">Certificado OpenVpn:</label>\r\n                        <textarea class=\"form-control\" name=\"new_network[certopen]\" rows=\"3\"></textarea>\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_dns1\">Dns 1:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[dns1]\" value=\"8.8.8.8\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_dns2\">Dns 2:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[dns2]\" value=\"8.8.4.4\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_url_painel\">Url CheckUser:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[urlpainel]\" value=\"https://";
echo $_SERVER["HTTP_HOST"];
echo "/checkuser\">\r\n                    </div>\r\n\r\n                    <div class=\"form-group\">\r\n                        <label for=\"new_network_info\">Operadora:</label>\r\n                        <input type=\"text\" class=\"form-control\" name=\"new_network[info]\" value=\"Sua descrição aqui:operadora\">\r\n                    </div>\r\n\r\n                    <input type=\"submit\" class=\"btn btn-primary\" name=\"add_network\" value=\"Adicionar Payload\">\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n<!-- ...código anterior... -->\r\n\r\n<script>\r\nvar networkToEditIndex;\r\n\r\nfunction editNetwork(index) {\r\nnetworkToEditIndex = index;\r\nvar network = ";
echo json_encode($data["Networks"]);
echo "[index];\r\nvar editNetworkForm = document.getElementById('editNetworkForm');\r\n\r\n// Preencha os campos do formulário com os valores da rede selecionada\r\neditNetworkForm.innerHTML = `\r\n<div class=\"form-group\">\r\n    <label for=\"edit_network_name\">Nome da Configuração:</label>\r\n    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"\${network['Name']}\">\r\n</div>\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"edit_network_servidor\">Portas Udp:</label>\r\n    <input type=\"text\" class=\"form-control\" name=\"servidor\" value=\"\${network['Servidor']}\" placeholder=\"Ex: :7200;7300;7100;7400\">\r\n</div>\r\n<div class=\"form-group\">\r\n    <label for=\"edit_network_sport\">Porta SSL:</label>\r\n    <input type=\"text\" class=\"form-control\" name=\"sport\" value=\"\${network['SPort']}\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_sport\">Proxy:Port:</label>\r\n<input type=\"text\" class=\"form-control\" name=\"prox\" value=\"\${network['Prox']}\">\r\n</div>\r\n\r\n\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_payload\">Payload WebSocket:</label>\r\n<textarea class=\"form-control\" name=\"payload\" rows=\"5\">\${network['Payload']}</textarea>\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_direct\">Payload Direct:</label>\r\n<textarea class=\"form-control\" name=\"direct\" rows=\"5\">\${network['Direct']}</textarea>\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_sni\">Sni:</label>\r\n<input type=\"text\" class=\"form-control\" name=\"sni\" value=\"\${network['Sni']}\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_payssl\">Payload SSL:</label>\r\n<textarea class=\"form-control\" name=\"payssl\" rows=\"5\">\${network['Payssl']}</textarea>\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_certopen\">Certificado OpenVpn:</label>\r\n<textarea class=\"form-control\" name=\"certopen\" rows=\"5\">\${network['Certopen']}</textarea>\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_dns1\">Dns 1:</label>\r\n<input type=\"text\" class=\"form-control\" name=\"dns1\" value=\"\${network['Dns1']}\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_dns2\">Dns 2:</label>\r\n<input type=\"text\" class=\"form-control\" name=\"dns2\" value=\"\${network['Dns2']}\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_url_painel\">Url CheckUser:</label>\r\n<input type=\"text\" class=\"form-control\" name=\"urlpainel\" value=\"\${network['UrlPainel']}\">\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_vpnmod\">Modo:</label>\r\n<select class=\"form-control\" name = \"vpnmod\">\r\n<option value=\"0\" \${network['VPNMod'] == 0 ? 'selected' : ''}>Proxy</option>\r\n<option value=\"1\" \${network['VPNMod'] == 1 ? 'selected' : ''}>Ssl</option>\r\n<option value=\"2\" \${network['VPNMod'] == 2 ? 'selected' : ''}>Direct</option>\r\n<option value=\"3\" \${network['VPNMod'] == 3 ? 'selected' : ''}>SslPay</option>\r\n<option value=\"3\" \${network['VPNMod'] == 3 ? 'selected' : ''}>V2ray</option>\r\n</select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_vpntunmod\">SSH ou Direct:</label>\r\n<select class=\"form-control\" name = \"vpntunmod\">\r\n<option value=\"1\" \${network['VPNTunMod'] == 1 ? 'selected' : ''}>SSH</option>\r\n<option value=\"2\" \${network['VPNTunMod'] == 2 ? 'selected' : ''}>OVPN</option>\r\n</select>\r\n</div>\r\n<div class=\"form-group\">\r\n<label for=\"edit_network_info\">Info:</label>\r\n<input type=\"text\" class=\"form-control\" name=\"info\" value=\"\${network['Info']}\">\r\n</div>\r\n\r\n\r\n<!-- Adicione outros campos conforme necessário -->\r\n`;\r\n\r\n\r\n// Abra o modal\r\n\$('#editNetworkModal').modal('show');\r\n}\r\n\r\nfunction saveChanges() {\r\nvar editNetworkForm = document.getElementById('editNetworkForm');\r\nvar formData = new FormData(editNetworkForm);\r\nformData.append('edit_network', networkToEditIndex);\r\n\r\n// Faça uma requisição AJAX para o script PHP que irá lidar com a atualização do arquivo JSON\r\nvar xhr = new XMLHttpRequest();\r\nxhr.open('POST', 'geradorany.php');\r\nxhr.onload = function() {\r\n    if (xhr.status === 200) {\r\n        swal('Sucesso!', 'As alterações foram salvas com sucesso.', 'success').then(function() {\r\n            // Feche o modal\r\n            \$('#editNetworkModal').modal('hide');\r\n            // Atualize a tabela ou recarregue a página conforme necessário\r\n            window.location.reload();\r\n        });\r\n        \$('#editNetworkModal').modal('hide');\r\n        // Atualize a tabela ou recarregue a página conforme necessário\r\n        //window.location.reload();\r\n    } else {\r\n        alert('Ocorreu um erro ao salvar as alterações. Código do status: ' + xhr.status);\r\n    }\r\n};\r\nxhr.send(formData);\r\n}\r\n";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["edit_network"])) {
        $networkIndex = $_POST["edit_network"];
        $data["Networks"][$networkIndex]["Name"] = $_POST["name"];
        $data["Networks"][$networkIndex]["Servidor"] = $_POST["servidor"];
        $data["Networks"][$networkIndex]["SPort"] = $_POST["sport"];
        $data["Networks"][$networkIndex]["Prox"] = $_POST["prox"];
        $data["Networks"][$networkIndex]["Payload"] = $_POST["payload"];
        $data["Networks"][$networkIndex]["Direct"] = $_POST["direct"];
        $data["Networks"][$networkIndex]["Sni"] = $_POST["sni"];
        $data["Networks"][$networkIndex]["Payssl"] = $_POST["payssl"];
        $data["Networks"][$networkIndex]["Certopen"] = $_POST["certopen"];
        $data["Networks"][$networkIndex]["Dns1"] = $_POST["dns1"];
        $data["Networks"][$networkIndex]["Dns2"] = $_POST["dns2"];
        $data["Networks"][$networkIndex]["UrlPainel"] = $_POST["urlpainel"];
        $data["Networks"][$networkIndex]["VPNMod"] = $_POST["vpnmod"];
        $data["Networks"][$networkIndex]["VPNTunMod"] = $_POST["vpntunmod"];
        $data["Networks"][$networkIndex]["Info"] = $_POST["info"];
    }
    if ($_POST["new_network"]["sport"] == NULL) {
        $_POST["new_network"]["sport"] = "";
    }
    if (isset($_POST["add_network"])) {
        $newNetwork = ["Name" => isset($_POST["new_network"]["name"]) ? $_POST["new_network"]["name"] : "", "Servidor" => isset($_POST["new_network"]["servidor"]) ? $_POST["new_network"]["servidor"] : "", "SPort" => isset($_POST["new_network"]["sport"]) ? $_POST["new_network"]["sport"] : "", "Prox" => isset($_POST["new_network"]["prox"]) ? $_POST["new_network"]["prox"] : "", "Payload" => isset($_POST["new_network"]["payload"]) ? $_POST["new_network"]["payload"] : "", "Direct" => isset($_POST["new_network"]["direct"]) ? $_POST["new_network"]["direct"] : "", "Sni" => isset($_POST["new_network"]["sni"]) ? $_POST["new_network"]["sni"] : "", "Payssl" => isset($_POST["new_network"]["payssl"]) ? $_POST["new_network"]["payssl"] : "", "Certopen" => isset($_POST["new_network"]["certopen"]) ? $_POST["new_network"]["certopen"] : "", "Dns1" => isset($_POST["new_network"]["dns1"]) ? $_POST["new_network"]["dns1"] : "", "Dns2" => isset($_POST["new_network"]["dns2"]) ? $_POST["new_network"]["dns2"] : "", "UrlPainel" => isset($_POST["new_network"]["urlpainel"]) ? $_POST["new_network"]["urlpainel"] : "", "VPNMod" => isset($_POST["new_network"]["vpnmod"]) ? intval($_POST["new_network"]["vpnmod"]) : 0, "VPNTunMod" => isset($_POST["new_network"]["vpntunmod"]) ? intval($_POST["new_network"]["vpntunmod"]) : 1, "Info" => isset($_POST["new_network"]["info"]) ? $_POST["new_network"]["info"] : "", "DefaultProxy" => true, "CustomSquid" => isset($_POST["new_network"]["customsquid"]) ? $_POST["new_network"]["customsquid"] : ""];
        $data["Networks"][] = $newNetwork;
    }
    if (isset($_POST["save_config"])) {
        $data["Saudacao"] = $_POST["Saudacao"];
        $data["ReleaseNotes"] = $_POST["ReleaseNotes"];
        $data["LinkPainel"] = $_POST["LinkPainel"];
        $data["LinkIcone"] = $_POST["LinkIcone"];
        $data["LinkBanner"] = $_POST["LinkBanner"];
        $data["LinkBackground"] = $_POST["LinkBackground"];
    }
    if (isset($_POST["delete_network"])) {
        $networkIndex = $_POST["delete_network"];
        if (isset($data["Networks"][$networkIndex])) {
            unset($data["Networks"][$networkIndex]);
            $data["Networks"] = array_values($data["Networks"]);
        }
    }
    $data["ConfigVersion"] = isset($data["ConfigVersion"]) ? $data["ConfigVersion"] + 1 : 1;
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}
echo "</script>\r\n\r\n<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js\"></script>\r\n<script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js\"></script>\r\n</body>";

?>