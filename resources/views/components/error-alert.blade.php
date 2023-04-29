@if($errors->any() && $message)
<div class="bg-red-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
    {{ $message }}
</div>
@endif
