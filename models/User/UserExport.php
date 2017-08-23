<?php namespace HS\Models\User;

use Str;
use Auth;
use Mail;
use Event;
use October\Rain\Auth\Models\User as UserBase;
use Backend\Models\ExportModel;
use HS\Models\User\User;

class UserExport extends ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $subscribers = User::all();
        $subscribers->each(function($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $subscribers->toArray();
    }
}