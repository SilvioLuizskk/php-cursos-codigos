<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogProductCreated implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        Log::info('Novo produto criado', [
            'product_id' => $event->product->id,
            'name' => $event->product->name,
            'user_id' => $event->product->user_id,
            'price' => $event->product->price,
        ]);
    }
}
