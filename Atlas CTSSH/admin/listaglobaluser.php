<?php error_reporting(0);
session_start();
include('../atlas/conexao.php');
include('headeradmin2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

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

//anti sql injection na $_GET['search']
$_GET['search'] = anti_sql($_GET['search']);
if (!empty($_GET['search'])){
    $search = $_GET['search'];
    $sql = "SELECT * FROM ssh_accounts WHERE login LIKE '%$search%' AND byid = '$_SESSION[iduser]' ORDER BY expira ASC";
    $result = $conn -> query($sql);
  }else{
    $sql = "SELECT * FROM ssh_accounts";
    $result = $conn -> query($sql);
  }
  
    $sql44 = "SELECT * FROM configs";
    $result44 = $conn -> query($sql44);
    while ($row44 = $result44->fetch_assoc()) {
      $deviceativo = $row44['deviceativo'];
    }
         
   if ($deviceativo == "1") {
    $limpardeviceids = '<button type="button" class="btn btn-outline-danger btn-lg btn-block" onclick="limpardeviceids()">Limpar Device IDs</button>';
     
  }
  ?>
  
   <script>
  function limpardeviceids() {
    swal({
    title: "Tem certeza?",
    text: "VocÃª nÃ£o poderÃ¡ reverter isso!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location.href = "limpardeviceids.php";
    } else {
      swal("Cancelado!");
    }
  });
  }
  //ao abrir a pagina mostrar o loader 

  </script>

      <script>
    
  
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<style>
.btn-whatsapp {
  position: relative;
  display: inline-block;
  border-radius: 50%;
}

.btn-whatsapp:hover .whatsapp-fields {
  display: block;
  z-index: 9999; 
}

.whatsapp-fields {
  display: none;
  position: absolute;
  top: -120px; /* Valor negativo para abrir para cima */
  left: -90px;
  width: 200px;
  padding: 10px;
  background-color: #2a215e;
  border-radius: 5px;
}

.message-input {
  width: 100%;
  padding: 5px;
  background-color: #2a215e;
  margin-bottom: 5px;
}

.send-button {
  width: 100%;
  padding: 5px;
  background-color: #5CB85C;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

</style>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">

<div class="app-content content">
    
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
             
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Lista de Todos Usuarios</h4>
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
                            
                            <div class="card-body card-dashboard">
                                
                                <!-- nao mostar o sroll -->
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>Usuario</th>
                                                        <th>Limite</th>
                                                        <th>categoria</th>
                                                        <th>Validade</th>
                                                        <th>Status</th>
                                                        <th>Notas</th>
                                                        <th>Editar</th>
                                                    </tr>
                                                </thead>

                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        $id = $row['id'];
                                                        $login = $row['login'];
                                                        $senha = $row['senha'];
                                                        $limite = $row['limite'];
                                                        $validade = $row['expira'];
                                                        $status = $row['status'];
                                                        $deviceid = $row['deviceid'];
                                                        $deviceativo = $row['deviceativo'];
                                                        $categoria = $row['categoriaid'];
                                                        $suspenso = $row['mainid'];
                                                        $notas = $row['lastview'];
                                                        // DefiniÃ§Ã£o da classe CSS
                                                        
                                                        $expira = $row['expira'];
                                                        $expira = date('d/m/Y', strtotime($expira));
                                                        $expira2 = $expira;
                                                        $sql2 = "SELECT * FROM onlines WHERE usuario = '$login'";
                                                        $result2 = $conn -> query($sql2);
                                                        $row2 = $result2->fetch_assoc();
                                                        $usando = $row2['quantidade'];

                                                        $today = strtotime(date('Y-m-d')); // Convert today's date to a timestamp
                                                        $expiraTimestamp = strtotime(str_replace('/', '-', $expira2)); // Convert the given date to a timestamp
                                                        
                                                        if ($expiraTimestamp == $today) {
                                                            $expira = "<label class='badge badge-warning' style='color: #fff;'>{$expira2}</label>"; // Yellow for today's date
                                                        } elseif ($expiraTimestamp < $today) {
                                                            $expira = "<label class='badge badge-danger' style='color: #fff;'>{$expira2}</label>"; // Red for expired dates
                                                        } else {
                                                            $expira = "<label class='badge badge-success' style='color: #fff;'>{$expira2}</label>"; // Green for dates not expired
                                                        }
                                                        
                                                        if ($deviceativo == "1") {
                                                            $deviceativo = "Sim";
                                                        }else{
                                                            $deviceativo = "NÃ£o";
                                                        }
                                                        if ($deviceid == "") {
                                                            $deviceid = "Nenhum";
                                                        }
                                                        echo "<tr>
                                                        <td>$login</td>
                                                        ";
                                                        if ($usando == 0) {
                                                            echo "<td>".$limite."</td>";
                                                          }else{
                                                            if ($usando > $limite) {
                                                              echo "<td><label class='badge badge-danger' style='color: #fff;'> $usando / $limite </label></td>";
                                                            }else{
                                                              echo "<td><label class='badge badge-success' style='color: #fff;'> $usando / $limite </label></td>";
                                                            }
                                                          }
                                                        echo "
                                                        <td>$categoria</td>
                                                        <td>$expira</td>
                                                        ";
                                                        switch ($suspenso) {
                                                          case 'Suspenso':
                                                            echo "<td class='text-danger'>Suspenso</td>";
                                                            break;
                                                          case 'Limite Ultrapassado':
                                                            echo "<td class='text-danger'>Limite Ultrapassado</td>";
                                                            break;
                                                          default:
                                                            if ($status == 'Online') {
                                                              echo "<td class='text-success'>Online</td>";
                                                            } else {
                                                              echo "<td class='text-alert'>Offline</td>";
                                                            }
                                                            break;
                                                        }
                                                        echo "<td>$notas</td>";

                                                        echo '
                                                        <td><div class="btn-group mb-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                AÃ§Ãµes
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="editarlogin.php?id='.$id.'">Editar</a>
                                                                <a class="dropdown-item" onclick="limpardeviceids('.$id.')">Limpar Device ID</a>
                                                                <a class="dropdown-item" onclick="renovardias('.$id.')">Renovar Dias</a>
                                                                <a class="dropdown-item" onclick="reativar('.$id.')">Reativar</a>
                                                                <a class="dropdown-item" onclick="suspender('.$id.')">Suspender</a>
                                                                <a class="dropdown-item" onclick="excluir('.$id.')">Excluir</a>
                                                            </div>
                                                        </div>
                                                        
                                                          </td>
                                                          
                                                          
                                                          </tr>';
                                                        }
                                                        
                                                      }
                                                ?>
                                                      <!-- <div class="whatsapp-fields">
    <textarea class="message-input" rows="5" placeholder="Digite sua mensagem"></textarea>
    <button class="send-button">Enviar</button>
  </div> -->
                                                <script>
                                                  document.addEventListener("DOMContentLoaded", function() {
  var whatsappButton = document.querySelector(".btn-whatsapp");
  var messageInput = document.querySelector(".whatsapp-fields .message-input");
  var sendButton = document.querySelector(".whatsapp-fields .send-button");

  whatsappButton.addEventListener("mouseover", function() {
    messageInput.style.display = "inline-block";
    sendButton.style.display = "inline-block";
  });

  whatsappButton.addEventListener("mouseout", function() {
    messageInput.style.display = "none";
    sendButton.style.display = "none";
  });
});
                                                </script>
                                                <script>
    function limpardeviceids($id) {
    /* confirma */
    swal({
    title: "Tem certeza?",
    text: "VocÃª deseja limpar o Device ID do usuÃ¡rio?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
  .then((willDelete) => {
    if (willDelete) {
      /* faz uma requisiÃ§ao com ajax */
        $.ajax({
            url: 'deviceid.php?id='+$id,
            type: 'GET',
            success: function(data){
              data = data.replace(/(\r\n|\n|\r)/gm, "");

               if (data == 'deletado com sucesso') {
                swal("Sucesso!", "Device ID limpo com sucesso!", "success");
               }else{
                swal("Erro!", "Erro ao limpar Device ID!", "error");
               }
            }
        });
    } else {
      swal("Cancelado!");
    }
  });
  }
    function renovardias($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "VocÃª deseja renovar os dias do usuÃ¡rio?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
          $.ajax({
    url: 'renovardias.php?id='+$id,
    type: 'GET',
    dataType: 'json',
    success: function(data){
        if (data.sucesso == true) {
            /* ao clicar atualiza pagina */
            swal("Sucesso!", data.mensagem, "success").then(function() {
                location.reload();
            });
        } else {
            swal("Erro!", data.mensagem, "error").then(function() {
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
    function reativar($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "VocÃª deseja reativar o usuÃ¡rio?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'reativar.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'reativado com sucesso') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "UsuÃ¡rio reativado com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao reativar usuÃ¡rio!", "error");
                    }
                }
            });
        } else {
            swal("Cancelado!");
        }
        });
    }
    function suspender($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "VocÃª deseja suspender o usuÃ¡rio?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'suspender.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'erro no servidor') {
                        /* ao clicar atualiza pagina */
                        swal("Erro!", "Erro no servidor, verifique se o servidor estÃ¡ online ou se a senha estÃ¡ correta!", "error");
                    }else{

                    if (data == 'suspenso com sucesso') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "UsuÃ¡rio suspenso com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao suspender usuÃ¡rio!", "error");
                    }
                }
                }
            });
        } else {
            swal("Cancelado!");
        }
    });
}
function excluir($id) {
        /* confirma */
        swal({
        title: "Tem certeza?",
        text: "VocÃª deseja excluir o usuÃ¡rio?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'excluiruser.php?id='+$id,
                type: 'GET',
                success: function(data){
                    if (data == 'excluido') {
                        /* ao clicar atualiza pagina */
                        swal("Sucesso!", "UsuÃ¡rio excluido com sucesso!", "success").then(function() {
                            location.reload();
                        });
                    }else{
                        swal("Erro!", "Erro ao excluir usuÃ¡rio!", "error");
                    }
                }
            });
        } else {
            swal("Cancelado!");
        }
        });
    }
  </script>
  </tbody>
    </table>
    </div>
    </div>
    </div>
    

                                        
                    
                          
                                        
                        
    <!-- END: Content-->
    <script src="cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="../app-assets/js/scripts/datatables/datatable.js"></script>
    <script>
    $('#myTable').DataTable({

        /* traduzir somente */
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por pÃ¡gina",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando pÃ¡gina _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponÃ­vel",
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

<script src="../app-assets/sweetalert.min.js"></script>
<!-- ajax -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
