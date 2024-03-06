<?php
namespace Armaulan\DmsTelegram\Class;

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
use CURLFile;

class Telegram {

    private string $key;

    public function __construct($botName) {
        $dotenv = Dotenv::createImmutable('../');
        $dotenv->load();

        // Load bot tokens from environment variables
        $this->key = $_ENV[$botName];

    }

    // Send a message using a specific bot
    public function sendMessage($chatId, $message) {
        if (!isset($this->key)) {
            throw new \InvalidArgumentException("Invalid bot key");
        }
        
        $messageEncoded = urlencode($message);
        $key = $this->key;
        #echo $key . PHP_EOL;

        $ch = curl_init("https://api.telegram.org/bot$key/sendMessage?chat_id=$chatId&text=$messageEncoded");
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);

        curl_close($ch);

        // Process the response
        $data = json_decode($response, true);
        #var_dump($data);
        return $data;

    }

    public function sendDocument($chatId, $path) {
        if (!isset($this->key)) {
            throw new \InvalidArgumentException("Invalid bot key");
        }  

        $key = $this->key;
        $telegram_docs = "https://api.telegram.org/bot$key/sendDocument?chat_id=$chatId";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $telegram_docs);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // Create CURLFile
        $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        $cFile = new CURLFile($path, $finfo);

        // Add CURLFile to CURL request
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "document" => $cFile
        ]);

        $result = curl_exec($ch);
        if ($result === false) {
            $error_message = curl_error($ch);
            echo "cURL error: $error_message";
        }
        curl_close($ch);

        return "Document sent successfully";

    }

    public function setKey($key) {
        $this->key = $key;
    }
}