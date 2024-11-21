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

# Variables ------------------------------------------------------------------------------
$key = 'form_data';
# Variables ------------------------------------------------------------------------------

if (class_exists('Redis'))
{
    try
    {
    
        $redis = new Redis();
        $redis->connect($_ENV['REDIS_HOST'],$_ENV['REDIS_PORT']); // Redis szerver elérési adatai
        $redis->auth($_ENV['REDIS_PASS']);

        if($redis->llen($key)>0)
        {
            $message = $redis->brpop($key, 10);

            if ($message)
            {
                $data = json_decode($message[1], true);
    
                echo "<pre>";
                print_r($data);
                echo "</pre>";
    
                // // Feldolgozza az üzenetet
                // if (processMessage($data))
                // {
                //     // Sikeres feldolgozás
    
                // }
                // else
                // {
                //     // Sikertelen feldolgozás
                //     $redis->del('messages', $message[0]);
                // }
            }
            else
            {
                // Időtúllépés
                
            }
        }
        else
        {
            echo "Message Queue is empty";
        }
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
