<?php
    //токен канала
$_channel_token = 'CUF7SSM28';
    //название канала (без пробелов)
$_channel_name = 'cопровождение_сервисов';
    //загружаем в массив информацию
$all_channels[$_channel_token]['name']=$_channel_name;
$all_channels[$_channel_token]['dir']=__DIR__;
logger("Настройка каналов - получен " . $_channel_name);
