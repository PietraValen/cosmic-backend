<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Cosmic Backend - NASA

Este é o backend do projeto Cosmic Backend - NASA, desenvolvido para gerenciar dados relacionados a detectores de ondas gravitacionais, glitches, eventos de ondas gravitacionais, observatórios e descobertas científicas.

### Tecnologias Utilizadas

- **Laravel**: Framework PHP para desenvolvimento web.
- **JWT (JSON Web Token)**: Para autenticação e autorização.
- **MySQL**: Banco de dados relacional.
- **Postman**: Para testes e documentação de rotas da API.

### Funcionalidades

- **Autenticação**: Login, logout, refresh de tokens e recuperação de informações do usuário autenticado.
- **Gestão de Detectores**: CRUD para detectores de ondas gravitacionais.
- **Gestão de Glitches**: CRUD para glitches detectados.
- **Gestão de Tipos de Glitches**: CRUD para classificação de glitches.
- **Gestão de Eventos de Ondas Gravitacionais**: CRUD para eventos detectados.
- **Gestão de Observatórios**: CRUD para observatórios astronômicos.
- **Estatísticas de Projetos**: CRUD para estatísticas relacionadas ao projeto.
- **Descobertas Científicas**: CRUD para descobertas científicas registradas.

### Estrutura do Projeto

- **Rotas**: Definidas no arquivo `routes/api.php`.
- **Controladores**: Localizados em `App\Http\Controllers\Api`.
- **Middleware**: Implementação de autenticação JWT em `App\Http\Middleware\JwtMiddleware`.

### Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/PietraValen/cosmic-backend.git
   ```

2. Instale as dependências:
   ```bash
   composer install
   ```

3. Configure o arquivo `.env`:
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure as variáveis de ambiente, como conexão com o banco de dados e chave JWT.

4. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

5. Execute as migrações:
   ```bash
   php artisan migrate
   ```

6. Inicie o servidor:
   ```bash
   php artisan serve
   ```

### Testes

- Utilize o arquivo `postman_collection.json` para importar as rotas no Postman e testar a API.

### Licença

Este projeto está licenciado sob a [MIT License](https://opensource.org/licenses/MIT).
