# PHP Challenge 20200916

## O desafio

Basicamente este deafio se baseou em duas tarefas: Desenvolver um sistema de CRON e uma REST API.

### O sistema de CRON

A idéia do sistema de cron era desenvolver uma funcionalidade que importasse para a base de dados a versão mais recente do [Open Food Facts](https://br.openfoodfacts.org/data) uma vez ao dia. Permitindo ao usuário definir o melhor horário para realizar a atualização.

### A REST API

Já a REST API tinha como objetivo desenvolver um CRUD com os produtos importados pelo sistema de cron. os endpoints seriam:

- `GET /products`: Listar os produtos da base de dados de forma paginada.
- `GET /products/:code`: Listar um produto da base de dados.
- `PUT /products/:code`: Atualizar informaçõesde um produto da base de dados.
- `DELETE /products/:code`: Mudar o status de um produto para `trash`.

## A entrega

`Ainda estou tentando fazer algumas melhorias no projeto`

### O sistema de CRON

O sistema de cron desenvolvido funciona da seguinte forma:
- O sistema acessa a API do Open Food e recebe uma lista de produtos que deve importar.
- A partir daí este processamento é enviado para uma fila, onde os produtos são enviados para a base de dados, como este processamento é assincrono o usuário não tem que esperar acabar e pode continuar usando os sistema.
- Quando finalizado o processo os administradores são notificados por email, caso ocorra algum erro eles também são notificados.
- Como solicitado no desafio o sistema é executado uma vez ao dia em um horário definido pelo usuário através da variável de ambiente `CRON_RUN_AT`.
- Também é possível executar o cron através do terminal utilizando o comando `./vendor/bin/sail artisan cron:work`.

### A REST API

`A documentação pode ser acessada` [aqui](https://documenter.getpostman.com/view/15881488/2s8ZDYX1ok)

Atualmente o sistema possui 2 endpoints principais, um endpoint de usuários e outro de produtos, os dois possuem as funcionalidades básicas de um CRUD. Para estes endpoints desenvolvi também testes automatizados utilizando phpunit.

Endpoints relacionados a usuários:
Método   | URI | Descrição
--------- | ------ | ---------
GET | /api/users | retorna todos os usuários da base de dados
GET | /api/users/{user} | retorna um usuário da base de dados  
PUT | /api/users/{user} | atualiza um usuário da base de dados
DELETE | /api/users/{user} | apaga um usuário da base de dados

Endpoints relacionados a produtos:
Método   | URI | Descrição
--------- | ------ | ---------
GET | /api/products | retorna todos os produtos da base de dados
GET | /api/products/{user} | retorna um produto da base de dados  
PUT | /api/products/{user} | atualiza um produto da base de dados
DELETE | /api/products/{user} | apaga um produto da base de dados

## Instalação e configuração

Faça o clone do respositório
```bash
git clone https://github.com/Ronildo-Sousa/php-challenge.git
```

Na pasta do projeto instale as dependências
```docker
docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
```

Crie o arquivo .env
```bash
cp .env.example .env
```

Inicialize o container do Laravel Sail
```bash
./vendor/bin/sail up -d
```

Crie a chave da aplicação
```bash
./vendor/bin/sail artisan key:generate
```

Execute as migrations e seeders
```bash
./vendor/bin/sail artisan migrate --seed
```
