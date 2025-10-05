## Cosmic Backend - NASA

Este é o backend do projeto Cosmic Backend - NASA, desenvolvido para gerenciar dados relacionados a detectores de ondas gravitacionais, glitches, eventos de ondas gravitacionais, observatórios e descobertas científicas.

### Tecnologias Utilizadas

- **Laravel**: Framework PHP para desenvolvimento web.
- **JWT (JSON Web Token)**: Para autenticação e autorização.
- **MySQL**: Banco de dados relacional.
- **Postman**: Para testes e documentação de rotas da API.
- **DeepSeek API**: Para obtenção de dados públicos relacionados a glitches e eventos gravitacionais.
- **GWOSC API**: Para acesso a dados de ondas gravitacionais fornecidos pelo Gravitational Wave Open Science Center.

### Funcionalidades

- **Autenticação**: Login, logout, refresh de tokens e recuperação de informações do usuário autenticado.
- **Gestão de Detectores**: CRUD para detectores de ondas gravitacionais.
- **Gestão de Glitches**: CRUD para glitches detectados.
- **Gestão de Tipos de Glitches**: CRUD para classificação de glitches.
- **Gestão de Eventos de Ondas Gravitacionais**: CRUD para eventos detectados.
- **Gestão de Observatórios**: CRUD para observatórios astronômicos.
- **Estatísticas de Projetos**: CRUD para estatísticas relacionadas ao projeto.
- **Descobertas Científicas**: CRUD para descobertas científicas registradas.
- **Gestão de Usuários**: CRUD para gerenciamento de usuários do sistema.
- **Gestão de Tipos de Eventos**: CRUD para gerenciamento de tipos de eventos de ondas gravitacionais.

### Estrutura do Projeto

- **Rotas**: Definidas no arquivo `routes/api.php`.
- **Controladores**: Localizados em `App\Http\Controllers\Api`.
- **Middleware**: Implementação de autenticação JWT em `App\Http\Middleware\JwtMiddleware`.

### Integração com APIs Públicas

- **DeepSeek API**: Utilizada para obter informações detalhadas sobre glitches detectados em diferentes detectores.
- **GWOSC API**: Utilizada para acessar dados de ondas gravitacionais, como eventos detectados e suas características.

### Schedules e Cálculos

Os schedules configurados no Laravel são responsáveis por:

1. **Obtenção de Dados**: Realizar chamadas periódicas às APIs do DeepSeek e GWOSC para coletar informações atualizadas.
2. **Análise Inicial**: Processar os dados obtidos e, através de cálculos específicos, determinar se os eventos detectados têm características de glitches gravitacionais.
3. **Notificação para Análise**: Informar os analistas sobre os eventos classificados como potenciais glitches gravitacionais para revisão detalhada.

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
   - Configure as variáveis de ambiente, como conexão com o banco de dados, chave JWT e credenciais para as APIs do DeepSeek e GWOSC.

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

### Configuração do Cron para Schedules

Para que os comandos agendados no Laravel funcionem corretamente, é necessário configurar o cron no servidor. Adicione a seguinte entrada ao crontab do sistema:

```bash
* * * * * php /caminho/para/sua/aplicacao/artisan schedule:run >> /dev/null 2>&1
```

Substitua `/caminho/para/sua/aplicacao` pelo caminho completo do diretório onde o projeto está localizado.

### Testes

- Utilize o arquivo `postman_collection.json` para importar as rotas no Postman e testar a API.

### Licença

Este projeto está licenciado sob a [MIT License](https://opensource.org/licenses/MIT).
