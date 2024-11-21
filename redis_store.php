<?php
# Fejlesztői cucc, hibajelentés be/ki ----------------------------------------------------
error_reporting(E_ALL);
ini_set("display_errors", 1);
# Fejlesztői cucc, hibajelentés be/ki ----------------------------------------------------

# Set timezone ---------------------------------------------------------------------------
date_default_timezone_set("Europe/Budapest");
# Set timezone ---------------------------------------------------------------------------

# Load 3th party extensions --------------------------------------------------------------
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';  // Load composer autoloader
# Load 3th party extensions --------------------------------------------------------------

# Init used extensions -------------------------------------------------------------------
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->safeLoad();
# Init used extensions -------------------------------------------------------------------

if (class_exists('Redis'))
{
    try
    {
    
        $redis = new Redis();
        $redis->connect($_ENV['REDIS_HOST'],$_ENV['REDIS_PORT']); // Redis szerver elérési adatai
        $redis->auth($_ENV['REDIS_PASS']);
    
        # receive data stream ---------------------------------------------------------------
        $fp         = fopen('php://input', 'r');
        $rawData    = stream_get_contents($fp);
        $d          = json_decode($rawData, true);
        # receive data stream ---------------------------------------------------------------
    
        $iData = [
            "date_time"=>"".date('Y-m-d H:i:s')."",
            "first_name"=>"Laci",
            "email"=>"laci@gmail.com",
            "subject"=>"Ez egy teszt...."
        ];
    
        $store = $redis->rpush('form_data', json_encode($iData));
    
        echo "<pre>";
        print_r($store);
        echo "</pre>";
    
    }
    catch (Exception $e)
    {
        // Hiba kezelése
        echo 'Hiba történt: '.$e->getMessage();
    }
}
else
{
    exit("Redis osztály nem elérhető.");
}
?>
