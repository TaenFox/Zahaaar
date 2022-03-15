<?php
    //токен канала
$_channel_token = 'G01CB350SAE';
    //название канала (без пробелов)
$_channel_name = 'hard_work';
    //загружаем в массив информацию
$all_channels[$_channel_token]['name']=$_channel_name;
$all_channels[$_channel_token]['dir']=__DIR__;
logger("Настройка каналов - получен " . $_channel_name);

