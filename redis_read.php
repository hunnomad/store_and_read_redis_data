<?php

# Developer stuff, bug reporting on/off ----------------------------------------------------
error_reporting(E_ALL);
ini_set("display_errors", 1);
# Developer stuff, bug reporting on/off ----------------------------------------------------

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
        $redis->connect($_ENV['REDIS_HOST'],$_ENV['REDIS_PORT']); // Redis server access data
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
    
                // // Process the message
                // if (processMessage($data))
                // {
                //     // Successful processing
    
                // $redis->lrem($key, 0, $data); // Delete the first item from the list
                // }
                // else
                // {
                //     // Unsuccessful processing
                //     $redis->del('messages', $message[0]);
                // }
            }
            else
            {
                // Timeout
                
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
        echo 'An error occurred.: '.$e->getMessage();
    }
}
else
{
    exit("Redis class is not available.");
}

?>
