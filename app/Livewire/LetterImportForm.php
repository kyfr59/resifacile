<?php

namespace App\Livewire;

use App\Actions\Letter\AddDocumentsAction;
use App\Actions\Letter\RemoveDocumentAction;
use App\Contracts\Cart;
use App\Enums\DocumentType;
use App\Helpers\MimeTypes;
use App\Traits\WithPreviewFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;

class LetterImportForm extends Component
{
    use WithFileUploads;

    public mixed $files = [];

    protected $messages = [
        'files' => "Vous n'avez pas importé de fichiers",
    ];

    protected function rules(): array
    {
        $rules = [
            'files.*' => 'required|file|mimetypes:' . MimeTypes::authorized() . '|mimes:pdf,bin',
        ];

        $documents = App::make(Cart::class)->getDocuments();

        if($documents->count() < 1) {
            $rules['files'] = 'required';
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->canRemove = true;
    }

    public function removeStoredDocument(array $source): void
    {
        if(key_exists('index', $source)) {
            (new RemoveDocumentAction())->handle($source['index']);
        }
    }

    public function save(): RedirectResponse|Redirector
    {
        $documents = App::make(Cart::class)->getDocuments();

        $this->validate();

        if(count($this->files)) {
            (new AddDocumentsAction())->handle($this->files);
        }

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url()
            ])
            ->event('onClick')
            ->log('Le client poursuis sa commande');

        return redirect()->route('frontend.letter.recipient');
    }

    public function render()
    {
        return view('livewire.letter-import-form', [
            'documents' => App::make(Cart::class)->getDocuments(),
            'has_files' => App::make(Cart::class)->getOrder()->has_files,
        ]);
    }
}
