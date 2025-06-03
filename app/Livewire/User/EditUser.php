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

    public ?array $hobbies = null;
    
    public ?array $user_ref = null;

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

    #[On('edit-user')]
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        // set hobbies if exists
        $this->dispatch('reset-tom');
        $this->dispatch('set-hobbies', hobbies: $user->hobbies ?? []);
        $ref_selected = User::whereIn('id', $user->user_ref ?? [])
            ->get();
        $this->dispatch('set-user-ref', data: $ref_selected, id: $user->user_ref ?? []);
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
            'hobbies' => $this->hobbies,
            'user_ref' => $this->user_ref,
        ]);

        $this->dispatch('user-updated')->to(ListUser::class);
        $this->dispatch('reset-tom');
        $this->reset();
        Flux::modal('edit-user')->close();
    }

    public function render()
    {
        return view('livewire.user.edit-user');
    }
}
