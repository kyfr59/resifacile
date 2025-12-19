<?php

namespace App\Livewire;

use App\Actions\Letter\AddTemplateAction;
use App\Actions\Letter\GetLetterEditable;
use App\Contracts\Cart;
use App\DataTransferObjects\AddressData;
use App\Enums\AppType;
use App\Enums\DocumentType;
use App\Enums\PostageType;
use App\Helpers\Country;
use App\Helpers\MimeTypes;
use App\Models\Brand;
use App\Models\Template;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;

class LetterEditForm extends Component
{
    use WithFileUploads;

    protected $listeners = ['templateShow', 'letterShow'];

    public Template|Brand|null $product = null;

    public ?array $template = null;
    public mixed $files = [];
    public ?string $from_city = null;
    public ?string $date = null;
    public ?string $object = null;
    public ?string $name = null;
    public ?array $signature = null;
    public ?string $reference = null;
    public bool $importFiles = false;

    protected $messages = [
        'template.model.text.required' => "N'oublier pas de rédigier votre courrier dans la zone ci-dessus !",
        'template.model.json.required' => "N'oublier pas d'éditer votre courrier et remplacer les éléments en gras !"
    ];

    protected $validationAttributes = [
        'from_city' => 'localité',
    ];

    protected function rules(): array
    {
        $rule_files = 'file|mimetypes:' . MimeTypes::authorized();

        $letter = [
            'from_city' => 'required|string',
        ];

        return array_merge([
            //'template.model.text' => 'required|string',
            //'template.model.json' => 'required',
            'files.*' => $rule_files,
        ], $letter);
    }

    public function mount(): void
    {
        $document = App::make(Cart::class)
            ->getDocuments()
            ->filter(
                fn ($document) => $document->type === DocumentType::TEMPLATE || $document->type === DocumentType::HANDWRITE
            )->first();

        $order = App::make(Cart::class)->getOrder();
        $this->importFiles = $order->has_files;

        if($document) {
            $this->template = $document->model->toArray();
        }

        if(
            $this->template['is_new_type'] &&
            (
                empty($this->template['model']) ||
                $document
            )
        ) {
            $this->template['model'] = ['text' => $document?->letter ?? '', 'json' => $document?->model->model];
        } else {
            $this->template['model'] = (new GetLetterEditable)->handle(template: $this->template);
            $this->template['is_new_type'] = true;
        }

        $this->template['document_type'] = DocumentType::HANDWRITE->value;

        $fields = $this->template['group_fields'];
        $this->from_city = $fields['from_city'] ?? null;
        $this->date = $fields['date'] ?? now()->format( 'd/m/Y');
        $this->object = $fields['object'] ?? $this->object;
        $this->signature = $fields['signature'] ?? null;
        $this->reference = $fields['reference'] ?? null;
    }

    public function templateShow(Template $document): void
    {
        $this->object = $document->name;
        $this->template['model'] = (new GetLetterEditable)->handle(template: $document->model->toArray());
        $this->template['document_type'] = DocumentType::TEMPLATE->value;
    }

    public function autosave(): void
    {}

    public function save(): Redirector
    {
        $this->validate();

        $cart = App::make(Cart::class);

        $this->template["group_fields"] = [
            'from_city' => $this->from_city,
            'date' => $this->date,
            'object' => $this->object,
            'signature' => $this->signature,
            'reference' => $this->reference,
        ];

        (new AddTemplateAction)($this->template);

        if(AppType::TERMINATION_LETTER->value === config('site.type')) {
            App::make(Cart::class)->addPostageType(PostageType::REGISTERED_LETTER);
        }

        $order = $cart->getOrder();
        $order->has_files = $this->importFiles;
        $cart->addOrder($order);

        if($this->product) {
            $cart->addRecipients([
                $this->product->address,
            ]);
        }

        activity()
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url()
            ])
            ->event('onClick')
            ->log('Le client a modifié son modèle de résiliation');

        if($this->importFiles) {
            return redirect()->route('frontend.letter.import');
        }

        return redirect()->route('frontend.letter.recipient');
    }

    public function render(): View
    {
        return view('livewire.letter-edit-form', [
            'default_countries' => Country::default(),
            'countries' => Country::all(),
            'documents' => App::make(Cart::class)->getDocuments(),
        ]);
    }
}
