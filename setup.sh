#!/bin/bash
echo "1/4 Gerando a key do projeto..."
./vendor/bin/sail artisan key:generate

echo "2/4 Executando as migrations e e populando o banco de dados..."
./vendor/bin/sail artisan migrate --seed

echo "3/4 Criando link simbólico para que os arquivos armazenados possam ser acessados publicamente..."
./vendor/bin/sail artisan storage:link

echo "4/4 Importando dados da API de clientes para o banco..."
./vendor/bin/sail artisan import:data

echo "Concluído!"
