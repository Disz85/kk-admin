<?php

namespace App\Jobs;

use App\Helpers\Import\HtmlToEditorJsConverter;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertHtmlToEditorJs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Model $model)
    {
        $this->model = $model->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(HtmlToEditorJsConverter $converter): void
    {
        try {
            $this->model->description = $converter->convert($this->model->legacy_description, $this->model->getTable());
            $this->model->timestamps = false;
            $this->model->save();
        } catch (Exception $exception) {
            $this->fail($exception);
        }
    }
}
