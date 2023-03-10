<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const OFF = 0;
    const ON = 1;

    const DELETE_LOCATION_NOTIFICATIONS = "DELETE_LOCATION_NOTIFICATIONS";
    const UPDATE_LOCATION_NOTIFICATIONS = "UPDATE_LOCATION_NOTIFICATIONS";

    const USER_REGISTER_NOTIFICATIONS = "USER_REGISTER_NOTIFICATIONS";

    const TASK_ASSIGN_NOTIFICATIONS = "TASK_ASSIGN_NOTIFICATIONS";
    const TASK_DELETE_NOTIFICATIONS = "TASK_DELETE_NOTIFICATIONS";
    const TASK_UPDATE_NOTIFICATIONS = "TASK_UPDATE_NOTIFICATIONS";

    const START_WORK_NOTIFICATIONS = "START_WORK_NOTIFICATIONS";
    const COMPLETED_WORK_NOTIFICATIONS = "COMPLETED_WORK_NOTIFICATIONS";
    const START_WORK_DELAY_NOTIFICATIONS = "START_WORK_DELAY_NOTIFICATIONS";
    const TASK_REMINDER_NOTIFICATIONS = "TASK_REMINDER_NOTIFICATIONS";

    const SEND_PAYROLL_REPORTS_AUTO = "SEND_PAYROLL_REPORTS_AUTO";
    const CLEAR_ATTENDANCES_AUTO = "CLEAR_ATTENDANCES_AUTO";
    const CLEAR_NOTIFICATIONS_AUTO = "CLEAR_NOTIFICATIONS_AUTO";
    const BACKUP_DATABASE_AUTO = "BACKUP_DATABASE_AUTO";

    const ANDROID_VERSION = "ANDROID_VERSION";
    const IOS_VERSION = "IOS_VERSION";

    protected $fillable = [
        'name',
        'value',
        'push_notification_state',
        'telegram_notification_state'
    ];
}
