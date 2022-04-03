# La Cripta

### User authentication and password storing project using Domain Driven Design

![docker](https://img.shields.io/badge/Docker-compose-brightgreen.svg)
![nginx](https://img.shields.io/badge/nginx-on_apline-brightgreen.svg)
![php](https://img.shields.io/badge/PHP_FPM-8.1.4-brightgreen.svg)
![xdebug](https://img.shields.io/badge/Xdebug-3-brightgreen.svg)
![mariadb](https://img.shields.io/badge/MariaDB-10.7.3-brightgreen.svg)

Requests are handled through Symfony with endpoints declared in
`/app/config/routes.yaml` and handled via controllers of `/app/src/Controller`.

Domain and context code is stored in `/app/src/Vault` folder.
