<?php


echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>\r\n\r\n";
error_reporting(0);
session_start();
if (!isset($_SESSION["login"]) && !isset($_SESSION["senha"])) {
    session_destroy();
    unset($_SESSION["login"]);
    unset($_SESSION["senha"]);
    header("location:../index.php");
}
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}
include_once "headeradmin2.php";
$sql = "SELECT * FROM configs";
$result = $conn->query($sql);
if (0 < $result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        $csspersonali = $row["corfundologo"];
    }
}
echo "<style>\r\n        ";
echo $csspersonali;
echo "        .position-absolute {\r\n  position: absolute !important;\r\n  top : 0;\r\n}\r\n    </style>\r\n    <div id=\"custom-target\"></div>\r\n<div class=\"app-content content\">\r\n        <div class=\"content-overlay\"></div>\r\n        <div class=\"content-wrapper\" > \r\n        <p class=\"text-primary\">Aqui Você Pode Editar os Tema do Painel</p>\r\n            <div class=\"content-header row\">\r\n            </div>\r\n            <div class=\"content-body\" >\r\n                <section id=\"dashboard-ecommerce\">\r\n                    <div class=\"row\" >\r\n                <section id=\"basic-horizontal-layouts\">\r\n                    <div class=\"row match-height\">\r\n                        <div class=\"col-md-6 col-12\">\r\n                            <div class=\"card\">\r\n                                <div class=\"card-header\">\r\n                                    <h4 class=\"card-title\">Editar Css</h4>\r\n                                </div>\r\n                                <div class=\"card-content\">\r\n                                    <div class=\"card-body\">\r\n                                        <form class=\"form form-horizontal\">\r\n                                            <div class=\"form-body\">\r\n                                                <div class=\"row\">\r\n                                                  \r\n\r\n                                                    \r\n                                                    <style type=\"text/css\" media=\"screen\">\r\n      #editor { \r\n        height: 300px;\r\n        border: 5px solid #5A8DEE;\r\n        border-radius: 5px;\r\n        margin-bottom: 10px;\r\n        width: 500px;\r\n    }\r\n\r\n\r\n\r\n    </style>\r\n</head>\r\n<body>\r\n<div id=\"custom-target\"></div>\r\n    <div id=\"editor\">\r\n    <!-- botao salvar -->\r\n</div>\r\n<!-- se a tela for menor que 736 -->\r\n<script>\r\n    if (window.matchMedia(\"(max-width: 736px)\").matches) {\r\n        document.getElementById('editor').style.width='400px';\r\n    }else{\r\n        document.getElementById('editor').style.width='2000px';\r\n    }\r\n</script>\r\n\r\n\r\n\r\n    <script src=\"https://cdn.jsdelivr.net/npm/ace-builds@1.23.0/src-min-noconflict/ace.js\"></script>\r\n    <link href=\"https://cdn.jsdelivr.net/npm/ace-builds@1.23.0/css/ace.min.css\" rel=\"stylesheet\">\r\n    <script src=\"https://code.jquery.com/jquery-3.6.0.min.js\"></script>\r\n\r\n    \r\n\r\n                                                    \r\n                                            \r\n                                                    <div class=\"col-sm-12 d-flex justify-content-end\">\r\n                                                        \r\n                                                        <a onclick=\"editor.execCommand('save')\" class=\"btn btn-primary mr-1 mb-1\" >Editar</a>\r\n                                                        <a href=\"home.php\" class=\"btn btn-light-secondary mr-1 mb-1\">Voltar</a>\r\n                                                    </div>\r\n                                                </div>\r\n                                            </div>\r\n                                        </form>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n\r\n                        <script>\r\n        var editor = ace.edit(\"editor\");\r\n        editor.setTheme(\"ace/theme/twilight\");\r\n        editor.getSession().setMode(\"ace/mode/css\");\r\n        //buscar o css com ajax \r\n        \$.ajax({\r\n        url: 'csspersonalizado.php',\r\n        type: 'POST',\r\n        dataType: 'json',\r\n        success: function (data) {\r\n            editor.setValue(data);\r\n        }\r\n    });\r\n\r\n        document.getElementById('editor').style.fontSize='15px';\r\n        // Salve o código CSS atualizado\r\n        editor.commands.addCommand({\r\n            name: 'save',\r\n            bindKey: {win: 'Ctrl-S', mac: 'Command-S'},\r\n            exec: function (editor) {\r\n                var css = editor.getValue();\r\n                \$.ajax({\r\n                    url: 'csspersonalizado.php',\r\n                    type: 'POST',\r\n                    data: {css: css},\r\n                    success: function (data) {\r\n                        Swal.fire({\r\n  text: 'Salvo com Sucesso!',\r\n  target: '#custom-target',\r\n  customClass: {\r\n    container: 'position-absolute'\r\n  },\r\n  toast: true,\r\n  position: 'bottom-end',\r\n})\r\n /* sumir toast devagar */\r\n    setTimeout(function() {\r\n        \$('.swal2-container').fadeOut('slow');\r\n    }, 2000); // <-- time in milliseconds\r\n                    }\r\n                });\r\n            }\r\n        });\r\n    </script>";

?>