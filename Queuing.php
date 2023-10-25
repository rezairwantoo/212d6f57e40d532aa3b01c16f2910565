<?php
// (A) LOAD LIBRARIES
// composer require react/socket
include_once __DIR__ .'/app/Repository/Postgres/Connection.php'; 
use App\Repository\Mail\MailRepository;
use App\Models\Mail;

// (B) FLAG
$sending = false; // is currently sending emails
$result = null; // last run result

// (C) CREATE ENDPOINT - "PING TO RUN"
$socket = new React\Socket\SocketServer(isset($argv[1]) ? $argv[1] : "127.0.0.1:8001", [
  "tls" => ["local_cert" => isset($argv[2]) ? $argv[2] : (__DIR__ . "/localhost.pem")]
]);
$socket->on("connection", function (React\Socket\ConnectionInterface $connection) {
    $connection->close();
    global $sending, $result;
    if (!$sending) {
        $sending = true;
        do {
            $mailRepo = new MailRepository(Mail::table);
            $result = $mailRepo->AllQueuing();
            if (count($result) == 0) {
                $sending = false;
            } else {
                foreach ($result as $key => $value) {
                    $mailRepo->UpdateToSent($value['id']);
                }
            }

            $result = null;
            if ($result==null) { 
                $sending = false;
             }
            usleep(2); // 0.2 seconds pause
        } while ($result != null);
    }
});
echo "Listening on " . $socket->getAddress() . PHP_EOL;