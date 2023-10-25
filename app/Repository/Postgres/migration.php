<?php
error_reporting(E_ERROR | E_PARSE);
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/conn.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

DB::schema()->create('mails', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->string('froms')->index();
    $table->text('receiver');
    $table->text('cc')->nullable();
    $table->text('bcc')->nullable();
    $table->text('body')->nullable();
    $table->string('subject')->nullable();
    $table->enum('status', ['draf', 'sent', 'queuing']);
    $table->dateTime('send_at')->nullable();
    $table->timestamps();
});
