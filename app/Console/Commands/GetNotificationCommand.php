<?php

namespace App\Console\Commands;

use App\DataTransferObjects\NotificationData;
use App\Jobs\ProcessNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SFTP;

class GetNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importation des notifications Maileva';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sftp = new SFTP(
            config('filesystems.disks.sftp.host'),
            config('filesystems.disks.sftp.port'),
            10
        );

        if($sftp->login(config('filesystems.disks.sftp.username'), config('filesystems.disks.sftp.password'))) {
            $sftp->chdir(config('maileva.notificationsFolder'));
            $files = $sftp->nlist();

            foreach ($files as $file) {
                if($file !== '.' && $file !== '..') {
                    $xml = $sftp->get($file);

                    $path = 'notifications_processed' . DIRECTORY_SEPARATOR . $file;

                    $bool = Storage::put($path, $xml);

                    if($bool) {
                        if(last(explode('.', $file)) === 'xml') {
                            ProcessNotification::dispatch($path, $file);
                        }

                        $sftp->delete($file, false);
                    }
                }
            }

            $sftp->disconnect();
        }

    }
}
