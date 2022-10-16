# La Cripta

### User authentication and password storing project following Domain Driven Design principles

![docker](https://img.shields.io/badge/Docker-compose-brightgreen.svg)
![php](https://img.shields.io/badge/PHP_FPM-8.1.10-brightgreen.svg)
![server](https://img.shields.io/badge/server-roadrunner-blue)

Requests are handled using Symfony framework through endpoints declared in
`/app/config/routes.yaml` and controllers of `/app/src/Controller`.

Domain and context code is stored in `/app/src/Domains/Vault` folder.

### How to use
Run the following commands:
- `docker compose build`
- `docker compose run app composer install`
- `docker compose up`
- `docker compose run app php bin/console doctrine:migrations:migrate`

### How to test (Docker)
Run the following command:
- `docker compose run app php bin/phpunit --textdox`
