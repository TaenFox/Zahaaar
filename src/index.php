<?php
      /*
      Привет
      это бот разработанный для нужд команды сопровождения
      сервисов ООО "Ситимобил"
      Его первичная функция - ставить эмодзи в ответ на упоминания в канале
      Он используется для распределения очередей в тикетнице HALP и обеспечивает
      разделение заявок на разные очереди при публичном размешении списка заявок
      */
require_once('functions.php');
require_once('API_Slack.php');
      /*
      functions.php содержит все функции, которые используются в этом боте
      без него тут вообще ничего не будет работать
      */

      /*
      для включение решима тестирования и логгирования нужно поставить значение
      IS_TEST = true;
      */
DEFINE("IS_TEST", getenv('IS_TEST'));

      /*
      делаем штамп времени для логгирования
      */
define('TS_LOG', "TS" . microtime(true));

logger("Входящее подключение");

logger("Режим теста включен");

require_once('Slack.php');
logger("Загружен файл Slack.php");
      /*
      slack.php содержит следующую информацию
      DEFINE('UserOAuthToken', '');
      DEFINE('BotUserOAuthToken', '');
      DEFINE('AppToken', '');
      DEFINE('SLCK_CURRENT_CHANEL_WEBHOOK', '');

      Так как это информация для авторизации - она живёт отдельным файлом
      и не входит в состав проекта
      */
require_once('Reaction_to_card.php');
logger("Загружен файл Reaction_to_card.php");
      /*
      Файл Reaction_to_card.php содержит информацию о соответствии форм, публи-
      куемых в канале (и которые упоминают бота)
      */
print "HTTP 200 OK";
//logger(file_get_contents("php://input"));
      /*
      отвечаем слаку что всё у нас тут хорошо, запрос обработан
      пишем в логи входящий файл json
      */

_get_consts();          //получили все переменные
logger("Входящий запрос обработан");

      /*
      Проверяем тип входящего запроса

      -url_verification
      при первом подключении к серверу отправки эвентов слака
      нужно пройти процедуру авторизации. Для этого на запрос содержащий
      type => url_verification
      нужно ответить соответственным образом.
      Мануал - https://api.slack.com/events/url_verification
      */
if (SLCK_TYPE === 'url_verification')       //фрагмент для авторизации
{
    logger("SLCK_TYPE => url_verification");
    print "Content-type: text/plain";
    print SLCK_CHALLENGE;
}
if(SLCK_EVENT_BOT_ID!=BOT_ID)
{
      _selecter_of_channels();
}
logger("---------");
exit();
//-------------------------------конец лупы------------------
