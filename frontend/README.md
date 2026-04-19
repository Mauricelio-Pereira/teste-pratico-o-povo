# Sistema de Gerenciamento para Oficinas de Cronotacógrafos (FRONT)

Este é o frontend do sistema de gerenciamento para oficinas de cronotacógrafos, desenvolvido utilizando as tecnologias mais modernas para garantir eficiência e uma experiência de usuário de alta qualidade.

## 🛠️ Tecnologias Utilizadas

- **Framework**: [Vite](https://vitejs.dev/)
- **Linguagem**: [TypeScript](https://www.typescriptlang.org/)
- **Biblioteca**: [ReactJS](https://reactjs.org/)
- **Estilização**: [Tailwind UI](https://tailwindui.com/), [Flowbite React](https://flowbite-react.com/)
- **Componentes UI**: [Flowbite React](https://flowbite-react.com/), [Shadcn UI](https://ui.shadcn.com/)  
- **Notificações**: [React Toastify](https://fkhadra.github.io/react-toastify/introduction)

---

## 🚀 Requisitos

Certifique-se de ter as versões corretas das ferramentas abaixo instaladas no seu ambiente:

- **Node.js**: `v20.18.0`
- **npm**: `10.8.2`

---

## 🧩 Configuração do Ambiente

### 1. Clone o repositório

- Navegue até a sua pasta de trabalho no terminal e execute os seguinte comandos para baixar e acessar o projeto:

```bash
git clone https://github.com/Mauricelio-Pereira/control-tac.git

cd control-tac/frontend
```

### 2. Configuração do Host

Para configurar o domínio local test.localhost, siga os passos abaixo:

- Execute o comando abaixo e abra o arquivo hosts:

```bash
C:\Windows\System32\drivers\etc\
```

- Adicione a seguinte linha ao final do arquivo:

```bash
127.0.0.1   test.localhost
```

> [!IMPORTANT]
> Você precisará salvar o arquivo como administrador. Para isso:
> Abra o Bloco de Notas como administrador (clique com o botão direito e escolha "Executar como Administrador").
> Edite e salve o arquivo hosts.

### 3. Instalação das dependências

- No diretório do projeto, execute o comando abaixo para instalar todas as dependências:

```bash
npm install
```

### 4. Execute o projeto

- Após a configuração, execute o comando abaixo para iniciar o servidor local:

```bash
npm run dev
```

> O servidor será iniciado e estará disponível no endereço: http://test.localhost:5173.

---

## Como buildar

### 1. Buildar o projeto

- No diretório do projeto, execute o comando abaixo para Buildar

```bash
npm run build
```

### 2. Execute o projeto
- Após buildar, execute o comando abaixo para iniciar o servidor local:

```bash
npm run preview
```