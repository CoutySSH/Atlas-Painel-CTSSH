<?php


error_reporting(0);
session_start();
set_time_limit(0);
ignore_user_abort(true);
set_include_path(get_include_path() . PATH_SEPARATOR . "../lib2");
include "Net/SSH2.php";
include "../vendor/event/autoload.php";
include "../atlas/conexao.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Adicione logs para debug
$logValues = "Valores das variáveis: categoria={$_POST['categoria']}, usuariofin={$_POST['usuariofin']}, senhafin={$_POST['senhafin']}, validadefin={$_POST['validadefin']}, limitefin={$_POST['limitefin']}";
error_log($logValues);

if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}

include "headeradmin2.php";
unset($_SESSION["whatsapp"]);

if (isset($_SESSION["mensagem_enviada"])) {
    unset($_SESSION["mensagem_enviada"]);
}

$sql2 = "SELECT * FROM servidores";
$result = $conn->query($sql2);

set_time_limit(0);
ignore_user_abort(true);

if (isset($_POST["criaruser"])) {
    $categoria = $_POST["categoria"];
    $usuariofin = $_POST["usuariofin"];
    $senhafin = $_POST["senhafin"];
    $notas = $_POST["notas"];
    $validadefin = $_POST["validadefin"];
    $limitefin = $_POST["limitefin"];
    
    // Adicione os valores ao log novamente para verificar se estão corretos
    $logValues = "Valores das variáveis após o formulário: categoria={$categoria}, usuariofin={$usuariofin}, senhafin={$senhafin}, validadefin={$validadefin}, limitefin={$limitefin}";
    error_log($logValues);

    $_POST["whatsapp"] = str_replace(" ", "", $_POST["whatsapp"]);
    $_POST["whatsapp"] = str_replace("-", "", $_POST["whatsapp"]);
    $_SESSION["whatsapp"] = $_POST["whatsapp"];
    $_SESSION["usuariofin"] = $usuariofin;
    $_SESSION["senhafin"] = $senhafin;
    $_SESSION["validadefin"] = $validadefin;
    $_SESSION["limitefin"] = $limitefin;

    if (empty($usuariofin) || empty($senhafin)) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário e Senha não podem ser vazios!');window.location.href='criarteste.php';</script>";
        exit;
    }

    if (!ctype_alnum($usuariofin) || !ctype_alnum($senhafin)) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário e Senha não podem conter caracteres especiais!');window.location.href='criarteste.php';</script>";
        exit;
    }

    $sql = "SELECT * FROM ssh_accounts WHERE login = '" . $usuariofin . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script language='javascript' type='text/javascript'>alert('Ops.. Usuário já existe!');window.location.href='criarteste.php';</script>";
        exit;
    }

    $sql4 = "SELECT * FROM servidores WHERE subid = '" . $categoria . "'";
    $result4 = $conn->query($sql4);
    $loop = React\EventLoop\Factory::create();
    $servidores_com_erro = [];
    define("SCRIPT_PATH", "./atlasteste.sh");
    $sucess_servers = [];
    $failed_servers = [];
    $sucess = false;

    while ($user_data = mysqli_fetch_assoc($result4)) {
        $tentativas = 0;
        $conectado = false;

        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data["ip"], $user_data["porta"]);

            if ($ssh->login($user_data["usuario"], $user_data["senha"])) {
                $loop->addTimer(0, function () use($ssh) {
                    global $usuariofin, $senhafin, $validadefin, $limitefin;
                    $ssh->exec(SCRIPT_PATH . " " . $usuariofin . " " . $senhafin . " " . $validadefin . " " . $limitefin . " > /dev/null 2>&1 &");
                    $ssh->disconnect();
                });

                $sucess_servers[] = $user_data["nome"];
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
            }
        }

        if (!$conectado) {
            $servidores_com_erro[] = $user_data["ip"];
            $failed_servers[] = $user_data["nome"];
        }
    }

    foreach ($servidores_com_erro as $ip) {
        $sql2 = "SELECT id, ip, porta, usuario, senha FROM servidores WHERE ip = '" . $ip . "'";
        $result2 = mysqli_query($conn, $sql2);
        $user_data2 = mysqli_fetch_assoc($result2);
        $tentativas = 0;
        $conectado = false;

        while ($tentativas < 2 && !$conectado) {
            $ssh = new Net_SSH2($user_data2["ip"], $user_data2["porta"]);

            if ($ssh->login($user_data2["usuario"], $user_data2["senha"])) {
                $loop->addTimer(0, function () use($ssh) {
                    global $usuariofin, $senhafin, $validadefin, $limitefin;
                    $ssh->exec(SCRIPT_PATH . " " . $usuariofin . " " . $senhafin . " " . $validadefin . " " . $limitefin . " > /dev/null 2>&1 &");
                    $ssh->disconnect();
                });

                $sucess_servers[] = $user_data2["nome"];
                $conectado = true;
                $sucess = true;
            } else {
                $tentativas++;
            }
        }

        if (!$conectado) {
            $failed_servers[] = $user_data2["nome"];
        }
    }

    if (!$sucess) {
        echo "<script>alert('Erro ao criar usuário!');window.location.href='criarusuario.php';</script>";
        exit;
    }

    if ($sucess) {
        $sucess_servers_str = implode(", ", $sucess_servers);
        $failed_servers_str = implode(", ", $failed_servers);
        echo "<script>window.location.href = 'testecriado.php?sucess=" . $sucess_servers_str . "&failed=" . $failed_servers_str . "';</script>";

        $validade = $validadefin;
        date_default_timezone_set("America/Sao_Paulo");
        $data = date("Y-m-d H:i:s");
        $data = strtotime($data);
        $data = strtotime("+" . $validadefin . " minutes", $data);
        $data = date("Y-m-d H:i:s", $data);
        $validadefin = $data;

        $sql9 = "INSERT INTO ssh_accounts (login, senha, expira, limite, byid, categoriaid, lastview, bycredit, mainid, status, whatsapp) VALUES ('" . $usuariofin . "', '" . $senhafin . "', '" . $validadefin . "', '" . $limitefin . "', '" . $_SESSION["iduser"] . "', '" . $categoria . "', '" . $notas . "', '0', 'NULL', 'Offline', '" . $_SESSION["whatsapp"] . "')";
        $result9 = mysqli_query($conn, $sql9);

        $datahoje = date("d-m-Y H:i:s");
        $sql10 = "INSERT INTO logs (revenda, validade, texto, userid) VALUES ('" . $_SESSION["login"] . "', '" . $datahoje . "', 'Criou um Teste " . $usuariofin . " de " . $validade . " minutos', '" . $_SESSION["iduser"] . "')";
        $result10 = mysqli_query($conn, $sql10);
    }

    $loop->run();
}
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <p class="text-primary">Aqui você pode criar um teste para seu cliente.</p>
        <div class="content-header row"></div>
        <div class="content-body">
            <section id="dashboard-ecommerce">
                <div class="row">
                    <section id="basic-horizontal-layouts">
                        <div class="row match-height">
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Criar Teste</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form class="form form-horizontal" action="criarteste.php" method="POST">
                                                <div class="form-body">
                                                    <button type="button" class="btn btn-primary mr-1 mb-1" onclick="gerar()">Gerar Aleatorio</button>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Categoria</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <select class="form-control" name="categoria">
                                                                <?php
                                                                $sql = "SELECT * FROM categorias";
                                                                $result = $conn->query($sql);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo "<option value='" . $row["subid"] . "'>" . $row["nome"] . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Login</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" name="usuariofin" placeholder="Login">
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Senha</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" name="senhafin" placeholder="Senha">
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Limite</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" value="1" min="1" name="limitefin" />
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Minutos</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" value="60" min="1" name="validadefin" />
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Notas</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" name="notas" placeholder="Notas">
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Número Whatsapp (NÚMERO IGUAL AO WHATSAPP)</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" class="form-control" name="whatsapp" placeholder="+5511999999999">
                                                        </div>

                                                        <div class="col-12 col-md-8 offset-md-4 form-group">
                                                            <fieldset></fieldset>
                                                        </div>

                                                        <div class="col-sm-12 d-flex justify-content-end">
                                                            <button type="submit" class="btn btn-primary mr-1 mb-1" name="criaruser">Criar</button>
                                                            <button type="reset" class="btn btn-light-secondary mr-1 mb-1">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function gerar() {
                                var usuario = document.getElementsByName("usuariofin")[0];
                                var senha = document.getElementsByName("senhafin")[0];
                                usuario.value = Math.random().toString(36).substr(2, 8);
                                senha.value = Math.random().toString(36).substr(2, 8);
                            }
                        </script>
                    </section>
                </div>
            </section>
        </div>
    </div>
</div>
