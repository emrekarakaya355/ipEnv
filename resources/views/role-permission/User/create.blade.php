<x-layout>
    <x-slot name="heading">Kullanıcı Ekle</x-slot>

    <div class="w-full p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">
            Kullanıcı Ekle
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
                        <form action="{{ url('users') }}" method="POST"  class="bg-white shadow-lg rounded-2xl p-8 form-container">
                            @csrf

                            <div class="mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" id="name" class="form-control ml-7" value="{{old('name')}}" />
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control ml-8" value="{{ old('email') }}" />
                            </div>


                            <div class="mb-3">
                                <div class="relative">

                                <label for="password">Password</label>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control ml-1"
                                        value="{{ old('password') }}"
                                    />
                                    <button type="button" id="togglePassword" >
                                        Göster
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="roles">Roles</label>
                                <div id="roles" class="form-control">
                                    @foreach ($roles as $role)
                                        <div class="mb-2">
                                            <input
                                                type="checkbox"
                                                name="roles[]"
                                                value="{{ $role }}"
                                                id="role_{{ $role }}"
                                                {{ in_array($role, old('roles', [])) ? 'checked' : '' }}
                                            />
                                            <label for="role_{{ $role }}" class="ml-2">
                                                {{ $role }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-primary-button type="submit" class="btn btn-primary">Save</x-primary-button>
                                <x-danger-button type="button" onclick="window.location.href='{{ route('users.index') }}'">Back</x-danger-button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Butonun içeriğini 'Göster' ve 'Gizle' arasında değiştir
            this.textContent = type === 'password' ? 'Göster' : 'Gizle';
        });
    </script>
</x-layout>
