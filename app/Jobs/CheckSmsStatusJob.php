<?php

namespace App\Jobs;

use App\Constants\IntegrationLogStatuses;
use App\Constants\OsonSmsMessageStatuses;
use App\Constants\Settings;
use App\Repositories\IntegrationLog\IntegrationLogRepository;
use App\Repositories\Setting\SettingRepository;
use App\Services\Sms\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckSmsStatusJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(public $txnId, public $msgId, public $logId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $smsService = app()->make(SmsService::class);
        $settingRepository = app()->make(SettingRepository::class);
        $smsParams = $settingRepository->selectByName(Settings::SMS_PARAMS);
        $result = $smsService->checkSms($this->txnId, $this->msgId);
        $status = null;
        $inProcess = false;
        if(in_array($result->message_state_code,[OsonSmsMessageStatuses::ENROUTE, OsonSmsMessageStatuses::ACCEPTED])){
            $inProcess = true;
            $status = IntegrationLogStatuses::IN_PROCESS;
        }
        if($result->message_state_code == OsonSmsMessageStatuses::DELIVERED){
            $status = IntegrationLogStatuses::SENDED;
        }
        if(in_array($result->message_state_code, [OsonSmsMessageStatuses::EXPIRED, OsonSmsMessageStatuses::DELETED, OsonSmsMessageStatuses::UNDELIVERABLE,OsonSmsMessageStatuses::UNKNOWN, OsonSmsMessageStatuses::REJECTED])){
            $status = IntegrationLogStatuses::ERROR;
        }

        $integrationLogRepository = app()->make(IntegrationLogRepository::class);
        $integrationLogRepository->update([
            'integration_log_status_id' => $status
        ], $this->logId);

        if($inProcess){
            //$this->release(60);
            dispatch((new static($this->txnId, $this->msgId, $this->logId))->delay($smsParams['delay_in_second']));
        }
    }
}
