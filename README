# Catego

Este é um projeto desenvolvido em Laravel, um framework PHP para desenvolvimento web. Abaixo estão as instruções básicas para configurar e executar o projeto localmente.

## Pré-requisitos

- PHP (versão 8.3 ou superior)
- Composer (gerenciador de dependências do PHP)
- Node.js (para rodar o Vite)
- Banco de dados (MySQL)
- Git (opcional, para clonar o repositório)

## Instalação

1. **Clone o repositório** (se estiver usando Git):
   ```bash
   git clone https://github.com/felipemaced0/catego.git
   cd catego
   ```

2. **Instale as dependências do PHP**:
   ```bash
   composer install
   ```

3. **Instale as dependências do Node.js**:
   ```bash
   npm install
   ```

4. **Configure o arquivo `.env`**:
   - Renomeie o arquivo `.env.example` para `.env`.
   - Abra o arquivo `.env` e configure as variáveis de ambiente, como as credenciais do banco de dados:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nome_do_banco
     DB_USERNAME=seu_usuario
     DB_PASSWORD=sua_senha
     ```
   - Gere uma nova chave de aplicação:
     ```bash
     php artisan key:generate
     ```

5. **Execute as migrações**:
   - Para criar as tabelas no banco de dados, execute:
     ```bash
     php artisan migrate
     ```

6. **Popule o banco de dados** (se houver seeders):
   - Para popular o banco de dados com dados iniciais, execute:
     ```bash
     php artisan db:seed
     ```
   - Ou, para rodar migrações e seeders juntos:
     ```bash
     php artisan migrate --seed
     ```

## Executando o Projeto

1. **Inicie o servidor de desenvolvimento**:
   ```bash
   php artisan serve
   ```
   O projeto estará disponível em `http://localhost:8000`.

2. **Rodando o Vite**:
   - Para rodar o Vite no modo de desenvolvimento (com hot-reload):
     ```bash
     npm run dev
     ```
   - Para construir os assets para produção:
     ```bash
     npm run build
     ```

## Comandos Úteis

- **Limpar cache**:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan view:clear
  php artisan route:clear
  ```

- **Rodar testes** (se houver):
  ```bash
  php artisan test
  ```

## Estrutura do Projeto

- **`app/`**: Contém a lógica do aplicativo (models, controllers, etc.).
- **`database/`**: Contém migrações, seeders e factories.
- **`resources/`**: Contém views, assets (CSS, JS) e outros recursos.
- **`routes/`**: Define as rotas da aplicação.
- **`public/`**: Arquivos públicos acessíveis diretamente pelo navegador.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
