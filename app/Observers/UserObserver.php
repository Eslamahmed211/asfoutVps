<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UsersLog;

class UserObserver
{
  /**
   * Handle the User "created" event.
   */
  public function creating(User $user): void
  {
  }

  /**
   * Handle the User "updated" event.
   */
  public function updating(User $user): void
  {
    $oldData = $user->getOriginal();
    $newData = $user->getDirty();

    $updatedColumns = [];

    foreach ($newData as $key => $value) {
      if ($oldData[$key] !== $value) {
        $updatedColumns[$key] = [
          'old' => $oldData[$key],
          'new' => $value,
        ];
      }
    }


    if (!empty($updatedColumns)) {
      $updateLog = new UsersLog([
        'user_id' => $user->id,
        'changer' => auth()->user()->id ?? null,
        'updated_columns' => json_encode($updatedColumns),
      ]);
      $updateLog->save();
    }
  }



  /**
   * Handle the User "deleted" event.
   */
  public function deleted(User $user): void
  {
  }

  /**
   * Handle the User "restored" event.
   */
  public function restored(User $user): void
  {
    //
  }

  /**
   * Handle the User "force deleted" event.
   */
  public function forceDeleted(User $user): void
  {
  }
}
