    
<script src="../app-assets/sweetalert.min.js"></script>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php

    error_reporting(0);
    session_start();
    include('../atlas/conexao.php');
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    $conn->set_charset("utf8mb4");
    mysqli_set_charset($conn, "utf8mb4");

    include('headeradmin2.php');
    $dominioserver = 'apiwhats.atlaspainel.com.br';
    
    mysqli_set_charset($conn, "utf8mb4");
    $sqlmens = "SELECT * FROM mensagens WHERE byid = '$_SESSION[iduser]'";
    $resultmens = mysqli_query($conn, $sqlmens);
    $byid = $_SESSION['iduser'];
          $sql2 = "SELECT * FROM whatsapp WHERE byid = '$byid'";
          $result1 = mysqli_query($conn, $sql2);
          $row1 = mysqli_fetch_assoc($result1);
          $chaveapiatual = $row1['sessao'];
          $tokenapiatual = $row1['token'];

    $sql = "SELECT whatsapp FROM accounts WHERE id = '$_SESSION[iduser]'";
    $result = mysqli_query($conn, $sql);   
    $row = mysqli_fetch_assoc($result);
    $whatsapp = $row['whatsapp'];   
    include_once 'suspenderrev.php';
    ?>
    <!-- plugins:css -->
    <!-- endinject -->
    <link rel="stylesheet" href="../atlas-assets/css/style.css">
    
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo $icon; ?>" />
  </head>
  <body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">    <script src="../app-assets/sweetalert.min.js"></script>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
             
                <div class="col-12">
                    <div class="card">
                    
                        <div class="card-header">
                            <h4 class="card-title">Whatsapp</h4>
                            <div class="col">  
                            <div class="divider divider-success">
                                <div style='font-size:25px;' class="divider-text" id="statusconecwhats"></div>
                            </div>
                             <div class="divider divider-danger">
                                <div style='font-size:25px;' class="divider-text" id="statusdescwhats"></div>
                            </div>
                            <div class="divider divider-primary">
    <div style='font-size:25px;' class="divider-text" id="carregando"></div>
</div>

<script>
    // Exibe o spinner de carregamento quando a pÃ¡gina Ã© carregada
    window.addEventListener('load', function () {
        document.getElementById('carregando').innerHTML = 'Carregando...';
    });
</script>

                            

                            <!-- description -->
                            <form action="whatsconect.php" method="post">
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                <p class="card-text">Para adiquirir a chave e o token API, acesse o site <a href="https://painelwhats.atlaspainel.com.br" target="_blank">https://painelwhats.atlaspainel.com.br</a></p>

                                    <p class="card-text">NÃ£o Somos Responsaveis por qualquer tipo de bloqueio, banimento ou qualquer outro tipo de puniÃ§Ã£o que o whatsapp venha a aplicar em sua conta.</p>
                                    <h6 >Chave API</h6>
                                    <input class="form-control" type="text" name="chaveapi" id="chaveapi" value="<?php echo $chaveapiatual; ?>" >
                                    <br>
                                    <h6>Token API</h6>
                                    <input class="form-control" type="text" name="tokenapi" id="tokenapi" value="<?php echo $tokenapiatual; ?>" >
                                    <br>
                                    <h6>Seu Numero</h6>
                                    <input class="form-control" type="text" name="numerowhats" id="numerowhats" value="<?php echo $whatsapp ?>">
                                    <br>
                                    <button type="submit" class="btn btn-primary" name="salvartokenapi">Salvar</button>
                                    <br>
                                </div>
        <a type="button" class="btn mr-1 mb-1 btn-outline-info btn-sm" data-toggle="modal" data-target="#editNetworkModal">
          Adicionar Mensagem
        </a>
        <a type="button" class="btn mr-1 mb-1 btn-outline-info btn-sm" id="backuppainel">
          Backup do Painel
        </a>
        </form>
        <script>
    //ao clicar backuppainel GET para o arquivo sendbackup.php
    $("#backuppainel").click(function() {
        $.ajax({
            url: 'sendbackup.php',
            type: 'GET',
            success: function(response) {
                swal("Sucesso!", `Backup Enviado com Sucesso!`, "success");
            },
            error: function(error) {
                console.error(`Erro ao enviar o Backup:`, error);
            }
        });
    });
</script>
        <?php
        if (isset($_POST['salvartokenapi'])) {
          //salvar token api
          $chaveapi = $_POST['chaveapi'];
          $tokenapi = $_POST['tokenapi'];
          $byid = $_SESSION['iduser'];
            $novonumerowhats = $_POST['numerowhats'];
          $tokenapi = mysqli_real_escape_string($conn, $tokenapi);
          $chaveapi = mysqli_real_escape_string($conn, $chaveapi);
          $novonumerowhats = mysqli_real_escape_string($conn, $novonumerowhats);

          // Verificar se jÃ¡ existe um registro com o mesmo byid
          $sql = "SELECT * FROM whatsapp WHERE byid = '$byid'";
          $result = mysqli_query($conn, $sql);

          if (mysqli_num_rows($result) > 0) {
              // Se jÃ¡ existe um registro, atualize-o
              $sqlsave = "UPDATE whatsapp SET token = '$tokenapi', sessao = '$chaveapi' WHERE byid = '$byid'";
              $message = 'Registro atualizado com sucesso';
          } else {
              // Se nÃ£o existe um registro, insira um novo
              $sqlsave = "INSERT INTO whatsapp (token, sessao, byid) VALUES ('$tokenapi', '$chaveapi', '$byid')";
              $message = 'Novo registro inserido com sucesso';
          }
          $sqlsavenum = "UPDATE accounts SET whatsapp = '$novonumerowhats' WHERE id = '$byid'";
            $resultnum = mysqli_query($conn, $sqlsavenum);

          $resultsave = mysqli_query($conn, $sqlsave);

          if ($resultsave) {
              echo "<script>swal('Token API Salvo com Sucesso','', 'success').then((value) => {window.location.href = 'crtlwhatsapp.php';});</script>";
          } else {
              echo "<script>sweetAlert('Oops...', 'Erro ao Salvar Token API!', 'error');</script>";
          }



        }
        ?>

        <script>
  //se clica no botao de ativar ou desativar
  $("#actionwwhats").click(function() {
    const action = $(this).data('action');
    
    swal("Sucesso!", `Whatsapp ${action}!`, "success").then(() => {
      $.ajax({
        url: 'crtlwhatsapp.php', // Nome do arquivo PHP que contÃ©m a lÃ³gica de ativar/desativar
        type: 'POST',
        data: { action: action },
        success: function(response) {
          location.reload();
        },
        error: function(error) {
          console.error(`Erro ao ${action}ar o WhatsApp:`, error);
        }
      });
    });
  });
</script>

                        </div>
                        
                        <script>


if (window.innerWidth < 678) {

    document.write('<div class="alert alert-warning" role="alert"> <strong>AtenÃ§Ã£o!</strong> Mova para lado para Fazer Alguma AÃ§Ã£o! </div>');
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
}

</script>

<div class="card-content">
    
<form method="POST" action="">
                            <div class="card-body card-dashboard">

                           

                      

                                <!-- nao mostar o sroll -->
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                    <th>Mensagem</th>
                                                    <th>FunÃ§Ã£o</th>
                                                    <th>Ativo</th>
                                                    <th>Editar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php while ($rowmens = mysqli_fetch_assoc($resultmens)) { ?>
                                                    <tr>
                                                    <td><?php echo nl2br(htmlspecialchars($rowmens['mensagem'], ENT_QUOTES, 'UTF-8')); ?></td>
                                                    <td><?php echo $rowmens['funcao']; ?></td>
                                                    <td><?php echo $rowmens['ativo']; ?></td>
                                                    <td>
    <a type="button" class="btn mr-1 mb-1 btn-outline-info btn-sm edit-btn" data-toggle="modal" data-target="#editmensagem" data-id="<?php echo $rowmens['id']; ?>">
        Editar
    </a>
</td>

                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

  </tbody>
    </table>
    </form>

    <hr>
    <script>
$(document).ready(function() {
    // Captura o evento de clique no botÃ£o de ediÃ§Ã£o
    $('.edit-btn').click(function() {
        // ObtÃ©m o ID da mensagem
        
        var id = $(this).data('id');
        // Atualiza o valor do campo oculto no formulÃ¡rio
        $('#edit_id').val(id);

        // FaÃ§a uma requisiÃ§Ã£o AJAX para obter os detalhes da mensagem usando o ID
        $.ajax({
            url: 'crtlwhatsapp.php', // Arquivo PHP para obter os detalhes da mensagem
            type: 'POST',
            data: { id: id },
            success: function(response) {
                // Atualize os campos do formulÃ¡rio no modal com os detalhes da mensagem
                var mensagem = response.mensagem;
                var funcao = response.funcao;
                var ativo = response.ativo;
                var hora = response.hora;

                $('#edit_mensagem').val(mensagem);
                $('#edit_funcao').val(funcao);
                $('#edit_ativo').val(ativo);
                $('#edit_hora').val(hora);

                // Exiba o modal
                $('#editmensagem').modal('show');
                function exibirOcultarHora() {
        var funcaoSelecionada = document.getElementById("edit_funcao").value;
        var horaField = document.getElementById("hora-field");

        if (funcaoSelecionada === "contaexpirada" || funcaoSelecionada === "revendaexpirada") {
            horaField.style.display = "block";
        } else {
            horaField.style.display = "none";
        }
    }

    // Chamar a funÃ§Ã£o inicialmente
    exibirOcultarHora();

    // Chamar a funÃ§Ã£o sempre que a opÃ§Ã£o for alterada
    document.getElementById("edit_funcao").addEventListener("change", exibirOcultarHora);
            },
            error: function() {
                // Exiba uma mensagem de erro, se necessÃ¡rio
            }
        });
    });
});
</script>


<div class="modal fade" id="editmensagem" tabindex="-1" role="dialog" aria-labelledby="ConfigurarappLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="ConfigurarappLabel">Editar Mensagem</h5>
            </div>
            <div class="modal-body">
                <form action="whatsconect.php" method="POST">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_mensagem">Mensagem:</label>
                        <textarea class="form-control" name="edit_mensagem" rows="10" id="edit_mensagem"></textarea>
                    </div>
                    <div class="form-group">
    <label for="edit_funcao">Selecione a FunÃ§Ã£o:</label>
    <select class="form-control select2-size-sm" name="edit_funcao" id="edit_funcao">
        <option value="criarusuario">Quando Criar UsuÃ¡rio</option>
        <option value="criarteste">Quando Criar Teste</option>
        <option value="criarrevenda">Quando Criar Revenda</option>
        <option value="contaexpirada">Quando UsuÃ¡rio Expirar</option>
        <option value="revendaexpirada">Quando Revenda Expirar</option>
    </select>
</div>

<div class="form-group" id="hora-field" style="display: none;">
    <label for="edit_hora">Apartir de Qual Horario:</label>
    <input type="time" class="form-control" name="edit_hora" id="edit_hora">
</div>

                    <div class="form-group">
                        <label for="edit_ativo">Mensagem Ativa:</label>
                        <select class="form-control select2-size-sm" name="edit_ativo" id="edit_ativo">
                        <option value="ativada">Ativada</option>
                                                    <option value="desativado">Desativado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn mb-1 btn-danger btn-lg btn-block" name="deletar">Apagar</button>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" name="editar">Salvar AlteraÃ§Ãµes</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
function anti_sql($input)
{
    $seg = preg_replace_callback("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i", function($match) {
        return '';
    }, $input);
    $seg = trim($seg);
    $seg = strip_tags($seg);
    $seg = addslashes($seg);
    return $seg;
}

if (isset($_POST['editar'])) {
  $id = $_POST['edit_id'];
  $mensagem = $_POST['edit_mensagem'];
  $funcao = $_POST['edit_funcao'];
  $ativo = $_POST['edit_ativo'];
  $hora = $_POST['edit_hora'];
  //anti sql injection
    $mensagem = anti_sql($mensagem);
    $funcao = anti_sql($funcao);
    $ativo = anti_sql($ativo);
    $hora = anti_sql($hora);

  // Definir a codificaÃ§Ã£o para utf8mb4
  mysqli_set_charset($conn, "utf8mb4");

  $sql = "UPDATE mensagens SET mensagem='$mensagem', funcao='$funcao', ativo='$ativo', hora='$hora', byid='$_SESSION[iduser]' WHERE id='$id'";
  $result = mysqli_query($conn, $sql);
  if ($result) {
      echo "<script>swal('Mensagem Editada com Sucesso','','success').then((value) => {window.location.href = 'crtlwhatsapp.php';});;</script>";
  } else {
      echo "<script>sweetAlert('Oops...', 'Erro ao Editar Mensagem!', 'error');</script>";
  }
}

if (isset($_POST['deletar'])) {
    $id = $_POST['edit_id'];
    //anti sql injection
    $id = anti_sql($id);
    $sql = "DELETE FROM mensagens WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>swal('Mensagem Apagada com Sucesso','','success').then((value) => {window.location.href = 'crtlwhatsapp.php';});;</script>";
    } else {
        echo "<script>alert('Erro ao Apagar Mensagem!');</script>";
    }
}
?>

<div class="modal fade" id="Configurarapp" tabindex="-1" role="dialog" aria-labelledby="ConfigurarappLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ConfigurarappLabel">Conectar Whatsapp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                    <div class="form-group">
                        <label for="LinkBackground" id='statusconectar'></label>
                    </div>
                    <center>
                    <div id="qrcode-container"></div>
<div id="statusconectar"></div>

<div id="remaining-time"></div>
</center>
       
                    <!-- Adicione aqui os outros campos -->

                    <div class="modal-footer">
                        <a type="button" id='fechar' class="btn btn-secondary" data-dismiss="modal">Fechar</a>
                        <a id="conectarwhats" type="button" class="btn btn-primary">Conectar</a>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
  //ao clicar em fechar recarrega a pagina
  $('#fechar').click(function() {
    location.reload();
});
</script>


<!-- Campos para editar uma rede -->
<!-- Modal para editar uma rede -->
<div class="modal fade" id="editNetworkModal" tabindex="-1" role="dialog" aria-labelledby="editNetworkModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editNetworkModalLabel">Adicionar Mensagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form action="whatsconect.php" method="POST">
                                              <div class="form-group">
                                              <label>Mensagem:</label>
                                      <textarea class="form-control" name="mensagem" rows="10" id="mensagemTextArea">Exemplo: Teste Criado com Sucesso!
                                      Seu teste expira em {expira}!
                                      Seu login Ã© {login} e sua senha Ã© {senha}!
                                      </textarea>
                                      <br>
                                      <div class="form-group">
                                        <label>Selecione a FunÃ§Ã£o</label>
                                      </div>
                                      <div class="form-group">
                                        <select class="form-control select2-size-sm" name="funcao" id="funcaoSelect">
                                          <option value="criarusuario">Quando Criar UsuÃ¡rio</option>
                                          <option value="criarteste">Quando Criar Teste</option>
                                          <option value="criarrevenda">Quando Criar Revenda</option>
                                          <option value="contaexpirada" >Quando Usuario Expirar</option>
                                          <option value="revendaexpirada" >Quando Revenda Expirar</option>
                                        </select>
                                      </div>
                                      <div class="form-group" id="hora-add" style="display: none;">
                                              <label for="add-hora">Apartir de Qual Horario:</label>
                                              <input type="time" class="form-control" name="add-hora" id="add-hora">
                                          </div>

                                      <script>
                                        // ObtÃ©m o elemento do <textarea> e do <select>
                                        const mensagemTextArea = document.getElementById('mensagemTextArea');
                                        const funcaoSelect = document.getElementById('funcaoSelect');
                                        const horaAdd = document.getElementById('hora-add');


                                        // Mapeia as opÃ§Ãµes selecionadas para seus respectivos textos
                                        const mensagens = {
                                          criarusuario: 'ðŸŽ‰ Usuario Criado ðŸŽ‰ <br><br>\n\n' +
                                          'ðŸ”Ž Usuario: {usuario} <br>\n' +
                                          'ðŸ”‘ Senha: {senha} <br>\n' +
                                          'ðŸŽ¯ Validade: {validade} <br>\n' +
                                          'ðŸ•Ÿ Limite: {limite} <br><br>\n\n' +
                                          'ðŸŒLink de RenovaÃ§Ã£o: https://{dominio}/renovar.php <br>\n' +
                                          'Esse link ðŸ‘† servirÃ¡ para vocÃª fazer as suas renovaÃ§Ãµes',
                                          criarteste: 'ðŸŽ‰ Teste Criado ðŸŽ‰ <br><br>\n\n' +
                                          'ðŸ”Ž Usuario: {usuario} <br>\n' +
                                          'ðŸ”‘ Senha: {senha} <br>\n' +
                                          'ðŸŽ¯ Validade: {validade} Minutos <br>\n' +
                                          'ðŸ•Ÿ Limite: {limite} <br><br>\n\n' +
                                          'ðŸŒLink de RenovaÃ§Ã£o: https://{dominio}/renovar.php\n' +
                                          'Esse link ðŸ‘† servirÃ¡ para vocÃª fazer as suas renovaÃ§Ãµes',
                                          criarrevenda: 'ðŸŽ‰ Revenda Criada ðŸŽ‰ <br><br>\n\n' +
                                          'ðŸ”Ž Revenda: {usuario}\n' +
                                          'ðŸ”‘ Senha: {senha}\n' +
                                          'ðŸŽ¯ Validade: {validade}\n' +
                                          'ðŸ•Ÿ Limite: {limite} <br><br>\n\n' +
                                          'ðŸ’¥ Obrigado por usar nossos serviÃ§os!\n' +
                                          'ðŸŒLink do Painel: https://{dominio}/\n' +
                                          'Esse link ðŸ‘† servirÃ¡ para vocÃª acessar o painel de revenda',
                                          contaexpirada: 'ðŸ˜© Sua conta esta prestes a vencer ðŸ˜© <br><br>\n\n' +
                                          'ðŸ”Ž Usuario: {usuario}\n' +
                                          'ðŸ”‘ Senha: {senha}\n' +
                                          'ðŸŽ¯ Validade: {validade}\n' +
                                          'ðŸ•Ÿ Limite: {limite} <br><br>\n\n' +
                                          'ðŸŒLink de RenovaÃ§Ã£o: https://{dominio}/renovar.php\n' +
                                          'Esse link ðŸ‘† servirÃ¡ para vocÃª fazer as suas renovaÃ§Ãµes',
                                          revendaexpirada: 'ðŸ˜© Sua conta esta prestes a vencer ðŸ˜© <br><br>\n\n' +
                                          'ðŸ”Ž Revenda: {usuario}\n' +
                                          'ðŸ”‘ Senha: {senha}\n' +
                                          'ðŸŽ¯ Validade: {validade}\n' +
                                          'ðŸ•Ÿ Limite: {limite} <br><br>\n\n' +
                                          'ðŸ’¥ Acesse o Painel para Renovar sua Revenda'

                                        };

                                        // FunÃ§Ã£o para atualizar o <textarea>
                                        function atualizarTextArea() {
                                            const funcaoSelecionada = funcaoSelect.value;
                                            const mensagemSelecionada = mensagens[funcaoSelecionada];
                                            mensagemTextArea.value = mensagemSelecionada;

                                            if (funcaoSelecionada === 'contaexpirada' || funcaoSelecionada === 'revendaexpirada') {
                                                horaAdd.style.display = 'block';
                                            } else {
                                                horaAdd.style.display = 'none';
                                            }
                                        }

                                        // Aguarda o carregamento completo do documento HTML
                                        document.addEventListener('DOMContentLoaded', atualizarTextArea);

                                        // Atualiza o <textarea> e exibe/oculta o campo de hora quando houver uma alteraÃ§Ã£o no <select>
                                        funcaoSelect.addEventListener('change', atualizarTextArea);
                                    </script>
                                             
                                          
                                             

                                            <div class="form-group">
                                                <label>Mensagem Ativa</label>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control select2-size-sm" name="ativo">
                                                  <option value="ativada">Ativada</option>
                                                    <option value="desativado">Desativado</option>
                                                </select>
                                            </div> 
                                            
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                            <button type="submit" class="btn btn-primary" name="adicionar">Salvar AlteraÃ§Ãµes</button>

                                          </div>
                                        </form>
                                        <?php
                                        if (isset($_POST['adicionar'])) {
                                          mysqli_set_charset($conn, "utf8mb4");
                                            $mensagem = $_POST['mensagem'];
                                            $funcao = $_POST['funcao'];
                                            $ativo = $_POST['ativo'];
                                            $hora = $_POST['add-hora'];
                                            //anti sql injection
                                            $mensagem = anti_sql($mensagem);
                                            $funcao = anti_sql($funcao);
                                            $ativo = anti_sql($ativo);
                                            $hora = anti_sql($hora);
                                            $verifiq = "SELECT * FROM mensagens WHERE funcao='$funcao' AND byid='$_SESSION[iduser]'";
                                            $resultverifiq = mysqli_query($conn, $verifiq);
                                            if (mysqli_num_rows($resultverifiq) > 0) {
                                                echo "<script>sweetAlert('Erro','FunÃ§Ã£o jÃ¡ Cadastrada!','error')</script>";
                                                exit();
                                            }
                                            $sql = "INSERT INTO mensagens (mensagem, funcao, ativo, hora, byid) VALUES ('$mensagem', '$funcao', '$ativo', '$hora', '$_SESSION[iduser]')";
                                            $result = $conn->query($sql);
                                            echo "<script>sweetAlert('Sucesso!', 'Mensagem Adicionada com Sucesso!', 'success').then((value) => { window.location.href = 'crtlwhatsapp.php'; });</script>";
                                        }
                                        ?>
</div>
</div>
</div>
<div class="modal fade" id="addNetworkModal" tabindex="-1" role="dialog" aria-labelledby="addNetworkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNetworkModalLabel">Verificar Status ConexÃ£o</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Campos para adicionar uma nova rede -->
                <div class="form-group">
                    <center>
                        <h5>Status</h5>
                    </div>
                    <div class="dropdown-divider"></div>
                    <br>
                    <div class="form-group">
                    <center>
                        <h6 for="status" id="status"></h6>
                    </div>
                    <center>
                <a class="btn btn-primary" id="verificarconexao" value="Verificar ConexÃ£o">Verificar ConexÃ£o</a>
                <br>
            </div>
        </div>
    </div>
</div>

<script>

const url = 'https://<?php echo $dominioserver; ?>/instance/connectionState/<?php echo $chaveapiatual; ?>';

const headers = {
  'accept': '*/*',
  'Authorization': 'Bearer <?php echo $tokenapiatual; ?>',
  'Content-Type': 'application/json'
};

fetch(url, {
  method: 'GET',
  headers: headers
})
  .then(response => response.json())
  .then(data => {
    //console.log(data);
    if (data.state === 'open') {
      $('#statusconecwhats').html('Whatsapp Conectado');
      //remove carregando
      $('#carregando').remove();
    } else {
      $('#statusdescwhats').html('Desconectado');
      //remove carregando
      $('#carregando').remove();
    }
  })
  .catch(error => {
    console.error(error);

  });


</script>


<!-- ...cÃ³digo anterior... -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
