<?php

namespace OZiTAG\Tager\Backend\Mail\Console;

use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Console\Command;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;
use OZiTAG\Tager\Backend\Mail\TagerMail;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;

class ResendSkipMailCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'tager:mail-resend-skip {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Try to resend skipped emails for last {days} days';

    /**
     * @var MailLogRepository
     */
    private $repository;

    /**
     * @var TagerMail
     */
    private $tagerMail;

    /** @var Storage */
    private $filestorage;

    public function __construct(MailLogRepository $repository, TagerMail $tagerMail, Storage $filestorage)
    {
        parent::__construct();

        $this->repository = $repository;

        $this->tagerMail = $tagerMail;

        $this->filestorage = $filestorage;
    }

    public function handle()
    {
        $days = (int)$this->argument('days');

        $items = $this->repository->findSkipForLastDays($days);
        $count = count($items);

        $this->log('Found ' . $count . ' items');
        foreach ($items as $ind => $item) {
            $this->log('Log Item ' . ($ind + 1) . ' / ' . $count . ': ID ' . $item->id);

            $attachments = new TagerMailAttachments();
            if ($item->attachments) {
                $attachmentsJson = json_decode($item->attachments, true);
                if (!empty($attachmentsJson)) {
                    foreach ($attachmentsJson as $attachmentJson) {
                        $path = $attachmentJson['path'];
                        if (!is_file($path)) {
                            continue;
                        }

                        $file = $this->filestorage->createFromLocalFile($path);
                        if ($file) {
                            $attachments->addFile($file);
                        }
                    }
                }
            }

            $this->tagerMail->sendMail($item->recipient, $item->subject, $item->body, $attachments);

            $this->log('OK');
        }
    }
}
