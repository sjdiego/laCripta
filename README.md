# La Cripta

### User authentication and password storing project using Domain Driven Design

![docker](https://img.shields.io/badge/Docker-compose-brightgreen.svg)
![php](https://img.shields.io/badge/PHP_FPM-8.1.10-brightgreen.svg)

Requests are handled through Symfony with endpoints declared in
`/app/config/routes.yaml` and controllers of `/app/src/Controller`.

Domain and context code is stored in `/app/src/Vault` folder.

### How to use
Run the following commands:
- `docker compose build`
- `docker compose run app composer install`
- `docker compose up`
- `docker compose run app php bin/console doctrine:migrations:migrate`

### How to test (Docker)
Run the following command:
- `docker compose run app php bin/phpunit --textdox`
