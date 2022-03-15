<?php
    //токен канала
$_channel_token = 'unknown';
    //название канала (без пробелов)
$_channel_name = 'unknown';
    //загружаем в массив информацию
$all_channels[$_channel_token]['name']=$_channel_name;
$all_channels[$_channel_token]['dir']=__DIR__;
logger("Настройка каналов - получен " . $_channel_name);
