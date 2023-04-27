<div class="w-full mb-6">
    <label for="name" class="block text-white mb-2">Nome</label>
    <input type="text" class="w-full rounded" name="name" value="{{ old('name', isset($client) ? $client->name : '') }}">
    @error('name')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
<div class="w-full mb-6">
    <label for="last_name" class="block text-white mb-2">Sobrenome</label>
    <input type="text" class="w-full rounded" name="last_name" value="{{ old('last_name', isset($client) ? $client->last_name : '') }}">
    @error('last_name')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
<div class="w-full mb-6">
    <label for="email" class="block text-white mb-2">E-mail</label>
    <input type="text" class="w-full rounded" name="email" value="{{ old('email', isset($client) ? $client->email : '') }}">
    @error('email')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
<div class="w-full mb-6">
    <label for="avatar" class="block text-white font-bold mb-2">Avatar</label>
    <div class="relative">
        <input type="file" class="opacity-0 absolute inset-0 w-full h-full" name="avatar" id="avatar">
        <div class="flex items-center justify-center w-full h-12 border rounded hover:bg-gray-100">
            <span class="text-gray-500 font-semibold">Selecionar arquivo</span>
        </div>
    </div>
    @error('avatar')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
