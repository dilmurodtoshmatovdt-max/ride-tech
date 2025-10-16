<?php

namespace App\Jobs;

use App\Services\Sms\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SmsSendJob implements ShouldQueue
{
    use Queueable;

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
        $smsService = app()->make(SmsService::class);
        $result = $smsService->sendSms($this->requestData);
        // if(!$result){
        //     dispatch((new static($this->requestData))->delay(60));
        // }
        CheckSmsStatusJob::dispatch($result['response']->txn_id, $result['response']->msg_id, $result['log_id']);
    }
}
