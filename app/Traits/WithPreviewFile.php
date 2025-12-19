<?php

namespace App\Traits;

use App\Actions\Letter\RemoveDocumentAction;

trait WithPreviewFile
{
    /**
     * @var bool
     */
    public bool $showPreview = false;

    /**
     * @var string|null
     */
    public ?string $previewUrl = null;

    /**
     * @var string|null
     */
    public ?string $previewName = null;

    /**
     * @var bool
     */
    public bool $canRemove = false;

    /**
     * @param int $id
     * @return void
     */
    public function show(int $id, string $name): void
    {
        $this->previewUrl = route('frontend.letter.preview', ['id' => $id]);
        $this->previewName = $name;
        $this->showPreview = true;
    }

    /**
     * @param int $id
     * @return void
     */
    public function remove(int $id): void
    {
        (new RemoveDocumentAction())->handle($id);
    }

    /**
     * @return void
     */
    public function hidden(): void
    {
        $this->previewUrl = null;
        $this->previewName = null;
        $this->showPreview = false;
    }
}
