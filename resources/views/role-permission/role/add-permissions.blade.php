<x-layout>
    <x-slot name="heading">Yetkileri Düzenle</x-slot>

    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">
            Yetkileri Düzenle
        </h1>
        <h4 class="text-2xl font-semibold"> Role : {{ $role->name }}
        </h4>
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

                        <form action="{{ url('roles/'.$role->id.'/give-permissions') }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                @error('permission')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <label for="">Yetkiler</label>

                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-2">
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    name="permission[]"
                                                    value="{{ $permission->name }}"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked':'' }}
                                                />
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="mb-3">
                                <x-primary-button type="submit" class="btn btn-primary">Update</x-primary-button>
                                <x-danger-button type="button" onclick="window.location.href='{{ route('roles.index') }}'">Geri Dön </x-danger-button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
</x-layout>
