<div class="max-w-md mx-auto mt-10">
    <form wire:submit.prevent="login" class="space-y-6">

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email"
                   wire:model.defer="form.email"
                   class="mt-1 block w-full border-gray-300 rounded-md"
                   required autofocus>
            @error('form.email')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password"
                   wire:model.defer="form.password"
                   class="mt-1 block w-full border-gray-300 rounded-md"
                   required>
            @error('form.password')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="form.remember" class="mr-2">
            <span class="text-sm text-gray-600">Remember me</span>
        </div>

        <button type="submit"
                class="w-full bg-black text-white py-2 rounded">
            Log in
        </button>
    </form>
</div>
