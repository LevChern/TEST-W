# ![logo](https://i.ibb.co/4g35khV/upload.png) TEST-W
--------------
## Введение
TEST-W - web-приложение для проведения тестирования.
Система имеет три типа пользователей: администратор, преподаватель и студент.
Администратор ведет список дисциплин, список преподавателей и списки студентов по группам. Для каждой дисциплины вводятся тестовые задания. К преподавателю приписываются дисциплины и группы. 
Логины и пароли для преподавателей задаются администратором. Для проведения тестирования преподаватель выбирает группу, дисциплину и параметры сеанса тестирования: число вопросов по каждой теме дисциплины, время на прохождения теста и критерии оценивания. Для начала тестирования открывается доступ.
Студент выбирает группу, свою фамилию (возможно и пароль) и выполняет тестовые задания.
По заввершению тестирования преподаватель закрывает доступ и просматривает результаты тестирования.
Представленный дистрибутив уже проинициализирован. Администратор имеет пароль 314 (задан в программе index_login.php). Введены 2 преподавателя и 2 дисциплины. По дисциплине "Web-программирование" загружена база тестовых заданий по 6 темам.
Подробные инструкции выдаются по ссылкам на основном экране приложения.
--------------
## Требования
Требования к ПО:
* Web-серсер с интерпретатором PHP версии 6.0 или выше
* браузеры [Mozilla Firefox](https://www.mozilla.org) и\или [Google Chrome](https://www.google.com/chrome/);
--------------
## Установка
1. Загрузить проект на web-сервер в выбранную папку, например TEST-W;
2. Запустить приложение из браузера, нампример localhost/TEST-W.
--------------
## Копирайт
Лицензия: GPL 3.0

Прочтите файл [LICENSE](./LICENSE).
