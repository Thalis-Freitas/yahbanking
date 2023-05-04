<div class="w-full mb-6">
    <label for="abbreviation" class="block text-white mb-2">Sigla</label>
    <input type="text" class="w-full rounded" name="abbreviation" value="{{ old('abbreviation', isset($investment) ? $investment->abbreviation : '') }}">
    @error('abbreviation')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
<div class="w-full mb-6">
    <label for="name" class="block text-white mb-2">Nome Comercial</label>
    <input type="text" class="w-full rounded" name="name" value="{{ old('name', isset($investment) ? $investment->name : '') }}">
    @error('name')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
<div class="w-full mb-6">
    <label for="description" class="block text-white mb-2">Descrição</label>
    <textarea id="description" class="w-full rounded h-24 resize-none" name="description">{{ old('description', isset($investment) ? $investment->description : '') }}</textarea>
    @error('description')
        <span class="text-red-600"> {{ $message }} </span>
    @enderror
</div>
