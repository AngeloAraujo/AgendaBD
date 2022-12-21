<?php
include_once "config.php";
// pega variáveis enviadas via GET - são enviadas para edição de um registro
$acao = isset($_GET['acao']) ? $_GET['acao'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
// verifica se está editando um registro
if ($acao == 'editar') {
    // buscar dados do usuário que estamos editando
    try {
        // cria a conexão com o banco de dados 
        $conexao = new PDO(MYSQL_DSN, DB_USER, DB_PASSWORD);
        // montar consulta
        $query = 'SELECT * FROM agenda.usuario WHERE id = :id';
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consult
        $stmt->bindValue(':id', $id);
        // executa a consulta
        $stmt->execute();
        // pega o resultado da consulta - nesse caso a consulta retorna somente um registro pq estamos buscando pelo ID que é único 
        // por isso basta um fetch
        $usuario = $stmt->fetch();
    } catch (PDOException $e) { // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>" . $e->getMessage());
        die();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Contatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script>
        function preencher() {


            let userInput = document.getElementById("dtnasc").value;
            let data = new Date(userInput);
            let dataatual = new Date();
            var currentY = dataatual.getFullYear();

            var prevY = data.getFullYear();

            var ageY = currentY - prevY;

            console.log(ageY);

            document.getElementById("idade").value = ageY;
        }

        function excluir(url) {
            if (confirm("Confirma a exclusão?"))
                window.location.href = url; //redireciona para o arquivo que irá efetuar a exclusão
        }
        window.onload = (function() {

            document.getElementById('fpesquisa').addEventListener('submit', function(ev) {
                ev.preventDefault()
                carregaDados();
            });
        });

        function carregaDados() {
            busca = document.getElementById('busca').value;
            const xhttp = new XMLHttpRequest(); // cria o objeto que fará a conexão assíncrona
            xhttp.onload = function() { // executa essa função quando receber resposta do servidor
                dados = JSON.parse(this.responseText); // os dados são convertidos para objeto javascript
                montaTabela(dados); // chama função que montará a tabela na interface
            }
            // configuração dos parâmetros da conexão assíncrona
            xhttp.open("GET", "pesquisa.php?busca=", true); // arquivo que será acessado no servidor remoto  
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // cabeçalhos - necessário para requisição POST
            xhttp.send("busca=" + busca); // parâmetros para a requisição
        }
    </script>
</head>

<body>

   
    <form action="acaobd.php" method="post" enctype="multipart/form-data" name="myForm">
        <fieldset>
            <div id="telaproduto">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Novo Cadastro</h5>
                        </div><br>
                        <div class="modal-body">
                            <div class="form-group">Id:
                                <input type="text" class='form-control' style='width:50px' readonly name="id" id="id" value=<?php if (isset($usuario)) echo $usuario['id'];
                                                                                                                            else echo 0; ?>>
                            </div>
                            <div class="form-group">Nome
                                <input class="form-control" type="text" id="nome" name="nome" placeholder="Digite seu nome" value=<?php if (isset($usuario)) echo $usuario['nome'] ?>>
                            </div><br>
                            <div class="form-group">Sobrenome:
                                <input class="form-control" type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome" value=<?php if (isset($usuario)) echo $usuario['sobrenome'] ?>>
                            </div><br>
                            <div class="form-group">E-mail:
                                <input class="form-control" type="email" id="email" name="email" placeholder="Digite seu e-mail" value=<?php if (isset($usuario)) echo $usuario['email'] ?>>
                            </div><br>
                            <div class="form-group">Senha:
                                <input class="form-control" type="password" id="senha" name="senha" placeholder="Digite uma senha" value=<?php if (isset($usuario)) echo $usuario['senha'] ?>>
                            </div><br>
                            <div class="form-group">Data de Nascimento:
                                <input class="form-control" type="date" id="dtnasc" name="dtnasc" onchange=preencher() value=<?php isset($_GET['dtnasc']) ? $_GET['dtnasc'] : '' ?>>
                            </div><br>
                            <div class="form-group">Idade:
                                <input class="form-control" type="text" id="idade" name="idade" value=<?= isset($_GET['idade']) ? $_GET['idade'] : '' ?>>
                            </div><br>
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Endereço</label>
                                </div>
                                <div class="form-group">
                                    <select class="form-select" aria-label=".form-select-sm example" id="endereco" name="endereco">
                                        <option selected>Escolher</option>
                                        <option value="Avenida" <?php if (isset($_GET['endereco']) and $_GET['endereco'] == 'Avenida') echo 'selected'; ?>>Avenida</option>
                                        <option value="Rua" <?php if (isset($_GET['endereco']) and $_GET['endereco'] == 'Rua') echo 'selected'; ?>>Rua</option>
                                        <option value="Estrada" <?php if (isset($_GET['endereco']) and $_GET['endereco'] == 'Estrada') echo 'selected'; ?>>Estrada</option>
                                        <option value="Outros" <?php if (isset($_GET['endereco']) and $_GET['endereco'] == 'Outros') echo 'selected'; ?>>Outros</option>
                                    </select>
                                </div><br>
                                <input class="form-control" type="text" id="enderecorua" name="enderecorua" value=<?php if (isset($usuario)) echo $usuario['endereco'] ?>>

                            </div>
                            <div class="form-group">Cidade:
                                <input class="form-control" type="text" id="cidade" name="cidade" value=<?php if (isset($usuario)) echo $usuario['cidade'] ?>>
                            </div>

                            <div class="form-group">Telefone para Contato:
                                <input class="form-control" type="text" id="telefone" name="telefone" value=<?php if (isset($usuario)) echo $usuario['telefone'] ?>>
                            </div><br>
                            <div class="form-group">Passatempo:
                                <input class="form-control" type="text" id="passatempo" name="passatempo" placeholder="Cite seus hobbies" value=<?php if (isset($usuario)) echo $usuario['passatempo'] ?>>
                            </div><br>

                            <div>
                                <input class="btn btn-outline-secondary" type="submit" value="Salvar" name="acao">
                                <input class="btn btn-outline-secondary" type="reset" value="Limpar">
                                <a href="index.html"><button class="btn btn-outline-primary">Voltar ao Menu
                                        Inicial</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
            </div>
        </fieldset>
    </form>
</body>

</html>