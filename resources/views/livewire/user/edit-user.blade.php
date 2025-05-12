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

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>
</div>