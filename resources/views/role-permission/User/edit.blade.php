<x-layout>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                @if ($errors->any())
                    <ul class="alert alert-warning">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="card">

                    <div class="card-body">
                        <form action="{{ url('users/'.$user->id) }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="">Name</label>
                                <input type="text"
                                       class="form-control ml-7"
                                       value="{{ $user->name }}"
                                       readonly/>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="text"
                                       id="email"
                                       class="form-control ml-8"
                                       value="{{ $user->email }}"
                                       readonly />
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
                                                {{ in_array($role, $userRoles) ? 'checked' : '' }}
                                            />
                                            <label for="role_{{ $role }}" class="ml-2">
                                                {{ $role }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">
                                <x-primary-button type="submit" class="btn btn-primary">Update</x-primary-button>
                                <x-danger-button type="button" onclick="window.location.href='{{ route('users.index') }}'">Geri Dön</x-danger-button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
