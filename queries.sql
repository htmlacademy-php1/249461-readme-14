INSERT INTO users (email, login, user_pass, avatar)
VALUES ('larisa@readme.loc', 'Лариса', '111111', 'img/userpic-larisa-small.jpg'),
       ('vladik@readme.loc', 'Владик', '111111', 'img/userpic.jpg'),
       ('viktor@readme.loc', 'Виктор', '111111', 'img/userpic-mark.jpg');

INSERT INTO types (title, class)
VALUES ('Текст', 'text'),
       ('Цитата', 'quote'),
       ('Фото', 'photo'),
       ('Видео', 'video'),
       ('Ссылка', 'link');

INSERT INTO posts (title, text, quote_author, image, video, link, views, post_author, post_type)
VALUES ('Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', '', '', '', '', '20', '1', '2'),
       ('Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', '', '', '', '', '25', '2', '1'),
       ('Пишем первую функцию', 'Чтобы карточки оставались компактными и не занимали слишком много места размер содержимого надо принудительно ограничивать. Для фотографий и видео это можно сделать через CSS, для цитат и ссылок есть ограничение длины при создании поста. Остаётся текстовый контент. Его длина никак не ограничивается в момент создания, а так как пользователи могут писать очень длинные тексты, необходимо предусмотреть обрезание текста до приемлемой длины при показе карточки поста на странице популярного.', '', '', '', '', '35', '2', '1'),
       ('Наконец, обработал фотки!', '', '', 'img/rock-medium.jpg', '', '', '10', '3', '3'),
       ('Моя мечта', '', '', 'img/coast-medium.jpg', '', '', '16', '1', '3'),
       ('Лучшие курсы', '', '', '', '', 'www.htmlacademy.ru', '55', '2', '5');

INSERT INTO comments (comment, author, post)
VALUES ('Крутая фотка', '1', '4'),
       ('Последний сезон подкачал', '3', '2');

INSERT INTO likes (author, post)
VALUES ('3', '1'),
       ('2', '1'),
       ('1', '4'),
       ('1', '3'),
       ('3', '6'),
       ('3', '2'),
       ('2', '5'),
       ('1', '5'),
       ('1', '4'),
       ('2', '4');

INSERT INTO subscribes (follower, host)
VALUES ('1', '2'),
       ('3', '2'),
       ('1', '3'),
       ('2', '3'),
       ('2', '1');

INSERT INTO message (message, sender, receiver)
VALUES ('Привет, классные посты делаешь', '1', '3'),
       ('Привет. Спасибо.', '3', '1');

INSERT INTO hashtags (hashtag)
VALUES ('#photooftheday'),
       ('#щикарныйвид'),
       ('#landscape'),
       ('#сериал'),
       ('#курсы');

INSERT INTO has_posts (hashtag, post)
VALUES ('1', '4'),
       ('2', '4'),
       ('3', '4'),
       ('1', '5'),
       ('2', '5'),
       ('3', '5'),
       ('4', '2'),
       ('5', '6');


/* получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента; */
SELECT p.id, p.dt_add, p.title, p.text, p.quote_author, p.image, p.video, p.link, p.views, u.login, t.title
  FROM posts p
  JOIN users u ON u.id = p.post_author
  JOIN types t ON t.id = p.post_type
  ORDER BY views DESC;

/* получить список постов для конкретного пользователя; */
SELECT * FROM posts WHERE post_author = 2;

/* получить список комментариев для одного поста, в комментариях должен быть логин пользователя; */
SELECT c.comment, u.login
  FROM comments c
  JOIN users u ON u.id = c.author
  WHERE post = 4;

/* добавить лайк к посту; */
INSERT INTO likes (author, post) VALUES ('3', '4');

/* подписаться на пользователя; */
INSERT INTO subscribes (follower, host) VALUES ('3', '1');
