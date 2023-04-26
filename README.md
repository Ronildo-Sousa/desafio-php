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
- A sistema acessa a API do Open Food e recebe uma lista de produtos que deve importar.
- A partir daí este processamento é enviado para uma fila, onde os produtos são enviados para a base de dados, como este processamento é assincrono o usuário não tem que esperar acabar e pode continuar usando os sistema.
- Como solicitado no desafio o sistema é executado uma vez ao dia em um horário definido pelo usuário através da variável de ambiente `CRON_RUN_AT`.
- Também é possível executar o cron através do terminal utilizando o comando `sail artisan cron:work`


## Instalação e configuração da API

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

## Endpoints

Acesse a [documentação](https://documenter.getpostman.com/view/15881488/2s8ZDYX1ok) para mais detalhes.

Método   | URI
--------- | ------
GET | /api/products
GET | /api/products/{code}
PUT | /api/products/{code}
DELETE | /api/products/{code}
