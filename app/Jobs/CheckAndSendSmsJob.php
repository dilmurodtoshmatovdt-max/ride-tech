<?php

namespace App\Jobs;

use App\Constants\Settings;
use App\Services\Telegram\TelegramService;
use App\Services\Sms\SmsService;
use App\Repositories\Setting\SettingRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckAndSendSmsJob implements ShouldQueue
{
    use Queueable;
    public $tries = 1;
    /**
     * Create a new job instance.
     */
    public function __construct(public $requestData)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $settingRepository = app()->make(SettingRepository::class);

        //TODO SELECT FOR UPDATE
        $smsParams = $settingRepository->selectByName(Settings::SMS_PARAMS);
        //TODO add check gradation
        if ($smsParams['balance'] / $smsParams['price'] < $smsParams['limit_for_notification']) {
            $telegramService = app()->make(TelegramService::class);
            $message = str_replace('#balance', $smsParams['balance'], $smsParams['notification']['notification_template']);
            $message = str_replace('#limit',(int)round($smsParams['balance'] / $smsParams['price'],0), $message);
            $telegramService->send($smsParams['notification']['telegram']['chat_id'], $message);

            $smsService = app()->make(SmsService::class);
            $smsService->checkBalance();
            $smsParams = $settingRepository->selectByName(Settings::SMS_PARAMS);
        }

        if ($smsParams['balance'] < $smsParams['price']) {
            dispatch((new static($this->requestData))->delay($smsParams['delay_in_second']));
        } else {
            SmsSendJob::dispatch($this->requestData);
        }

    }
}
