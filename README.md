
Summary
=====
this repo for send email with queue

## **Detailed design**
the design adapted by laravel framework, and using public routing, and implementing usecase domain as main logic and repository pattern as source data, for detailed info you can see 
a diagram below
![untitled (8)](https://github.com/rezairwantoo/queue-mailer/assets/6358186/3651475e-2ead-43de-9be2-bb3ac37cc950)

For the sequence diagram you can see the diagram below
![sequencequeue](https://github.com/rezairwantoo/queue-mailer/assets/6358186/a7319ce5-3a60-4c3e-8a35-b9bb1876345a)


For entry point to run this process, you need to hit the endpoint send the detail will be describe below

| Body | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `from`      | `String` | **Required**. Sender email address |
| `to`      | `String` | **Required**. Receiver email address |
| `cc`      | `String` | Destination email address of cc|
| `bcc`      | `String` | Destination email address of bcc |
| `subject`      | `String` | **Required**. Email subject |
| `body`      | `String` | **Required**. Email subject |

```
**URL** host/send
**Method** POST
**Body** 
{
    "from": "john.doe@example.com",
    "to": "john.doe@grr.com",
    "cc": "",
    "bcc": "",
    "subject": "test",
    "body": "body"
}

or you can hit the endpoint by this
curl --location --request POST 'http://localhost:8000/send' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=82rhsp4nvtcqiei9pl0m8ovlh0' \
--data-raw '{
    "from": "john.doe@example.com",
    "to": "john.doe@grr.com",
    "cc": "",
    "bcc": "",
    "subject": "test",
    "body": "body"
}'
```
### Data Source
for this case we used postgres as a data store, and have a single table is Mails
| Column | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `bigserial` | **PK**. identifier for mail |
| `user_id`      | `int8` | **FK**. identifier for User (for current state not implemented yet) |
| `froms`      | `varchar(255)` | **Not Null**. Sender email address |
| `receiver`      | `text` | **Not Null**. Receiver email address |
| `receiver`      | `text` | **Not Null**. Receiver cc email address |
| `receiver`      | `text` | **Not Null**. Receiver bcc email address |
| `subject`      | `String` | Email subject |
| `status`      | `varchar(255)` | Using constraints enum ['draf', 'sent', 'queuing'] |
| `body`      | `String` | Body Email |
| `send_at`      | `Timestamp` | Send email timestamp |
| `created_at`      | `Timestamp` | Created email timestamp |
| `updated_at`      | `Timestamp` | Updated email timestamp |

### How to run
to run this project first you need to clone this repo after that
and run 
```
composer install
```
after install the dependency package
please run below to setup the table (config db are in app/Config/database.php)
```
php app/Repository/Postgres/migration.php 
```
after run the migration
you can run the app server and worker queue with

```
run server
php -S localhost:8000 -t public

run worker
php Queuing.php
```
