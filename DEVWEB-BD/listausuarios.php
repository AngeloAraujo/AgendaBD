<?php
include_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    <section class='row'>
        <!-- esse formulário é para permitir a pesquisa de um usuário cadastrado -->
            <div class='col'>
                <form action="" method="get" id='pesquisa'>
                    <!-- esse formulário submte para essa mesma página para recarregar com o resultado da busca -->
                    <div class='row'>
                        <div class='col-8'>
                            <h2> Lista de Usuários cadastrados</h2>
                        </div>
                        <div class='col'><input class='form-control' type="search" name='busca' id='busca'></div>
                        <div class='col'><button type="submit" class='btn btn-success' name='pesquisa'>Buscar</button></div>
                    </div>
                </form>
                <div class='row'>
                    <!-- aqui montamos a tabela com os dados vindo do banco -->
                    <table class='table table-striped table-hover'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nome</th>
                                <th>Sobrenome</th>
                                <th>Idade</th>
                                <th>Data de Nascimento</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Endereço</th>
                                <th>Endereço Completo</th>
                                <th>Cidade</th>
                                <th>Passatempo</th>
                                <th>Senha</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php
                            try {
                                // cria  a conexão com o banco de dados 
                                $conexao = new PDO(MYSQL_DSN, DB_USER, DB_PASSWORD);
                                // pega o valor informado pelo usuário no formulário de pesquisa
                                $busca = isset($_GET['busca']) ? $_GET['busca'] : "";
                                // monta consulta
                                $query = 'SELECT * FROM usuario';
                                if ($busca != "") { // se o usuário informou uma pesquisa
                                    $busca = '%' . $busca . '%'; // concatena o curiga * na pesquisa
                                    $query .= ' WHERE nome like :busca'; // acrescenta a clausula where
                                }
                                // prepara consulta
                                $stmt = $conexao->prepare($query);
                                // vincular variaveis com a consulta
                                if ($busca != "") // somente se o usuário informou uma busca
                                    $stmt->bindValue(':busca', $busca);
                                // executa a consuta 
                                $stmt->execute();
                                // pega todos os registros retornados pelo banco
                                $usuarios = $stmt->fetchAll();
                                foreach ($usuarios as $usuario) { // percorre o array com todos os usuários imprimindo as linhas da tabela
                                    $editar = '<a href=cadUsuariomodelo.php?acao=editar&id=' . $usuario['id'] . '>Alt</a>';
                                    $excluir = "<a href='#' onclick=excluir('acaomodelo.php?acao=excluir&id={$usuario['id']}')>Excluir</a>";
                                    echo '<tr><td>' . $usuario['id'] . '</td><td>' . $usuario['nome'] . '</td><td>' . $usuario['sobrenome'] . '</td><td>' . $usuario['idade'] . '</td><td>' . $usuario['dtnasc'] . '</td>
                                <td>' . $usuario['email'] . '</td><td>' . $usuario['telefone'] . '</td>
                                <td>' . $usuario['endereco'] . '</td><td>' . $usuario['enderecorua'] . '</td>
                                <td>' . $usuario['cidade'] . '</td><td>' . $usuario['passatempo'] . '</td><td>' . $usuario['senha'] . '</td><td>' . $editar . '</td><td>' . $excluir . '</td></tr>';
                                }
                            } catch (PDOException $e) { // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
                                print("Erro ao conectar com o banco de dados...<br>" . $e->getMessage());
                                die();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        </form>
    </section>
</body>

</html>