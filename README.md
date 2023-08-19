# Aplicação de administração do bispado

O objetivo dessa aplicacao e de fornecer backend que pode ser consumido por varios clientes com funcoes administrativas do bispado.

Atualmente as funcoes disponiveis sao:

* Atas de reuniao sacramental.

## Funcoes programdas:

* Atas de reuniao de bispado
* Atas de reuniao de conselho
* Lista de faltantes
* Api de notificacao para clientes mobile.

## Arquitetura:

A ideia e que o backend seja uma api rest que pode ser cosumida por qualquer tipo de cliente.

Possui um sistema de autenticacao e no momento serve apenas para uma unidade, entao se alguem quiser usar ele deve ser deployado on demand

## Setup:

* Criar o arquivo .env e preencher os campos necessarios
* Usar a imagem docker com o comando `docker compose up -d`, para usar esse comando é necessario ter o docker e docker compose instalado
