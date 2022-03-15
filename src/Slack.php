<?php
$vars_from_env = array("UserOAuthToken", "BotUserOAuthToken", "AppToken", "SLCK_CURRENT_CHANEL_WEBHOOK", "BOT_ID", "APP_TEAM_SUP_SERVICE");

foreach ($vars_from_env as $var_name) {
    DEFINE("$var_name", getenv($var_name));
}
