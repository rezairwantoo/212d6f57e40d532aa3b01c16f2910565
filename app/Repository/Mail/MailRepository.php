<?php
namespace App\Repository\Mail;

include_once __DIR__ .'/../Postgres/conn.php'; 

use App\Constant\Constant;
use App\Repository\Postgres\Connection;
use App\Constant\HttpStatus;

class MailRepository
{
    private $table_name;

    public function __construct($tableName) {
        $this->table_name = $tableName;
    }
    
    public function Insert($req) {
        try {
            // connect to the PostgreSQL database
            $pdo = Connection::get()->connect();
            
            $sql = 'INSERT INTO '.$this->table_name.'(user_id, froms, receiver, cc, bcc, subject, status, body, created_at) 
                VALUES(
                    :user_id,
                    :from,
                    :to,
                    :cc,
                    :bcc,
                    :subject,
                    :status,
                    :body,
                    :created_at)';
            $stmt = $pdo->prepare($sql);
            
            // pass values to the statement
            $stmt->bindValue(':user_id', $req['user_id']);
            $stmt->bindValue(':from', $req['from']);
            $stmt->bindValue(':to', $req['to']);
            $stmt->bindValue(':cc', $req['cc']);
            $stmt->bindValue(':bcc', $req['bcc']);
            $stmt->bindValue(':subject', $req['subject']);
            $stmt->bindValue(':status', Constant::OnQueuing);
            $stmt->bindValue(':body', $req['body']);
            $stmt->bindValue(':created_at', date('Y/m/d H:i:s'));
            
            // execute the insert statement
            $stmt->execute();
        
            $ch = \curl_init();
            \curl_setopt($ch, CURLOPT_URL, "127.0.0.1:8001");
            \curl_exec($ch);
            \curl_close($ch);
            // return generated id
            return [
                "errors" => "",
                "messages" => "Success Saving mail, your email are in queuing",
                "status" => HttpStatus::OK,
                "data" => $pdo->lastInsertId()
            ];
            
        } catch (\PDOException $e) {
            return [
                "errors" => $e->getMessage(),
                "messages" => "Failed insert mail data",
                "status" => HttpStatus::InternalServerError,
                "data" => []
            ];
        }
    }

    public function AllQueuing() {
        try {
            // connect to the PostgreSQL database
            $pdo = Connection::get()->connect();

            $sql = 'SELECT * '
                . 'FROM '.$this->table_name
                . ' where status = :status ORDER BY id asc';

            $stmt = $pdo->prepare($sql);
        
            // pass values to the statement
            $stmt->bindValue(':status', Constant::OnQueuing);
            $stmt->execute();
            $emails = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $emails[] = [
                    'id' => $row['id'],
                    'to' => $row['receiver'],
                    'froms' => $row['from'],
                    'subject' => $row['subject'],
                ];
            }
            return $emails;
            
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return [
                "errors" => $e->getMessage(),
                "messages" => "Failed get queuing data",
                "status" => HttpStatus::InternalServerError,
                "data" => []
            ];
        }
    }

    public function UpdateToSent($id) {
        try {
            // connect to the PostgreSQL database
            $pdo = Connection::get()->connect();

            $sql = 'UPDATE '.$this->table_name
                . ' SET status = :new_status, send_at = :send_at, updated_at = :updated_at '
                . 'where id = :email_id';

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':new_status', Constant::Sent);
            $stmt->bindValue(':email_id', $id);
            $stmt->bindValue(':send_at', date('Y/m/d H:i:s'));
            $stmt->bindValue(':updated_at', date('Y/m/d H:i:s'));
           
            $stmt->execute();

            return [
                "errors" => "",
                "messages" => "Success update email to sent",
                "status" => HttpStatus::OK,
                "data" => $stmt->rowCount()
            ];
            
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return [
                "errors" => $e->getMessage(),
                "messages" => "Failed get queuing data",
                "status" => HttpStatus::InternalServerError,
                "data" => []
            ];
        }
    }
}
