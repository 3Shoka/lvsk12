<div>

    {{-- heading --}}
    <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">User</flux:heading>
        <flux:subheading size="lg" class="mb-6">List master data User</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- data tools --}}
    <div
        class="pb-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="sm:flex">
                <div
                    class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                    <div class="relative mt-1 lg:w-64 xl:w-96 flex items-center">
                        <flux:input size="sm" iconLeading="magnifying-glass" clearable
                            wire:model.live.debounce.250ms="searchTerm" placeholder="Search" class="w-sm me-2" />
                    </div>
                </div>
                <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                    <flux:modal.trigger name="create-user">
                        <flux:button size="sm" variant="outline" icon="user-plus" class="bg-">Create user
                        </flux:button>
                    </flux:modal.trigger>
                </div>
            </div>
        </div>
    </div>

    {{-- list data --}}
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-gray-100">
                            <tr class="border-b border-t border-gray-200">
                                <th scope="col" class="p-2 text-sm font-semibold text-left uppercase text-accent"
                                    wire:click="sortBy('name')">
                                    Nama
                                    @if ($sortColumn === 'name')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="p-2 text-sm font-semibold text-left uppercase text-accent"
                                    wire:click="sortBy('email')">
                                    Email
                                    @if ($sortColumn === 'email')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="p-2 text-sm font-semibold text-left uppercase text-accent"
                                    wire:click="sortBy('hobbies')">
                                    Hobbies
                                    @if ($sortColumn === 'hobbies')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th scope="col" class="p-2 text-sm font-semibold text-left uppercase text-accent"
                                    wire:click="sortBy('user_ref')">
                                    Referensi User
                                    @if ($sortColumn === 'user_ref')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>

                                <th scope="col" class="p-2 text-sm"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($this->listUser as $user)
                                <tr class="">
                                    <td class="p-2 text-base font-medium whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="p-2 text-base font-normal whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="p-2 text-base font-normal whitespace-nowrap">
                                        @if ($user->hobbies)
                                            @foreach ($user->hobbies as $hobby)
                                                <span
                                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">
                                                    {{ $hobby }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-base font-normal whitespace-nowrap">
                                        @if ($user->user_ref)
                                            @foreach ($user?->user_ref as $id)
                                                <span
                                                    class="inline-block px-2 py-1 mr-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">
                                                    {{ App\Models\User::find($id)?->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="p-2 space-x-2 whitespace-nowrap text-right">
                                        <flux:button size="sm" icon="pencil-square" iconVariant="mini"
                                            class="bg-blue-400! hover:bg-blue-500! text-white!"
                                            x-on:click="$dispatch('edit-user', { 'id': {{ $user->id }} })">
                                            Edit</flux:button>
                                        <flux:button
                                            x-on:click="$dispatch('delete-user', { 'id': {{ $user->id }} })"
                                            size="sm" variant="danger" icon="user-minus" iconVariant="mini">
                                            Hapus</flux:button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b">
                                    <td class="p-2 text-base font-medium text-center text-accent" colspan="3">Tidak
                                        ada data
                                        untuk ditampilkan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- pagination -->
        <div class="sticky bottom-0 right-0 items-center w-full py-4 bg-white border-t border-gray-200 ">
            {{ $this->listUser->links() }}
        </div>
    </div>

    {{-- modal create user --}}
    <livewire:user.create-user />

    {{-- modal edit user --}}
    <livewire:user.edit-user />

    {{-- modal delete user --}}
    <flux:modal name="delete-user" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete user?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this user.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Delete user</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
