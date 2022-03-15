<?php

DEFINE("LOG_FILE_DIR", "/var/log/app.log");

//функция чтобы вытащить все переменные, мы их тут же объявляем константами и суём куда ни попадя
function _get_consts()
{
    $message = json_decode(file_get_contents("php://input"), true);
if(isset($message['token'])){define ('SLCK_TOKEN', $message['token']);}else{define ('SLCK_TOKEN', null);};
if(isset($message['api_app_id'])){define ('SLCK_API_APP_ID', $message['api_app_id']);}else{define ('SLCK_API_APP_ID', null);};
if(isset($message['event'])){define ('SLCK_EVENT', $message['event']);}else{define ('SLCK_EVENT', null);};
if(isset(SLCK_EVENT['type'])){define ('SLCK_EVENT_TYPE', SLCK_EVENT['type']);}else{define ('SLCK_EVENT_TYPE', null);};
if(isset(SLCK_EVENT['bot_id'])){define ('SLCK_EVENT_BOT_ID', SLCK_EVENT['bot_id']);}else{define ('SLCK_EVENT_BOT_ID', null);};
if(isset(SLCK_EVENT['subtype'])){define ('SLCK_EVENT_SUBTYPE', SLCK_EVENT['subtype']);}else{define ('SLCK_EVENT_SUBTYPE', null);};
if(isset(SLCK_EVENT['user'])){define ('SLCK_EVENT_USER', SLCK_EVENT['user']);}else{define ('SLCK_EVENT_USER', null);};
if(isset(SLCK_EVENT['text'])){define ('SLCK_EVENT_TEXT', SLCK_EVENT['text']);}else{define ('SLCK_EVENT_TEXT', null);};
if(isset(SLCK_EVENT['ts'])){define ('SLCK_EVENT_TS', SLCK_EVENT['ts']);}else{define ('SLCK_EVENT_TS', null);};
if(isset(SLCK_EVENT['channel'])){define ('SLCK_EVENT_CHANNEL', SLCK_EVENT['channel']);}else{define ('SLCK_EVENT_CHANNEL', null);};
if(isset(SLCK_EVENT['event_ts'])){define ('SLCK_EVENT_EVENT_TS', SLCK_EVENT['event_ts']);}else{define ('SLCK_EVENT_EVENT_TS', null);};
if(isset(SLCK_EVENT['username'])){define ('SLCK_EVENT_USERNAME', SLCK_EVENT['username']);}else{define ('SLCK_EVENT_USERNAME', null);};
if(isset(SLCK_EVENT['thread_ts'])){define ('SLCK_EVENT_THREAD_TS', SLCK_EVENT['thread_ts']);}else{define ('SLCK_EVENT_THREAD_TS', null);};
if(isset($message['type'])){define ('SLCK_TYPE', $message['type']);}else{define ('SLCK_TYPE', null);};
if(isset($message['event_id'])){define ('SLCK_EVENT_ID', $message['event_id']);}else{define ('SLCK_EVENT_ID', null);};
if(isset($message['event_time'])){define ('SLCK_EVENT_TIME', $message['event_time']);}else{define ('SLCK_EVENT_TIME', null);};
if(isset($message['authed_users'])){define ('SLCK_AUTHED_USERS', $message['authed_users']);}else{define ('SLCK_AUTHED_USERS', null);};
if(isset($message['challenge'])){define ('SLCK_CHALLENGE', $message['challenge']);}else{define ('SLCK_CHALLENGE', null);};

_convers_info();  //получаем информацию о канале

      /*
      пишем входящий запрос
      */
logger_pro(json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

    // logger(file_get_contents("php://input"));
}

function logger($t)
{
    /*
          Это функция логгирования. Что бы её включить нужно
          постаивть значение переменной IS_TEST = true
    */
    if (IS_TEST == "true")
        {
            $log = TS_LOG . " " . date('Y-m-d H:i:s') . " (" . microtime(true) . ") " . $t;
            file_put_contents(LOG_FILE_DIR, $log . PHP_EOL, FILE_APPEND);
            // error_log($t . PHP_EOL);
        };
}

function logger_pro($txt)
{
    $log = TS_LOG . " " . date('Y-m-d H:i:s') . " (" . microtime(true) . ") " . $txt;
    file_put_contents(LOG_FILE_DIR, $log . PHP_EOL, FILE_APPEND);
}


//функция выбора эмодзи в соответствии с reaction_to_card.php
function emoji_bot_response()
{
    logger(SLCK_EVENT_THREAD_TS);
    if (SLCK_EVENT_SUBTYPE === "bot_message" && is_null(SLCK_EVENT_THREAD_TS)) //уточняем, что нас упомянул бот/форма и не в ответ на тред
        {
            logger("SLCK_EVENT_SUBTYPE => bot_message");
            /*
            далее мы уверены, что тег пришёл от формы или бота и обрабатываем
            его в соответствии с названием (именем пользователя) формы/бота
            Перечень пар соответствия название-реакция указан в файле
            Reaction_to_card.php в формате массива. Его можно дополнять,
            для увеличения вариативности
            */
            
            /*
            Первым делом убеждаемся, что у нас есть такая пара название-реакция
            для этого вызываем именованный элемент массива по названию формы
            */  
            if (isset(REACTION_TO_CARD_ARRAY[SLCK_EVENT_USERNAME]))
                {
                    logger("Существует элемент \'".SLCK_EVENT_USERNAME."\' в REACTION_TO_CARD_ARRAY");
                /*
                Если такой элемент существует - аналогичным образом вызываем функцию
                чтобы поставить соответствующую реакцию на сообщение формы
                */
                    add_emoji(REACTION_TO_CARD_ARRAY[SLCK_EVENT_USERNAME]);
                    logger("Отправили эмодзи :".REACTION_TO_CARD_ARRAY[SLCK_EVENT_USERNAME].":");
                }else{
                    logger("Элемент \'".SLCK_EVENT_USERNAME."\' в REACTION_TO_CARD_ARRAY отсутствует");
                /*
                Если такого названия в массиве не предусмотрено - делаем
                следующие действия:
                */
                    //add_emoji("char_h");  //ставим две эмози "ХЗ""
                    //add_emoji("char_z");
                };

        }else{
            //тут нужно написать что бот пишет в ответ
            //какую нибудь глупость типа "Барин, я только рожицы ставить умею!"
            //add_emoji("party_parrot");
        }

}

/*
    Функция для получения настроек всех каналов в каталоге /channels
*/
function _get_channels_info()
{
    $my_dir = __DIR__;
    //logger('Путь для $my_dir - ' . $my_dir);
    //logger('is_dir($my_dir) = ' . is_dir($my_dir));
    //logger("Получаем настройки каналов: " . json_encode(scandir($my_dir)));
    $_scan_dir = scandir($my_dir . '/channels');

    foreach ($_scan_dir as $any_dir)
        {
            $cur_dir = $my_dir . '/channels/' . $any_dir;
            //logger('$cur_dir - ' . $cur_dir);
            if($any_dir != "." and $any_dir != ".." and is_dir($cur_dir))
              {
                  require_once($cur_dir.'/channel_info.php');
              }
        }
    DEFINE('CHANNELS', $all_channels);
    logger("Текущие настройки каналов: ". json_encode(CHANNELS, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    /* возвращает
        Array ( [CUF7SSM28] =>
            Array (
                    [name] => cопровождение_сервисов
                    [dir] => C:\apache\localhost\www\channels\service_providing_main
             и т.д.)
    */
}

/*
    функция обработки подключения в соответствии с каналом слака
*/
function _selecter_of_channels()
{
    _get_channels_info();
    if (isset(CHANNELS[SLCK_EVENT_CHANNEL]))
    {
        logger("Работаем по law.php канала ".CHANNELS[SLCK_EVENT_CHANNEL]['name']);
        require_once(CHANNELS[SLCK_EVENT_CHANNEL]['dir'].'/law.php');
    }
    else
    {
        logger("Работаем по law.php неизвестного канала");
        require_once(CHANNELS['unknown']['dir'].'/law.php');
    }
}

function _is_that_chanel($type_of_name, $name)
{
  switch ($type_of_name)
  {
    case 'inc':
      return(preg_match('/inc[0-9]{4}\_[0-9]{2}\_[0-9]{2}\_[0-9]{2}\-[0-9]{2}/',$name ));
      break;

    default:
      return(false);
      break;
  }
}
