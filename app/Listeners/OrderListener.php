<?php

namespace App\Listeners;

use App\Events\NewOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewOrder $event): void
    {
        //ID канала куда отправляем
        $idChannel = '5118266266';
        //токен бота которым отправляем сообщение
        $botToken = '5778112243:AAHTjPPiZC1_degvI_lEXHmKhrRDF6vJcfg';
        //наше импровизированное сообщение
        $message = "Отправлено из Laravel";
        //кодируем его, чтобы сохранить переносы строк
        $message = urlencode($message);
        //после этого отправляем
        try {
            $x = file_get_contents('https://api.telegram.org/bot' . $botToken . '/sendMessage?chat_id=' . $idChannel . '&text=' . $message);
            dump('Извещение в telegram канал отправлено');
            dump($x);
            dd($event);
        } catch (\Exception $e) {
            dd($e);
        }

    }
}
