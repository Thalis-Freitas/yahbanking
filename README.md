# YahBanking Mariner4

## Sumário
  * [Descrição do projeto](#descrição-do-projeto)
  * [Funcionalidades](#funcionalidades)
  * [Como rodar a aplicação](#como-rodar-a-aplicação)

## Descrição do projeto

<h4 align="justify"> Projeto de gerenciamento de clientes e investimentos para o YahBanking Mariner4, desenvolvido para processo seletivo da Yahp </h4>
<p align="justify">Este projeto tem como objetivo criar um sistema que permita ao gerente de contas do YahBanking Mariner4 gerenciar o cadastro de seus clientes, bem como os tipos de investimentos disponíveis. Além disso, permitir a gestão das contas de seus clientes, com ações como investir e resgatar valores investidos, e gerenciar investimentos, com possibilidade de edição, visualização e encerramento dos mesmos.</p>

## Funcionalidades

Em desenvolvimento :warning:

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
docker run --rm -it \
  -v $PWD:/app \
  -u $(id -u):$(id -g) \
  composer install
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

Gere a key do projeto Laravel:

```
sail artisan key:generate
```

Execute as migrations:

```
sail artisan migrate
```

* Acesse http://localhost
