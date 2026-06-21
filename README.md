# Sistema de Gerenciamento de Prontuários — Espaço Morada Psicologia

Este sistema foi desenvolvido como Trabalho de Conclusão de Curso (TCC) para o curso de **Tecnologia em Sistemas para Internet da UTFPR**. 

A aplicação foi projetada para atender de forma personalizada as necessidades de gerenciamento de pacientes e registros clínicos do **Espaço Morada Psicologia**. O sistema opera de forma estritamente local (*on-premise*), uma decisão arquitetural estratégica que garante o isolamento de rede e a total confidencialidade dos dados dos pacientes.

---
**Apresentação do Sistema disponivel pelo link: https://youtu.be/Jnw0663SA9A**

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP / Framework Laravel
* **Frontend:** Blade Template Engine, HTML5, CSS3 e JavaScript
* **Banco de Dados:** MySQL
* **Ambiente de Desenvolvimento:** Visual Studio Code / Sistema Operacional Linux

---

## 💻 Ambiente de Desenvolvimento (Linux)

Como o projeto foi integralmente construído e testado sob o sistema operacional Linux, as instruções para rodar a aplicação localmente nesta plataforma são:

### Pré-requisitos
* PHP (versão compatível com o Laravel utilizado)
* Composer
* MySQL Server

**Instalar as dependências do PHP:**

composer install

**Configurar o Ambiente:**

cp .env.example .env

**Abra o arquivo .env recém-criado e configure o bloco de banco de dados (DB_):**

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=espaco_morada
    DB_USERNAME=espaco
    DB_PASSWORD=1234

**Gerar a Chave de Segurança da Aplicação**

php artisan key:generate

**Criar o Banco de Dados no MySQL:**

CREATE DATABASE espaco_morada;


**Construir as Tabelas e Alimentar os Dados (Migrations e Seeds)**

php artisan migrate --seed

**Inicializar o Servidor Local**

php artisan serve

**Pronto! O terminal informará o endereço local.**

## 🔑 Credenciais para Acesso (Ambiente de Teste/Homologação)

Para realizar o login na aplicação logo após a primeira execução, utilize as seguintes credenciais padrão criadas automaticamente pelo sistema de seeds:

    E-mail de Acesso: admin@morada.com

    Senha: morada1234
