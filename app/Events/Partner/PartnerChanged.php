<?php

namespace App\Events\Partner;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartnerChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $partner;

    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($partner = null, string $action = 'changed')
    {
        $this->partner = $partner;
        $this->action = $action;
    }
}
