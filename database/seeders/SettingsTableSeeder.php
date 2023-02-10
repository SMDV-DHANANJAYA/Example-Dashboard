<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            [
                'name' => Setting::START_WORK_NOTIFICATIONS,
                'value' => 1,
                'push_notification_state' => 1,
                'telegram_notification_state' => 1,
                'description' => 'Automatically notify the admin users about the start work',
            ],
            [
                'name' => Setting::COMPLETED_WORK_NOTIFICATIONS,
                'value' => 1,
                'push_notification_state' => 1,
                'telegram_notification_state' => 1,
                'description' => 'Automatically notify the admin users about the completed work',
            ],
            [
                'name' => Setting::START_WORK_DELAY_NOTIFICATIONS,
                'value' => 1,
                'push_notification_state' => 1,
                'telegram_notification_state' => 1,
                'description' => 'Automatically notify the admin users about the delayed work (10 minutes after)',
            ],
            [
                'name' => Setting::TASK_REMINDER_NOTIFICATIONS,
                'value' => 1,
                'description' => 'The user will be automatically reminded of the task before it begins (15 minutes before)',
            ],
            [
                'name' => Setting::USER_REGISTER_NOTIFICATIONS,
                'value' => 1,
                'description' => 'A user who just created a new account will automatically receive an email with account information',
            ],
            [
                'name' => Setting::DELETE_LOCATION_NOTIFICATIONS,
                'value' => 1,
                'description' => 'Users who have previously been assigned to a deleted location will be automatically notify about its deletion',
            ],
            [
                'name' => Setting::UPDATE_LOCATION_NOTIFICATIONS,
                'value' => 1,
                'description' => 'Users who have previously been assigned to a updated location will be automatically notify about its update',
            ],
            [
                'name' => Setting::TASK_ASSIGN_NOTIFICATIONS,
                'value' => 1,
                'description' => 'Automatically notify the assign user of any new job assignments',
            ],
            [
                'name' => Setting::TASK_DELETE_NOTIFICATIONS,
                'value' => 1,
                'description' => 'The related users will be notify automatically when a previously assigned job is deleted',
            ],
            [
                'name' => Setting::TASK_UPDATE_NOTIFICATIONS,
                'value' => 1,
                'description' => 'The related users will be notify automatically when a previously assigned job is updated',
            ],
            [
                'name' => Setting::SEND_PAYROLL_REPORTS_AUTO,
                'value' => 1,
                'telegram_notification_state' => 1,
                'description' => 'Automatically email monthly payroll report to associated users',
            ],
            [
                'name' => Setting::CLEAR_ATTENDANCES_AUTO,
                'value' => 1,
                'telegram_notification_state' => 1,
                'description' => 'The quarterly attendance data will be automatically deleted (Manual deletion is possible)',
            ],
            [
                'name' => Setting::CLEAR_NOTIFICATIONS_AUTO,
                'value' => 1,
                'telegram_notification_state' => 1,
                'description' => 'Deletes all notifications automatically each day (Manual deletion is possible)',
            ],
            [
                'name' => Setting::BACKUP_DATABASE_AUTO,
                'value' => 1,
                'telegram_notification_state' => 1,
                'description' => 'Automatically creates daily database backup',
            ],
            [
                'name' => Setting::ANDROID_VERSION,
                'value' => 1,
                'description' => 'Android mobile application build version',
            ],
            [
                'name' => Setting::IOS_VERSION,
                'value' => 1,
                'description' => 'IOS mobile application build version',
            ]
        ];

        foreach ($values as $value){
            Setting::create($value);
        }
    }
}
