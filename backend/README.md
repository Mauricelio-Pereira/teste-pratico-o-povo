# Control Tac API

Sistema de Gerenciamento para Blog colaborativo (API)

---

## 🛠️ **Tecnologias Utilizadas**

- **Framework:** `Laravel 11x`
- **PHP:** `8.2.12`
- **Banco de Dados:** `MySQL 8.2.12`
- **SGBD:** `phpMyAdmin 5.2.1`

### **Extensões PHP Necessárias**
- `openssl`
- `gd`
- `zip`

---

## 🚀 **Instruções de Instalação**

### 🛠️ **Configuração do Ambiente**

> [!IMPORTANT]
> Consultar a [Documentação do Laravel](https://laravel.com/docs/11.x/installation)

1. **Instale o PHP e Composer**

- Abra o seu terminal e execute o seguinte comando para instalar o PHP e o Composer, necessários para a execução do projeto:

```bash
# Run as administrator...

# Windows
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows'))

# Linux
/bin/bash -c "$(curl -fsSL https://php.new/install/linux)"
```

- Após a finalização da instalação, é recomendado que se reinicie o terminal.


2. **Instale o Laravel Installer**

- Ainda no terminal, execute o seguinte comando para a instalaçao do Laravel Installer:

```bash
composer global require laravel/installer
```

- Após a finalização da instalação, é recomendado que se reinicie o terminal para que as alterações sejam aplicadas corretamente.


3. **Instale um SGBD**

- Para que possamos executar o projeto, precisaremos de um SGBD (Sistema de Gerencimante de Banco de Dados) que suporte o MySQL, o banco de dados que utilizaremos na aplicação. Existem inúmeros SGBD's disponíveis, mas estaremos utilizando o phpMyAdmin por ser fácil e prático de configurar e manusear.
- Caso opte por algum outro SGBD, apenas se atente ao requisito de que ele deve ter suporte para o MySQL, com a versão mínima de 8.2.12 para que possamos realizar todas as operações sem problemas.
- Para realizar a instalação do XAMPP para Windows ou Linux, basta fazer o download do instalador através do site oficial e seguir os passos de instalação:

> https://www.apachefriends.org/pt_br/index.html

- Após a instalação, para acessar o phpMyAdmin, será necessário inicializar os servidores do Apache e do MySQL disponíveis no painel de controle do XAMPP. O phpMyAdmin poderá então ser acessado no host local:

> http://127.0.0.1/phpmyadmin/


4. **Criação da Conta de Acesso do Banco de Dados**

- Para que o projeto em Laravel possa realizar operações no banco de dados, será necessário criar uma conta de usuário para ele.
- Na página inicial do phpMyAdmin, procure pelo menu [Contas de usuário](http://127.0.0.1/phpmyadmin/index.php?route=/server/privileges&viewing_mode=server) e clique no botão [Adicionar conta de usuário](http://127.0.0.1/phpmyadmin/index.php?route=/server/privileges&adduser=1).
- Crie um usuário com as seguintes credenciais:

  - **Nome de usuário:** o-povo-api
  - **Nome de Host:** %
  - **Senha:** o-povo-api
  - **Plugin de autenticação:** Autenticação nativa do MySQL
- Após, conceda os seguintes privilégios para a conta:

  - **Dados:**
    - SELECT;
    - INSERT;
    - UPDATE;
    - DELETE;
    - FILE.
  - **Estrutura:**
    - CREATE;
    - ALTER;
    - INDEX;
    - DROP;
    - CREATE TEMPORARY TABLES;
    - SHOW VIEW;
    - CREATE ROUTINE;
    - ALTER ROUTINE
    - EXECUTE;
    - CREATE VIEW;
    - EVENT;
    - TRIGGER.
  - **Administração:**
    - REFERENCES.
- Por fim, clique no botão **Executar** para criar a conta do usuário para a aplicação.

---

### 🛠️ **Inicialização do Projeto**

1. **Clone o Repositório**

- Navegue até a sua pasta de trabalho no terminal e execute os seguinte comandos para baixar e acessar o projeto:

```bash
git clone https://github.com/Mauricelio-Pereira/teste-pratico-o-povo.git

cd teste-pratico-o-povo/backend
```

2. **Instale as Dependências**

- Execute os seguinte comandos para instalar as dependências do Laravel:

```bash
composer install

npm install
```

3. **Carregar Variáveis de Ambiente**

> [!IMPORTANT]
> Consultar a [Documentação do Laravel](https://laravel.com/docs/11.x/configuration#environment-configuration)

- As variáveis de ambiente necessárias para que o projeto seja executado corretamente são salvas criptografadas em um arquivo chamado **.env.encrypted** (se não encontrar o arquivo, é possível que ele esteja oculto).
- Para descriptografar esse arquivo e gerar o arquivo **.env**, execute o seguinte comando:

```bash
# Substitua chave_segura pela chave de descriptografia
php artisan env:decrypt --key=chave_segura
```

> [!IMPORTANT]
> Para qualquer alteração realizada no arquivo **.env**, será necessário gerar um novo arquivo criptografado.
> Para que isso possa ser realizado com êxito, sera necessário, primeiro, excluir o arquivo **.env.encrypted** atual.
> Você pode gerar o novo arquivo executando o comando:

```bash
# Substitua chave_segura pela chave de descriptografia
php artisan env:encrypt --key=chave_segura
```

4. **Migração das Tabelas**

- Para migrar as tabelas para o banco de dados **control_tac**, execute o seguinte comando:

```bash
php artisan migrate
```

5. **Seeders**

- Para alimentar as tabelas com dados fictícios para testes, execute o seguinte comando:

```bash
php artisan db:seed
```

6. **Inicialização**

- Para inicializar o projeto, execute o seguinte comando:

```bash
php artisan serve
```

- O projeto de API pode então ser acessado no seguinte URL:

> [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)

---

### 🛠️ **Configurar o Agendador de Tarefas**

#### **No Servidor**

1. **Abra o Crontab**
- No terminal do servidor execute seguite comando para abrir o editor do crontab:

```bash
crontab -e
```

2. **Adicione a Entrada para o Laravel Scheduler**

- Adicione a seguinte linha no arquivo do crontab para que o Laravel execute o agendador a cada minuto:

```bash
* * * * * cd /caminho/para/seu/projeto && php artisan schedule:run
```

---

### 🛠️ **Extra**

1. **Limpeza do Cache**

- Caso realize alguma alteração no projeto e não veja efeito, execute o seguinte comando para limpar o cache de todos os serviços:

```bash
php artisan optimize
```

> [!IMPORTANT]
> Consultar a [Documentação do Laravel](https://laravel.com/docs/11.x/deployment#optimization)
