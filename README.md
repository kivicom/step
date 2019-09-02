1. В проект добавил папку database. 
В данной папке содержатся два файла:
1) Connection.php - используется для соединения с БД
2) start.php - в данном файле идут подключения слассов.

2. В проект добавил папку components.
В данной папке содержаться два компонента:
1) Admin.php - для управления комментариями. Показать/Выключить/Удалить
2) Comment.php - для вывода комментариев на главной странице.
3) User.php - здесь методы для регистрации, авторизации, обновления профиля, обновление пароля, получение юзера по ID, запомнить меня, получение email. 

3. Так же в остальных файлах, где были прописаны запросы в БД, я заменил на конструкцию вида:
require_once 'database/start.php'; // Подключение компонентов
$db = new Comment(Connection::make($config['database'])); // Соединение с БД
$comments = $db->getAll('comments'); // Вызов метода 
