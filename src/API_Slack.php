<?php


//Функция для отправки запросов в Slack API -------------------------------------
function slack($txt,$metod)
{
    logger("slack " . $metod . ": " . json_encode($txt));
    $go_url = 'https://slack.com/api/' . $metod;
    $c = curl_init($go_url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_XOAUTH2_BEARER, BotUserOAuthToken);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $txt);
    $e=curl_exec($c);
    curl_close($c);
    logger("slack api call " . $metod . " result: " . json_encode($e));
    return($e);
}

//функция чтобы поставить эмодзи--------------------------------------------
function add_emoji($emoji)
{
    $array= array(
    "token"  => BotUserOAuthToken,
    "channel" => SLCK_EVENT_CHANNEL,
    "name" => $emoji,
    "timestamp" => SLCK_EVENT_TS
    );
    slack($array, 'reactions.add');
}


function _convers_info($t=null)
{
  if(!isset($t))
  {
      $t = SLCK_EVENT_CHANNEL;
  }
  $array= array(
  "token"  => BotUserOAuthToken,
  "channel" => $t
  );
  $aa = slack($array, 'conversations.info');
  logger($aa);
  $a = json_decode($aa, true);
  if(isset($a['channel']['name'])){DEFINE('SLCK_EVENT_CHANNEL_NAME',$a['channel']['name']);}else{DEFINE('SLCK_EVENT_CHANNEL_NAME',null);};
  logger('Получилось достать имя канала - '.SLCK_EVENT_CHANNEL_NAME);
  /*
  пример ответа
      {
          "ok": true,
          "channel": {
              "id": "C012AB3CD",
              "name": "general",
              "is_channel": true,
              "is_group": false,
              "is_im": false,
              "created": 1449252889,
              "creator": "W012A3BCD",
              "is_archived": false,
              "is_general": true,
              "unlinked": 0,
              "name_normalized": "general",
              "is_read_only": false,
              "is_shared": false,
              "parent_conversation": null,
              "is_ext_shared": false,
              "is_org_shared": false,
              "pending_shared": [],
              "is_pending_ext_shared": false,
              "is_member": true,
              "is_private": false,
              "is_mpim": false,
              "last_read": "1502126650.228446",
              "topic": {
                  "value": "For public discussion of generalities",
                  "creator": "W012A3BCD",
                  "last_set": 1449709364
              },
              "purpose": {
                  "value": "This part of the workspace is for fun. Make fun here.",
                  "creator": "W012A3BCD",
                  "last_set": 1449709364
              },
              "previous_names": [
                  "specifics",
                  "abstractions",
                  "etc"
              ],
              "locale": "en-US"
          }
      }
  */
}

function _send_message($text, $channel = SLCK_EVENT_CHANNEL, $reply_to_thread = null, $is_reply_thread_to_channel = false, $blocks = null)
{
    $array= array(
    "token"  => BotUserOAuthToken,
    "channel" => $channel,
    "text" => $text,
    "reply_broadcast" => $is_reply_thread_to_channel
    );
    if ($reply_to_thread != null)
    {
        $array['thread_ts'] = $reply_to_thread;
    };
    if ($blocks != null)
    {
        $array['blocks'] = $blocks;
    };
    $e = slack($array, 'chat.postMessage');
    return ($e);
}

function _add_pin($ts)
{
    $array= array(
    "token"  => BotUserOAuthToken,
    "channel" => SLCK_EVENT_CHANNEL,
    "timestamp" => $ts
    );
    $e = slack($array, 'pins.add');
    return ($e);
}

function send_message_to_channel($text, $channel = SLCK_EVENT_CHANNEL)
{
    $e = _send_message($text, $channel);
    return ($e);
}

function send_message_to_thread($text, $is_reply_thread_to_channel)
{
    $e = _send_message($text,SLCK_EVENT_CHANNEL, SLCK_EVENT_EVENT_TS, $is_reply_thread_to_channel);
    return ($e);
}

function send_message_to_channel_and_pin($text, $channel = SLCK_EVENT_CHANNEL)
{
    $e = _send_message($text, $channel);
    $e['add_pin']=_add_pin(json_decode($e, true)['ts']);
    return ($e);
}


function _create_channel($name)
{
    $array= array(
    "token"  => BotUserOAuthToken,
    "team_id" => APP_TEAM_SUP_SERVICE,
    "is_private" => true,
    "name" => $name
    );
    
    $e = slack($array, 'conversations.create');
    $e = _invite_channel(json_decode($e, true)['channel']['id'], json_decode($e, true)['channel']['creator']);
    return ($e);
}

function _invite_channel($channel, $c_user)
{
    $array= array(
    "token"  => BotUserOAuthToken,
    "channel" => $channel,
    "user" => $c_user,
    );
    
    $e = slack($array, 'conversations.invite');
    return ($e);
}

