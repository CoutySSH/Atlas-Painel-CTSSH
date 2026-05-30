    
<script src="../app-assets/sweetalert.min.js"></script>
<?php
error_reporting(0);
session_start();
include('../atlas/conexao.php');
include('headeradmin2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$sql = "SELECT * FROM servidores ";
          $result = $conn -> query($sql);

          
?>


<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<!-- ajax -->
<script>
    // Função para atualizar as estatísticas de CPU e memória para um servidor específico
function atualizarEstatisticas(id) {
    $.ajax({
        url: 'atualizar_stats.php?id=' + id, // Passa o ID do servidor como parâmetro
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Atualize os elementos HTML específicos para o servidor com o ID correspondente
            $('#cpu-info-' + id).text('Uso CPU: ' + data.cpu);
            $('#ram-info-' + id).text('Uso Memória: ' + data.memoria);
            $('#status-' + id).text('Status: ' + data.status);
        },
        error: function() {
            // Trate erros, se necessário
            console.error('Erro ao atualizar estatísticas para o servidor com o ID ' + id);
        }
    });
}
</script>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
             
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Todos Servidores</h4>
                            <br>
                            <center>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="feather icon-settings"></i> Opções </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="adicionarservidor.php">Adicionar Servidor</a>
                                <a class="dropdown-item" href="adicionarcategoria.php">Adicionar Categoria</a>
                                <a class="dropdown-item" href="categorias.php">Categoria</a>
                                <a class="dropdown-item" href="installmodtodos.php">Instalar Modulos em Todos</a>
                                <a class="dropdown-item" href="limpezageral.php">Limpeza Geral</a>

                            
                        </div>
                        </center>
                        <br>
                        <div id="alerta">
                            </div>
                        <script>


if (window.innerWidth < 678) {

    document.write('<div class="alert alert-warning" role="alert"> <strong>Atenção!</strong> Mova para lado para Fazer Alguma Ação! </div>');
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 3000);
}

</script>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <!-- nao mostar o sroll -->
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                    <th> Nome </th>
                                                    <th> Ip Servidor </th>
                                                    <th> Status </th>
                                                    <th> Categoria </th>
                                                    <th> Tamanho </th>
                                                    <th> CPU </th>
                                                    <th> RAM </th>
                                                    <th> Onlines </th>
                                                    <th> Editar </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                          set_include_path(get_include_path() . PATH_SEPARATOR . '../lib2');
                          include ('Net/SSH2.php');
                          //result e result2
                          while ($user_data = mysqli_fetch_assoc($result)) {
                            // verifica se servidor esta online
                            $ip = $user_data['ip'];
                            echo "<script>atualizarEstatisticas($user_data[id]);</script>";
                            $porta = '22';
                            $fp = @fsockopen($ip, $porta, $errno, $errstr, 1);
                            if (!$fp) {
                              $status = "Offline";
                            } else {
                              $status = "Online";
                            }
                            echo "<td>" . $user_data['nome'] . "</td>";
                            echo "<td>" . $user_data['ip'] . "</td>";
                            echo "<td>" . $status . "</td>";
                            echo "<td>" . $user_data['subid'] . "</td>";
                            echo "<td>" . $user_data['serverram'] . " RAM / " . $user_data['servercpu'] . " CPUS</td>";
                            echo "<td id='cpu-info-$user_data[id]'><span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Buscando... </td>";
                            echo "<td id='ram-info-$user_data[id]'><span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Buscando... </td>";
                            echo "<td>" . $user_data['onlines'] . "</td>";
                            echo '
                               <td><div class="btn-group mb-1">
                                    <div class="dropdown">
                                       <button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         Ações
                                        </button>
                                       <div class="dropdown-menu">
                                           <a class="dropdown-item" href="editarservidor.php?id='.$user_data['id'].'">Editar</a>
                                           <a class="dropdown-item" onclick="reiniciar('.$user_data['id'].')">Reiniciar</a>
                                           <a class="dropdown-item" onclick="modulos('.$user_data['id'].')">Modulos</a>
                                           <a class="dropdown-item" onclick="sincronizar('.$user_data['id'].')">Sincronizar</a>
                                           <a class="dropdown-item" onclick="limpeza('.$user_data['id'].')">Limpeza</a>
                                           <a class="dropdown-item" onclick="excluir('.$user_data['id'].')">Deletar</a>
                                        </div>
                                     </div>
                                    </div>
                                 </td>
                               ';
                            echo "</tr>";


                          }
                            
                        
                          
                        
                          ?>

  </tbody>
    </table>
    </div>
    </div>
    </div>
                                                <script>



                              function reiniciar(id) {
                                swal({
                                    title: "Tem certeza?",
                                    text: "Você deseja reiniciar o servidor?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            var alerta = document.getElementById("alerta");
                                            alerta.innerHTML = "<div class='alert alert-success' role='alert'> <strong>Reiniciando!</strong> Reiniciando o servidor, aguarde alguns segundos! </div>";
                                            window.setTimeout(function() {
                                                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                                                    $(this).remove(); 
                                                });
                                            }, 3000);
                                            $.ajax({
                                                url: 'comandos.php?id=' + id + '&comando=reboot',
                                                type: 'POST',
                                                data: {
                                                    id: id,
                                                    comando: 'reboot'
                                                },
                                                success: function (data) {
                                                    if (data == 'Não foi possível autenticar') {
                                                        swal("Erro!", "Não foi possível autenticar", "error");
                                                    }
                                                    if (data == 'Não foi possível conectar ao servidor') {
                                                        swal("Erro!", "Não foi possível conectar ao servidor", "error");
                                                    }
                                                    if (data == 'Comando enviado com sucesso') {
                                                        swal("Sucesso!", "Reiniciado com sucesso", "success");
                                                    }
                                                }
                                            });
                                        } else {
                                            swal("Cancelado!");
                                        }
                                    });
                            }
                            function modulos(id) {
                                swal({
                                    title: "Tem certeza?",
                                    text: "Você deseja instalar os modulos no servidor?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            var alerta = document.getElementById("alerta");
                                            alerta.innerHTML = "<div class='alert alert-success' role='alert'> <strong>Instalando!</strong> Instalando os modulos, aguarde alguns segundos! </div>";
                                            window.setTimeout(function() {
                                                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                                                    $(this).remove(); 
                                                });
                                            }, 3000);
                                            $.ajax({
                                                url: 'installmodul.php?id=' + id,
                                                type: 'POST',
                                                data: {
                                                    id: id
                                                },
                                                success: function (data) {
                                                    //remover espaço em branco e quebra de linha
                                                    var dataSemEspacos = data.replace(/\s+/g, '');
                                                    if (dataSemEspacos === 'Nãofoipossívelautenticar') {
                                                        swal("Erro!", "Falha na autenticação do servidor", "error");
                                                    }

                                                    if (dataSemEspacos === 'Servidorconectadocomsucesso') {
                                                        swal("Sucesso!", "Módulos instalados com sucesso", "success").then(function () {
                                                            location.reload();
                                                        });
                                                    }
                                                    
                                                }
                                            });
                                        } else {
                                            swal("Cancelado!");
                                        }
                                    });
                            }
                            function sincronizar(id) {
                                swal({
                                    title: "Tem certeza?",
                                    text: "Você deseja sincronizar o servidor?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            var alerta = document.getElementById("alerta");
                                            alerta.innerHTML = "<div class='alert alert-success' role='alert'> <strong>Sincronizando!</strong> Sincronizando o servidor, aguarde alguns segundos! </div>";
                                            window.setTimeout(function() {
                                                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                                                    $(this).remove(); 
                                                });
                                            }, 3000);
                                            $.ajax({
                                                url: 'sincronizar.php?id=' + id,
                                                type: 'POST',
                                                data: {
                                                    id: id
                                                },
                                                success: function (data) {
                                                    if (data == 'Não foi possível conectar ao servidor') {
                                                        swal("Erro!", "Falha na autenticação do servidor", "error");
                                                    }
                                                    if (data == 'Comando enviado com sucesso') {
                                                        swal("Sucesso!", "Servidor sincronizado com sucesso", "success"); 
                                                    }
                                                    
                                                }
                                            });
                                        } else {
                                            swal("Cancelado!");
                                        }
                                    });
                            }
                            function limpeza(id) {
                                swal({
                                    title: "Tem certeza?",
                                    text: "Você deseja limpar o servidor?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            $.ajax({
                                                url: 'limpeza.php?id=' + id,
                                                type: 'POST',
                                                data: {
                                                    id: id
                                                },
                                                success: function (data) {
                                                    if (data == 'Login Failed') {
                                                        swal("Erro!", "Falha na autenticação do servidor", "error");
                                                    }else{
                                                        swal("Sucesso!", "Servidor limpo com sucesso", "success");
                                                    }
                                                }
                                            });
                                        } else {
                                            swal("Cancelado!");
                                        }
                                    });
                            }
                            function excluir(id) {
                                swal({
                                    title: "Tem certeza?",
                                    text: "Você deseja excluir o servidor?",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            $.ajax({
                                                url: 'dellserv.php?id=' + id,
                                                type: 'POST',
                                                data: {
                                                    id: id
                                                },
                                                success: function (data) {
                                                    swal("Servidor excluido com sucesso!", {
                                                        icon: "success",
                                                    });
                                                    setTimeout(function () {
                                                        location.reload();
                                                    }, 2000);
                                                }
                                            });
                                        } else {
                                            swal("Cancelado!");
                                        }
                                    });
                            }
                        </script>
    

                                        
                    
                          
                                        
                        
    <!-- END: Content-->
    <script src="cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="../app-assets/js/scripts/datatables/datatable.js"></script>
    <script>
    $('#myTable').DataTable({

        /* traduzir somente */
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponível",
            "infoFiltered": "(filtrado de _MAX_ registros no total)",
            "search": "Pesquisar:",
            "paginate": {
                "first": "",
                "last": "",
                "next": "",
                "previous": ""
            }
        }
    
    });


</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
