
# Projeto Austa - Sistema de Atendimentos

Este projeto é um sistema web para **gestão de atendimentos**, desenvolvido como um MVP funcional com **Laravel 12**, **MySQL** e **Blade + Bootstrap**.

Usuários podem criar, visualizar e gerenciar atendimentos. Administradores possuem acesso completo, podendo editar qualquer atendimento e gerenciar categorias.

## 🔧 Tecnologias Utilizadas

- [Laravel 12](https://laravel.com/)
- PHP 8+
- MySQL
- Bootstrap 5
- Blade (Laravel Templating)
- Font Awesome (Ícones)

## 🚀 Funcionalidades

- Cadastro de usuários e autenticação manual
- Criação de atendimentos com:
  - Título
  - Descrição
  - Prioridade (Alta, Média, Baixa)
  - Status (Aberto, Em andamento, Finalizado)
  - Categoria (relacionamento com tabela `categorias`)
- Visualização de atendimentos
  - Usuário comum vê apenas seus atendimentos
  - Administrador vê todos os atendimentos
- Edição de atendimentos (apenas por administradores)
- CRUD de Categorias (apenas por administradores)
- Controle de acesso com base na `role` do usuário

## 🧑‍💼 Tipos de Usuário

- `admin`: pode ver e editar todos os atendimentos e gerenciar categorias
- `user`: pode criar e visualizar apenas os próprios atendimentos

## 🛠️ Instalação e Execução

1. Clone o repositório:

```bash
git clone https://github.com/cagu1n/Projeto-austa.git
cd Projeto-austa
```

2. Instale as dependências:

```bash
composer install
```

3. Crie o arquivo `.env`:

```bash
cp .env.example .env
```

4. Gere a key do Laravel:

```bash
php artisan key:generate
```

5. Configure seu banco de dados no `.env`:

```dotenv
DB_DATABASE=nome_do_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

6. Execute as migrations:

```bash
php artisan migrate
```

7. (Opcional) Popule com dados iniciais se desejar:

```bash
php artisan db:seed
```

8. Execute o servidor:

```bash
php artisan serve
```

Acesse o sistema em `http://localhost:8000`.

## 📌 Observações

- Certifique-se de que as roles (`admin`, `user`) estejam corretamente definidas na tabela `users`
- O projeto ainda está em desenvolvimento (cerca de 80% concluído)

## 👨‍💻 Autor

Arthur Rezende Sant’ana – [@cagu1n](https://github.com/cagu1n)
