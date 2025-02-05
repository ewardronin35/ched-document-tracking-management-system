<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;

class GuestNotifiable
{
    use Notifiable;

    protected $phone;

    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    public function routeNotificationForVonage()
    {
        return $this->phone; // Return the guest's phone number
    }
}
