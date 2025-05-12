<?php

namespace App\Livewire\User;

use Flux\Flux;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class ListUser extends Component
{
    public ?string $searchTerm = null; 
    public ?string $sortColumn = 'created_at'; 
    public ?string $sortDirection = 'desc'; 
    public $userId = null; 

    #[Computed]
    public function listUser()
    {
        return User::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->sortColumn, function ($query) {
                $query->orderBy($this->sortColumn, $this->sortDirection);
            })
            ->paginate(10);
    }

    public function sortBy($column)
    {
        $this->sortDirection = $this->sortColumn === $column
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortColumn = $column;
    }

    public function edit($userId)
    {
        $this->dispatch('edit-user', $userId);
    }

    #[On('user-created')]
    #[On('user-updated')]
    public function listUserUpdated()
    {
        unset($this->listUser);
    }

    #[On('delete-user')]
    public function confirmDelete($id)
    {
        $this->userId = $id;
        Flux::modal('delete-user')->show();
    }

    public function delete()
    {
        $user = User::findOrFail($this->userId);

        if ($user) {
            $user->delete();
            Flux::modal('delete-user')->close();
        } else {
            // session()->flash('error', 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.user.list-user');
    }
}
