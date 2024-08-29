<x-layout>
    <x-slot name="heading">Rol Ekle</x-slot>

    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">
            Role Ekle
        </h1>
        {{-- Hata veya başarılı işlem mesajları --}}
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                {{ session('error') }}

            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ url('roles/'.$role->id) }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-between">
                        <div class="mb-3">
                            <label for="">Role Name</label>
                            <input type="text" name="name" value="{{ $role->name }}" required class="form-control" />

                        </div>
                        <div class="mb-3">
                            <x-primary-button type="submit" class="btn btn-primary"> Save </x-primary-button>
                            <x-danger-button type="button" onclick="window.location.href='{{ route('roles.index') }}'">Geri Dön </x-danger-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
