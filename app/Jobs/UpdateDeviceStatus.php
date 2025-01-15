<?php

namespace App\Jobs;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDeviceStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $device;
    /**
     * Create a new job instance.
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    public function uniqueId()
    {
        return $this->device->id;
    }
    /**
     * Execute the job.
     */
        public function handle(): void
        {
            $this->device->updateStatusByPing($this->device->ip_address);
            $this->device->save();
        }
}
