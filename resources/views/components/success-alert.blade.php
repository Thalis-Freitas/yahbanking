@if(session('msg'))
<div class="bg-green-700 text-white p-4 rounded font-bold mb-10 mx-6 sm:mx-0">
    {{ session('msg') }}
</div>
@endif
