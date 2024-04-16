<?php

namespace App\Observers;

use App\Models\order;
use App\Models\User;
use App\Notifications\userNotification;
use Carbon\Carbon;

class orderObserve
{
  function updating(order $order): void
  {



    if ($order->isDirty("status")) {

      $oldStatus = $order->getOriginal("status");
      $newStatus = $order->getAttribute("status");

      $log = [
        "oldStatus" => $oldStatus,
        "newStatus" => $newStatus,
        "date" => Carbon::now(),
        "user" => auth()->id(),
        "type" => "ChangeStatus"
      ];


      $logs = array_merge([$log], $order->logs ?? []);
      usort($logs, function ($a, $b) {
          return strtotime($b['date']) - strtotime($a['date']);
      });

      $order->logs = $logs;

      $user = User::find($order->user_id);

      if (!in_array($newStatus , $user->notification_settings) ) {
         return ;
      }


      $message = " تم تغير حالة الاوردر من " . $oldStatus . " الي " . $newStatus ;
      $content = ['message' =>  $message , "order_id" => $order->id, "type" => "orderStatus" , "newStatus" => $newStatus ];
      $user->notify(new userNotification($content));

      if ($user->role != "user") {
        $user = User::find($user->marketer_id);
        $user->notify(new userNotification($content));
      }



    }
  }
}
