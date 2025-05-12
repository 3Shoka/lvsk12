<?php

namespace App\Livewire\User;

use Flux\Flux;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class EditUser extends Component
{

    public $userId;

    #[Validate('required', message: 'Nama harus diisi')]
    #[Validate('min:4', message: 'Nama terlalu pendek')]
    #[Validate('max:255', message: 'Nama terlalu panjang')]
    public string $name;

    #[Validate('required', message: 'Email harus diisi')]
    #[Validate('email', message: 'Email tidak valid')]
    #[Validate('max:255', message: 'Email terlalu panjang')]
    public string $email;

    #[Validate('nullable')]
    #[Validate('min:8', message: 'Password terlalu pendek')]
    public ?string $password = null;

    #[Validate('nullable')]
    #[Validate('same:password', message: 'Konfirmasi password tidak sesuai')]
    public ?string $password_confirmation = null;

    #[On('edit-user')]
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        Flux::modal('edit-user')->show();
    }

    public function save()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);
        if ($this->password) {
            $this->validate([
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
            ]);
        }
        if ($this->email != $user->email) {
            $this->validate([
                'email' => 'required|email|max:255|unique:users,email',
            ]);
        }
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $this->dispatch('user-updated')->to(ListUser::class);
        $this->reset();
        Flux::modal('edit-user')->close();
    }

    public function render()
    {
        return view('livewire.user.edit-user');
    }
}
