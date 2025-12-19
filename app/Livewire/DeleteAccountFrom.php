<?php

namespace App\Livewire;

use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteAccountFrom extends Component
{
    public function save(): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => session()->get('ipClient'),
                'url' => request()->url(),
            ])
            ->event('onClick')
            ->log('Le client a supprimé son compte');
    }

    public function render(): View
    {
        return view('livewire.delete-account-from');
    }
}
