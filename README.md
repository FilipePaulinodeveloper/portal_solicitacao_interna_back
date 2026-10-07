# API - Portal de Solicitações Internas

API REST desenvolvida para gerenciar solicitações internas de colaboradores. Inclui autenticação por token, cadastro e consulta de solicitações, filtros, dashboard e regras de negócio para edição e exclusão.

## Tecnologias

- **Linguagem e framework:** PHP 8.2+ e Laravel 12
- **Banco de dados:** MySQL
- **Autenticação:** Laravel Sanctum com tokens Bearer
- **Validação:** Laravel Form Requests e validação de enums
- **Testes:** Pest

## Pré-requisitos

- PHP 8.2 ou superior, com as extensões necessárias ao Laravel e ao driver MySQL
- Composer
- MySQL 8 ou compatível
- Git

## Instalação do PHP
O projeto requer PHP 8.2 ou superior dentro da versão 8.2.x.
1. Acesse a página oficial de downloads do PHP para Windows:
https://www.php.net/downloads.php?os=windows&osvariant=windows-downloads&version=8.2


2. Após extrair o PHP, configure o diretório do PHP na variável de ambiente PATH do Windows:
```bash
Pesquise por "Variáveis de Ambiente" no menu Iniciar.

Acesse "Editar as variáveis de ambiente do sistema".

Clique em "Variáveis de Ambiente...".

Na seção "Variáveis do sistema", selecione Path e clique em "Editar".

Clique em "Novo" e informe o caminho da pasta onde o PHP foi extraído.

Confirme todas as alterações.
 ```

3. Verifique a instalação:
 ```bash
php -v
```

4. Extensões do PHP

Certifique-se de que as seguintes extensões estejam habilitadas no arquivo php.ini:
```bash
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=mysqli
```
## Instalação do Composer
O projeto utiliza o Composer 2.10.1.

1. Baixe e instale o Composer pelo site oficial:
https://getcomposer.org/download/

2. Após a instalação, execute:

 ```bash
  composer self-update 2.10.1
   ```


3. Verifique a versão deve retprmar a versão 2.10.1:
 ```bash
composer --version
  ```
  
## Instalação e configuração

1. Clone o repositório e acesse a pasta do backend:

   ```bash
   git clone <url-do-repositorio>
   cd portal_solicitacao_interna_back
   ```

2. Instale as dependências PHP:

   ```bash
   composer install
   ```

3. Crie o arquivo de ambiente a partir do modelo:

   ```bash
   # Linux/macOS
   cp .env.example .env

   # Windows PowerShell
   Copy-Item .env.example .env
   ```

4. Configure no `.env` as credenciais de uma base MySQL existente:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_solicitacao_db
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

   Para integrar com o frontend local, ajuste também a origem permitida, se necessário:

   ```dotenv
   ALLOWED_ORIGINS=http://localhost:5173
   ```

5. Gere a chave da aplicação:

   ```bash
   php artisan key:generate
   ```

6. Crie as tabelas e os usuários de teste:

   ```bash
   php artisan migrate --seed
   ```

## Execução

Inicie o servidor local da API:

```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`; os endpoints ficam sob o prefixo `/api`.

## Credenciais de desenvolvimento

O seeder cria estas três contas para testes. Todas usam a mesma senha:

| E-mail | Senha |
|---|---|
| `usuario1@example.com` | `SenhaDev123!` |
| `usuario2@example.com` | `SenhaDev123!` |
| `usuario3@example.com` | `SenhaDev123!` |

Essas credenciais são públicas e servem apenas para desenvolvimento. Não as utilize em produção.

## Autenticação

Faça login em `POST /api/login` enviando e-mail e senha:

```json
{
  "email": "usuario1@example.com",
  "password": "SenhaDev123!"
}
```

A resposta inclui um token de acesso. Envie-o nas rotas protegidas no cabeçalho:

```http
Authorization: Bearer <token>
Accept: application/json
```

O logout é feito em `POST /api/logout` e revoga o token atual.

## Endpoints

Todas as rotas abaixo, exceto o login, exigem autenticação Sanctum.

| Método | Endpoint | Descrição |
|---|---|---|
| `POST` | `/api/login` | Autentica o usuário e retorna um token. Pública. |
| `POST` | `/api/logout` | Revoga o token atual. |
| `GET` | `/api/dashboard` | Retorna os dados do dashboard. |
| `GET` | `/api/solicitacoes` | Lista solicitações com paginação e filtros. |
| `POST` | `/api/solicitacoes` | Cria uma solicitação para o usuário autenticado. |
| `GET` | `/api/solicitacoes/{id}` | Consulta uma solicitação. |
| `PUT` / `PATCH` | `/api/solicitacoes/{id}` | Atualiza uma solicitação, somente se estiver com status `Aberto`. |
| `PATCH` | `/api/solicitacoes/{id}/status` | Atualiza o status da solicitação. |
| `DELETE` | `/api/solicitacoes/{id}` | Exclui uma solicitação, somente se estiver com status `Aberto`. |
| `GET` | `/api/user/{id}` | Consulta um usuário. |


### Solicitações

Os filtros aceitos por `GET /api/solicitacoes` são `data_inicio`, `data_fim`, `categoria`, `status` e `titulo`. Os resultados são paginados em até 15 itens por página.

Categorias válidas:

- `TI`
- `RH`
- `Compras`
- `Financeiro`
- `Infraestrutura`

Status válidos:

- `Aberto`
- `Em andamento`
- `Concluído`

Exemplo de criação:

```json
{
  "titulo": "Acesso ao sistema",
  "descricao": "Solicito acesso ao sistema de relatórios.",
  "categoria": "TI"
}
```

O usuário da solicitação é associado a partir da identidade autenticada.

Exemplo de atualização de status:

```json
{
  "status": "Em andamento"
}
```

## Testes

Execute a suíte de testes com:

```bash
php artisan test
```

## Estrutura da aplicação

- `app/Http/Controllers`: controllers e tratamento das requisições HTTP
- `app/Http/Requests`: validação das entradas
- `app/Services`: regras de negócio e acesso aos modelos
- `app/Models`: modelos Eloquent
- `database/migrations`: estrutura do banco de dados
- `database/seeders`: dados iniciais e contas de desenvolvimento
- `routes/api.php`: rotas da API
- `tests`: testes automatizados

## Memorial Técnico
https://docs.google.com/document/d/1Z0i9wmPTH_kLqqwDPWvNRRwn1NxBvuGSAkCGBSYu3W0/

