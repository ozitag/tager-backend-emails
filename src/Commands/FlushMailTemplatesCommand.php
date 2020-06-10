<?php

namespace OZiTAG\Tager\Backend\Mail\Commands;

use Illuminate\Console\Command;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Seo\Models\SeoPage;
use OZiTAG\Tager\Backend\Seo\Repositories\SeoPageRepository;

class FlushMailTemplatesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tager:mail-flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync DB Mail templates with config';

    public function handle(MailTemplateRepository $repository)
    {
        $templates = config()->get('tager-mail.templates');
        if (!$templates) {
            return;
        }

        foreach ($templates as $template => $data) {
            $model = $repository->findByTemplate($template);

            if (!$model) {
                $model = $repository->createModelInstance();
                $model->template = $template;
            }

            if ($model->changed_by_admin == false) {
                if (isset($data['recipients'])) {
                    if (is_array($data['recipients'])) {
                        $model->recipients = implode(',', $data['recipients']);
                    } else if (is_string($data['recipients'])) {
                        $model->recipients = $data['recipients'];
                    }
                }

                $model->subject = $data['subject'] ?? '';
                $model->value = $data['value'] ?? '';
            }

            $model->name = $data['name'];
            $model->save();
        }
    }
}
