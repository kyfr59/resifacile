<?php

namespace App\Traits;

use App\Actions\Letter\RemoveDocumentAction;

trait WithPopup
{
    /**
     * @var bool
     */
    public bool $showPopup = false;

    /**
     * @param int $id
     * @return void
     */
    public function showPopup(): void
    {
        $this->showPopup = true;
    }

    /**
     * @return void
     */
    public function hiddenPopup(): void
    {
        $this->showPopup = false;
    }
}
