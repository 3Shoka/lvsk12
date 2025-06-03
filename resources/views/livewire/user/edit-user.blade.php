<div>
    <flux:modal name="edit-user" variant="flyout">
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">Update user</flux:heading>
                <flux:text class="mt-2">Fill required user information.</flux:text>
            </div>

            <flux:input wire:model="name" label="Name" placeholder="User name" />
            <flux:input wire:model="email" label="Email" type="email" placeholder="User email" />
            <flux:input wire:model="password" label="Password" type="password" placeholder="User password" />
            <flux:input wire:model="password_confirmation" label="Confirm Password" type="password"
                placeholder="Confirm password" />

            <flux:field>
                <flux:label>Hobbies</flux:label>
                <x-tom-select wire:model="hobbies" class="w-full" x-init="$el.hobbies = new TomSelect($el, {
                    plugins: ['remove_button'],
                    valueField: 'hobbies    ',
                    labelField: 'hobbies',
                    searchField: 'hobbies',
                })"
                    @set-hobbies.window="event.detail.hobbies.forEach(hobby => $el.tomselect.addItem(hobby))"
                    @reset-tom.window="$el.hobbies.clear()" multiple>
                    <option value="">Select hobbies</option>
                    @foreach (\App\Helpers\HobbiesHelper::list() as $hobby)
                        <option value="{{ $hobby }}">{{ $hobby }}</option>
                    @endforeach
                </x-tom-select>
                <flux:error name="hobbies" />
            </flux:field>

            <flux:field>
                <flux:label>User refs</flux:label>
                <x-tom-select wire:model="user_ref" class="w-full" x-init="$el.user_ref = new TomSelect($el, {
                    plugins: ['remove_button'],
                    valueField: 'id',
                    labelField: 'name',
                    searchField: 'name',
                    load: (query, callback) => {
                        if (!query.length) return callback([]);
                        $wire.listUsers(query).then(users => {
                            callback(users);
                        }).catch(error => {
                            console.error('Error loading users:', error);
                            callback([]);
                        });
                    },
                })" 
                @reset-tom.window="$el.user_ref.clearOptions()"
                @set-user-ref.window="
                    $el.user_ref.addOption(event.detail.data);
                    $el.user_ref.setValue(event.detail.id);"
                multiple></x-tom-select>
                <flux:error name="user_refs" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
