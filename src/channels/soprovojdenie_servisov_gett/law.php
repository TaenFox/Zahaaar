<?php
logger("Run " . __DIR__ . "/law.php " );
switch(SLCK_TYPE)
{
    case "event_callback":
        switch(SLCK_EVENT_TYPE)
        {
            case "app_mention":

            break;

            case "message":
                switch(SLCK_EVENT_SUBTYPE)
                {
                    case "bot_message":
                        logger("SLCK_EVENT_SUBTYPE => message");
                        logger("SLCK_EVENT_CHANNEL => " . SLCK_EVENT_CHANNEL);
                        logger("message => " . SLCK_EVENT_TEXT);
                        if (isset(REACTION_TO_CARD_ARRAY[SLCK_EVENT_USERNAME]))
                        {
                            logger("Существует элемент \'".SLCK_EVENT_USERNAME."\' в REACTION_TO_CARD_ARRAY");
                        /*
                        Если такой элемент существует - аналогичным образом вызываем функцию
                        чтобы поставить соответствующую реакцию на сообщение формы
                        */
                            sleep(1); //пауза, потому что HALP при моментальной установке делает два тикета
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
                    break;
                }
            break;
        }
    break;
}
    logger("Out " . __DIR__ . "/law.php " );

