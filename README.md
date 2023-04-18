<div align="center">
<br/>

 ![logo](https://github.com/Thalis-Freitas/yahbanking/blob/26628304520f9dfa474d342a330d00539c600160/public/img/logo.svg)

</div>

## Sumário
  * [Descrição do projeto](#descrição-do-projeto)
  * [Funcionalidades](#funcionalidades)
  * [Como rodar a aplicação](#como-rodar-a-aplicação)
  * [Comando para rodar os testes](#comando-para-rodar-os-testes)
  * [Comando para derrubar a app](#comando-para-derrubar-a-app)

## Descrição do projeto

<h4 align="justify"> Projeto de gerenciamento de clientes e investimentos para o YahBanking Mariner4, desenvolvido para processo seletivo da Yahp </h4>
<p align="justify">Este projeto tem como objetivo criar um sistema que permita ao gerente de contas do YahBanking Mariner4 gerenciar o cadastro de seus clientes, bem como os tipos de investimentos disponíveis. Além disso, permitir a gestão das contas de seus clientes, com ações como investir e resgatar valores investidos, e gerenciar investimentos, com possibilidade de edição, visualização e encerramento dos mesmos.</p>

## Funcionalidades

### Clientes

- [X] Cadastrar novo cliente.
- [X] Atualizar informações do cliente.
- [X] Página de detalhes do cliente.
- [X] Deletar cliente.
- [X] Listagem de clientes.
- [X] Ao cadastrar um cliente, os valores de "valor total", "valor não investido" e "valor investido" são preenchidos automaticamente com 0.
- [X] Possibilidade de depositar valores, o valor depositado é armazenado automaticamente em "valor não investido".
- [X] Valor total é a soma de "valor não investido" com "valor investido".

### Investimentos

- [X] Cadastrar investimento.
- [X] Atualizar informações do investimento.
- [X] Página de detalhes do investimento.
- [X] Deletar investimento.
- [X] Listagem de investimentos.
- [X] Lista de clientes que possuem valores aplicados no investimento.
- [X] Ao encerrar um investimento são devolvidos para todos os clientes seus respectivos valores aplicados.

### API de clientes

- [X] Comando para importar dados da API e armazenar no banco.
- [x] Script com chamada para o comando.

### Investimentos dos clientes

- [X] É possível vincular um cliente a um investimento com o qual ele ainda não possui vínculo na página de detalhes de um cliente.
- [X] Listagem de investimentos de um cliente.
- [X] Aplicar valores em investimentos já realizados.
- [X] Resgatar valores de investimentos já realizados.

## Como rodar a aplicação

No terminal, clone o projeto:

```
git clone git@github.com:Thalis-Freitas/yahbanking.git
```

Entre na pasta do projeto:

```
cd yahbanking
```

Instale as dependências:

```
docker run --rm -it -v $PWD:/app -u $(id -u):$(id -g) composer install
```

Crie o arquivo .env:

```
cp .env.example .env
```

Para facilitar a execução de comandos do Sail, crie um atalho:

```
alias sail="./vendor/bin/sail"
```

Certifique-se de que o Docker esteja em execução em sua máquina e suba os containers:

```
sail up -d
```

OBS: Por favor, por prevenção aguarde 1 minuto para rodar o próximo comando, para que o banco de dados possa estar preparado para as migrations!

Rode o script abaixo, ele é responsável por:
- Gerar a key do projeto
- Executar as migrations
- Criar link simbólico para que os arquivos armazenados possam ser acessados publicamente
- Popular o banco de desenvolvimento com dados fakes
- Importar dados da API de clientes para o banco

```
./setup.sh
```

Acesse o shell do container do projeto:

```
sail shell
```

Instale as dependências de compilação:

```
npm install
```

Compile o projeto:

```
npm run dev
```

* Acesse http://localhost e faça login com o usuário já cadastrado no sistema:

| E-mail | Senha |
| :----- | :----- |
| mariner4_gestao@yahbanking.com | password |

## Comando para rodar os testes:

```
sail artisan test
```

## Comando para derrubar a app:

```
sail down
```
