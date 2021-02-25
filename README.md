_Начало работы 25 февраля 14:00 по времени Алматы_

#Задание 1.

select
       u.first_name,
       u.last_name,
       b.author,
       GROUP_CONCAT(DISTINCT b.name ORDER BY b.name ASC SEPARATOR ', ') AS books
from users u
join user_books ub on u.id = ub.user_id
join books b on ub.book_id = b.id
where u.age between 7 and 17
group by u.id, b.author
having count(b.name) = 2;

Схема: /databases/schema/mysql-schema.dump

#Задание 2.

Необходимые действия:
1) Заполняем данными файл .env
2) composer install
3) php artisan migrate
4) php artisan passport:install
5) php artisan db::seed
6) Открываем таблицу users, берем любого пользователя. Пароль: secret

_Окончание работ 25 февраля 19:30 по времени Алматы_
