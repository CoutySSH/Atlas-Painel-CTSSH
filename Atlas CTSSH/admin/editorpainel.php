<!DOCTYPE html>
<html>
<head>
    <title>Editor Painel</title>
    <script src="../atlas-assets/js/sweetalert.js"></script>
    <?php error_reporting(0);
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    if(!isset($_SESSION['login']) and !isset($_SESSION['senha'])){
        session_destroy();
        unset($_SESSION['login']);
        unset($_SESSION['senha']);
        header('location:../index.php');
    }

    include('../atlas/conexao.php');
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    include_once 'headeradmin2.php';
        
    $sql = "SELECT * FROM configs";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
            $csspersonali = $row["corfundologo"];
            $textopersonali = $row["textoedit"];
    }

// Recupere o conteúdo da variável $tradutor e converta-o para um array em PHP
$tradutor = $textopersonali;
$linhas = explode("\n", $tradutor);
$substituicoes = array();
foreach ($linhas as $linha) {
    $par = explode("=", $linha);
    if (count($par) === 2) {
        $textoOriginal = trim($par[0]);
        $textoSubstituto = trim($par[1]);
        $substituicoes[] = array('original' => $textoOriginal, 'substituto' => $textoSubstituto);
    }
}

    ?>
    <style>
    <?php echo $csspersonali; ?>
    .position-absolute {
        position: absolute !important;
        top: 0;
    }
    </style>
</head>
<body>
    <div id="custom-target"></div>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper" id="inicialeditor">
            <p class="text-primary">Aqui Você Pode Editar os Tema do Painel</p>
            <div class="content-header row"></div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row">
                        <section id="basic-horizontal-layouts">
                            <div class="row match-height">
                                <div class="col-md-6 col-12">
                                    <div class="card">
                                    <form id="form" action="editorpainel.php" method="POST">
                                        <div class="card-header">
                                            <h4 class="card-title">Editar Css</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <form class="form form-horizontal">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <style type="text/css" media="screen">
                                                            #editor1 {
                                                                height: 300px;
                                                                border: 5px solid #5A8DEE;
                                                                border-radius: 5px;
                                                                margin-bottom: 10px;
                                                                width: 500px;
                                                            }

                                                            #editor2 {
                                                                height: 300px;
                                                                border: 5px solid #5A8DEE;
                                                                border-radius: 5px;
                                                                margin-bottom: 10px;
                                                                width: 500px;
                                                            }
                                                            </style>
                                                            <div id="editor1"></div>
                                                            <div class="card-header">
                                                                <h4 class="card-title">Editar Textos</h4>
                                                            </div>
                                                            <div id="editor2"></div>
                                                            <script>
                                                            if (window.matchMedia("(max-width: 736px)").matches) {
                                                                document.getElementById('editor1').style.width = '400px';
                                                                document.getElementById('editor2').style.width = '400px';
                                                            } else {
                                                                document.getElementById('editor1').style.width = '2000px';
                                                                document.getElementById('editor2').style.width = '2000px';
                                                            }
                                                            </script>
                                                            <script src="https://cdn.jsdelivr.net/npm/ace-builds@1.23.0/src-min-noconflict/ace.js"></script>
                                                            <link href="https://cdn.jsdelivr.net/npm/ace-builds@1.23.0/css/ace.min.css" rel="stylesheet">
                                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                            
    <!-- Restante do código HTML -->

    <div class="col-sm-12 d-flex justify-content-end">
        <input type="hidden" name="textoedit" id="textoedit">
        <a onclick="editor1.execCommand('save')" class="btn btn-primary mr-1 mb-1">Salvar Css</a>
        <button type="button" onclick="saveText()" class="btn btn-primary mr-1 mb-1">Salvar Texto</button>
        <a href="home.php" id="finaleditor" class="btn btn-light-secondary mr-1 mb-1">Voltar</a>
    </div>
</form>
    </div>
    </div>
    </div>

    <script>
    var editor1 = ace.edit("editor1");
    editor1.setTheme("ace/theme/twilight");
    editor1.getSession().setMode("ace/mode/css");
    //buscar o css com ajax 
    $.ajax({
        url: 'csspersonalizado.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            editor1.setValue(data);
        }
    });
    document.getElementById('editor1').style.fontSize = '15px';
    // Salvar o código CSS atualizado
    editor1.commands.addCommand({
        name: 'save',
        bindKey: {
            win: 'Ctrl-S',
            mac: 'Command-S'
        },
        exec: function(editor1) {
            var css = editor1.getValue();
            $.ajax({
                url: 'csspersonalizado.php',
                type: 'POST',
                data: {
                    css: css
                },
                success: function(data) {
                    Swal.fire({
                        text: 'Salvo com Sucesso!',
                        target: '#custom-target',
                        customClass: {
                            container: 'position-absolute'
                        },
                        toast: true,
                        position: 'bottom-end',
                    });
                    /* sumir toast devagar */
                    setTimeout(function() {
                        $('.swal2-container').fadeOut('slow');
                    }, 2000); // <-- time in milliseconds
                }
            });
        }
    });

    var editor2 = ace.edit("editor2");
    editor2.setTheme("ace/theme/twilight");
    editor2.getSession().setMode("ace/mode/html");
    editor2.setValue(`<?php echo $textopersonali; ?>`);
    document.getElementById('editor2').style.fontSize = '15px';

    function saveText() {
        var texto = editor2.getValue();
        document.getElementById('textoedit').value = texto;
        document.getElementById('form').submit(); // Submit o formulário
    }
    </script>
</form>

                        </section>
                        <?php 
                        if (isset($_POST['textoedit'])) {
                            $textoedit = $_POST['textoedit'];
                            $sql = "UPDATE configs SET textoedit = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $textoedit);
                            if ($stmt->execute()) {
                                echo "<script>swal.fire('Salvo com Sucesso!').then(function() {window.location.href='editorpainel.php';});</script>";
                            } else {
                                echo "<script>swal.fire('Erro ao Salvar!').then(function() {window.location.href='editorpainel.php';});</script>";
                            }
                        }
                        ?>
                    </div>
                </section>
            </div>
        </div>
    </div>


</body>

</html>

                       