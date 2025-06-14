<?php

namespace App\Livewire\User;

use Flux\Flux;
use App\Models\User;
use Livewire\Component;
use App\Livewire\User\ListUser;
use Livewire\Attributes\Validate;

class CreateUser extends Component
{
    #[Validate('required', message: 'Nama harus diisi')]
    #[Validate('min:4', message: 'Nama terlalu pendek')]
    #[Validate('max:255', message: 'Nama terlalu panjang')]
    public string $name;

    #[Validate('required', message: 'Email harus diisi')]
    #[Validate('email', message: 'Email tidak valid')]
    #[Validate('max:255', message: 'Email terlalu panjang')]
    #[Validate('unique:users,email', message: 'Email sudah terdaftar')]
    public string $email;

    #[Validate('required', message: 'Password harus diisi')]
    #[Validate('min:8', message: 'Password terlalu pendek')]
    public string $password;

    #[Validate('required', message: 'Konfirmasi password harus diisi')]
    #[Validate('same:password', message: 'Konfirmasi password tidak sesuai')]
    public string $password_confirmation;

    #[Validate('required', message: 'Hobi harus diisi')]
    #[Validate('array', message: 'Hobi harus berupa array')]
    public array $hobbies = [];

    public array $user_ref = [];

    public function listUsers($query = null)
    {
        return User::query()
            ->select(['id', 'name', 'email'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate();
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'hobbies' => $this->hobbies,
            'user_ref' => $this->user_ref,
        ]);

        $this->dispatch('user-created')->to(ListUser::class);
        $this->dispatch('reset-tom');
        $this->reset();
        Flux::modal('create-user')->close();
    }

    public function render()
    {
        return view('livewire.user.create-user');
    }
}
