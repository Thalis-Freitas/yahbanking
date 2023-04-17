#!/bin/bash
echo "1/5 Gerando a key do projeto..."
./vendor/bin/sail artisan key:generate

echo "2/5 Executando as migrations..."
./vendor/bin/sail artisan migrate

echo "3/5 Criando link simbólico para que os arquivos armazenados possam ser acessados publicamente..."
./vendor/bin/sail artisan storage:link

echo "4/5 Populando o banco de dados..."
./vendor/bin/sail artisan db:seed

echo "4/5 Importando dados da API de clientes para o banco..."
./vendor/bin/sail artisan import:data

echo "Concluído!"
