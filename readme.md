# Backend Task
Build a RESTful-API ecosystem for to-do list with Laravel, PHP 7.2, Nginx, MySQL.

## DB schema fields
### Database
| Tables_in_todolist |
|:------------------:|
|        tasks       |
|        users       |
|   password_resets  |
|     failed_jobs    |
|     migrations     |

### Table - users
|       Field       |         Type        | NULL | Key | Default |      Extra     |
|:-----------------:|:-------------------:|:----:|:---:|:-------:|:--------------:|
|         id        | bigint(20) unsigned |  NO  | PRI |   NULL  | auto_increment |
|        name       |     varchar(191)    |  NO  |     |   NULL  |                |
|       email       |     varchar(191)    |  NO  | UNI |   NULL  |                |
| email_verified_at |      timestamp      |  YES |     |   NULL  |                |
|      password     |     varchar(191)    |  NO  |     |   NULL  |                |
|   remember_token  |      timestamp      |  YES |     |   NULL  |                |
|     created_at    |      timestamp      |  YES |     |   NULL  |                |
|     updated_at    |      timestamp      |  YES |     |   NULL  |                |
### Table - tasks
|    Field   |         Type        | NULL | Key | Default |      Extra     |
|:----------:|:-------------------:|:----:|:---:|:-------:|:--------------:|
|     id     | bigint(20) unsigned |  NO  | PRI |   NULL  | auto_increment |
|   user_id  |   int(10) unsigned  |  NO  | MUL |   NULL  |                |
|    title   |     varchar(191)    |  NO  |     |   NULL  |                |
|   content  |         text        |  NO  |     |   NULL  |                |
| attachment |         text        |  NO  |     |   NULL  |                |
|   done_at  |      timestamp      |  YES |     |   NULL  |                |
|  delete_at |      timestamp      |  YES |     |   NULL  |                |
| created_at |      timestamp      |  YES |     |   NULL  |                |
| updated_at |      timestamp      |  YES |     |   NULL  |                |

### API List

Login
* register
    `POST localhost:8000/api/register`
* login
    `POST localhost:8000/api/login`
* logout
    `GET localhost:8000/api/logout`

Tasks
* get all to-do lists
    `GET localhost:8000/api/tasks`
* get one to-do list
    `GET localhost:8000/api/tasks/{task_id}`
* create one to-do list
    `POST localhost:8000/api/tasks`
* update one to-do list
    `PUT localhost:8000/api/tasks/{task_id}`
* delete one to-do list
    `DELETE localhost:8000/api/tasks/{task_id}`
* delete all to-do list
    `DELETE localhost:8000/api/tasks`

Token
* generate a new token
    `GET localhost:8000/api/refresh`
* get token status (Only if tokens with TTL or RefreshToken)
    `GET localhost:8000/api/status`
    - TTL = 1 hour
    - RefreshTTL = 2 weeks