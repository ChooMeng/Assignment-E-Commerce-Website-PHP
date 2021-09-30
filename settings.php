<?php
    if (!defined('DB_HOST')){
        define('TIMEZONE','Asia/Kuala_Lumpur'); //Set default timezone
        /*DATABASE*/
        define('DB_HOST', 'localhost'); //Database Hostname
        define('DB_USER', 'root'); //Database Username
        define('DB_PASS', ''); //Database Password
        define('DB_NAME', 'dailymarket'); //Database name
        define('LIST_PER_PAGE',20); //A max result can display by a list of page
        define('SUGGEST_DATA_MAX_AMOUNT',8); //Amount of the data been suggested in datalist
        define('MAX_RECENT_RECORD',5); //A max recent record result wil lbe display
        define('EMAIL_HOST','smtp.gmail.com');//host for google smtp gmail
        define('EMAIL_PORT',587);//email port for SMTP host
        define('EMAIL_USERNAME','{username}');//Email username for SMTP 
        define('EMAIL_PASSWORD','{password}');//Email password
        define('EMAIL_DEBUG',false); //Only open when debug the email
        define('EMAIL_ALLOWED',array("abc@gmail.com"));
        define('MAX_CATEGORY_IN_LIST',8);
        define('MAX_PRODUCT_IN_LIST',8);
        define('MAX_CLIENT_VIEW_ORDER_RESULT',25);
    }
    date_default_timezone_set(TIMEZONE);
    
    
