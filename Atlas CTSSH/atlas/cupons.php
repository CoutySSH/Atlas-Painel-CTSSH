<script src="../app-assets/sweetalert.min.js"></script>
<?php error_reporting(0);
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include('conexao.php');
include('header2.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$sql = "SELECT * FROM cupons WHERE byid = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $_SESSION['iduser']);
          $stmt->execute();
          $result = $stmt->get_result();
          
          
?>
<?php
function gerarCupom($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}
$cupon = gerarCupom(8, true, true, false);
?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<body class="vertical-layout vertical-menu-modern dark-layout 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="dark-layout">
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <section id="basic-datatable">
            <div class="row">
             
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <script>


if (window.innerWidth < 678) {

    document.write('<div class="alert alert-warning" role="alert"> <strong>Atenção!</strong> Mova para lado para ver mais detalhes! </div>');
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
                  

                    <h4 class="card-title">Cupom Desconto</h4><!-- botao adicionar servidor -->
                    <form action="cupons.php" method="post">
                    <input type="text" name="nome" placeholder="Nome Cupom" class="form-control" style="width: 200px; display: inline-block;" required>
                    <input type="text" name="cupom" placeholder="Cupom" class="form-control" style="width: 200px; display: inline-block;" value="<?php echo $cupon ?>">
                    <input type="number" name="vezesuso" placeholder="Quantas vezes pode usar" class="form-control" style="width: 200px; display: inline-block;" value="">

                    <select name="desconto" class="form-control" style="width: 200px; display: inline-block; color: azure;">
                    <option value="1">1%</option>
                    <option value="2">2%</option>
                    <option value="3">3%</option>
                    <option value="4">4%</option>
                    <option value="5">5%</option>
                    <option value="6">6%</option>
                    <option value="7">7%</option>
                    <option value="8">8%</option>
                    <option value="9">9%</option>
                    <option value="10">10%</option>
                    <option value="11">11%</option>
                    <option value="12">12%</option>
                    <option value="13">13%</option>
                    <option value="14">14%</option>
                    <option value="15">15%</option>
                    <option value="16">16%</option>
                    <option value="17">17%</option>
                    <option value="18">18%</option>
                    <option value="19">19%</option>
                    <option value="20">20%</option>
                    <option value="21">21%</option>
                    <option value="22">22%</option>
                    <option value="23">23%</option>
                    <option value="24">24%</option>
                    <option value="25">25%</option>
                    <option value="26">26%</option>
                    <option value="27">27%</option>
                    <option value="28">28%</option>
                    <option value="29">29%</option>
                    <option value="30">30%</option>
                    <option value="31">31%</option>
                    <option value="32">32%</option>
                    <option value="33">33%</option>
                    <option value="34">34%</option>
                    <option value="35">35%</option>
                    <option value="36">36%</option>
                    <option value="37">37%</option>
                    <option value="38">38%</option>
                    <option value="39">39%</option>
                    <option value="40">40%</option>
                    <option value="41">41%</option>
                    <option value="42">42%</option>
                    <option value="43">43%</option>
                    <option value="44">44%</option>
                    <option value="45">45%</option>
                    <option value="46">46%</option>
                    <option value="47">47%</option>
                    <option value="48">48%</option>
                    <option value="49">49%</option>
                    <option value="50">50%</option>
                    <option value="51">51%</option>
                    <option value="52">52%</option>
                    <option value="53">53%</option>
                    <option value="54">54%</option>
                    <option value="55">55%</option>
                    <option value="56">56%</option>
                    <option value="57">57%</option>
                    <option value="58">58%</option>
                    <option value="59">59%</option>
                    <option value="60">60%</option>
                    <option value="61">61%</option>
                    <option value="62">62%</option>
                    <option value="63">63%</option>
                    <option value="64">64%</option>
                    <option value="65">65%</option>
                    <option value="66">66%</option>
                    <option value="67">67%</option>
                    <option value="68">68%</option>
                    <option value="69">69%</option>
                    <option value="70">70%</option>
                    <option value="71">71%</option>
                    <option value="72">72%</option>
                    <option value="73">73%</option>
                    <option value="74">74%</option>
                    <option value="75">75%</option>
                    <option value="76">76%</option>
                    <option value="77">77%</option>
                    <option value="78">78%</option>
                    <option value="79">79%</option>
                    <option value="80">80%</option>
                    <option value="81">81%</option>
                    <option value="82">82%</option>
                    <option value="83">83%</option>
                    <option value="84">84%</option>
                    <option value="85">85%</option>
                    <option value="86">86%</option>
                    <option value="87">87%</option>
                    <option value="88">88%</option>
                    <option value="89">89%</option>
                    <option value="90">90%</option>
                    <option value="91">91%</option>
                    <option value="92">92%</option>
                    <option value="93">93%</option>
                    <option value="94">94%</option>
                    <option value="95">95%</option>
                    <option value="96">96%</option>
                    <option value="97">97%</option>
                    <option value="98">98%</option>
                    <option value="99">99%</option>
                    <option value="100">100%</option>
                </select>
                    <input type="submit" name="adicionarcupom" value="Adicionar" class="btn btn-primary btn-md" style="display: inline-block;">
                    </form>
                    <?php
if (isset($_POST['adicionarcupom'])) {
    $nome = $_POST['nome'];
    $cupom = $_POST['cupom'];
    $desconto = $_POST['desconto'];
    $vezesuso = $_POST['vezesuso'];

    $sql = "INSERT INTO cupons (nome, cupom, desconto, byid, usado, vezesuso) VALUES (?, ?, ?, ?, '0', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $nome, $cupom, $desconto, $_SESSION['iduser'], $vezesuso);
    if ($stmt->execute()) {
        echo "<script>swal('Sucesso!', 'Cupom Adicionado!', 'success').then((value) => {
                window.location.href = 'cupons.php';
              });</script>";
    } else {
        echo "<script>swal('Erro!', 'Cupom Não Adicionado!', 'error').then((value) => {
                window.location.href = 'cupons.php';
              });</script>";
    }
}
?>
                    <!-- <p class="card-description"> Add class <code>Usuarios</code>
                    </p> -->
                    <div class="card-content">
                            <div class="card-body card-dashboard">
                                <!-- nao mostar o sroll -->
                                <div class="table-responsive" style=" overflow: auto; overflow-y: hidden;">
                                    <table class="table zero-configuration" id="myTable">
                                                <thead>
                                                    <tr>
                                                    <th> Nome </th>
                                                    <th> Codigo Cupom </th>
                                                    <th> Desconto </th>
                                                    <th> Usado </th>
                                                    <th> Limite </th>
                                                    <th> Ações </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                          while ($user_data = mysqli_fetch_assoc($result)) {
                              // verifica se servidor esta online
                              if ($user_data['usado'] == '') {
                                  $user_data['usado'] = '0 ';
                              }
                              echo "<td>" . $user_data['nome'] . "</td>";
                              echo "<td>" . $user_data['cupom'] . "</td>";
                              echo "<td>" . $user_data['desconto'] . "%</td>";
                                echo "<td>" . $user_data['usado'] . " Vezes</td>";
                                echo "<td> Maximo " . $user_data['vezesuso'] . " Vezes</td>";
                              echo "<td><form action='cupons.php' method='post'><input type='hidden' name='id' value='" . $user_data['id'] . "'><input type='submit' name='deletar' value='Deletar' class='btn btn-outline-danger btn-fw'></form></td>";
                                echo "</tr>";
                          }
                          

                            if (isset($_POST['deletar'])) {
                                $id = $_POST['id'];
                                $stmt = $conn->prepare("DELETE FROM cupons WHERE id = ?");
                                $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
                                    echo "<script>swal('Sucesso!', 'Cupom Deletado!', 'success').then((value) => {
                                            window.location.href = 'cupons.php';
                                          });</script>";
                                } else {
                                    echo "<script>swal('Erro!', 'Cupom Não Deletado!', 'error').then((value) => {
                                            window.location.href = 'cupons.php';
                                          });</script>";
                                }
                            }
                        
                          
                        
                          ?>
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
<script src="../app-assets/sweetalert.min.js"></script>
<!-- ajax -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

                       