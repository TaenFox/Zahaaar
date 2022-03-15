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
                        
                    break;

                    case null:
                        logger('SLCK_EVENT_TEXT==\'8888\' ' . SLCK_EVENT_TEXT=='Будь так любезен, создай канал');
                        if(SLCK_EVENT_TEXT=='8888')
                        {_create_channel(date('\i\n\c\_Y\_m\_d\_H\-i'));};
                        
                    break;
                }
            break;
        }
    break;
}
    logger("Out " . __DIR__ . "/law.php " );

