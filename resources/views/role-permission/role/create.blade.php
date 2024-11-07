<x-layout>
    @section('title','Rol Ekle')

    <div class="w-full ">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('roles') }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                            @csrf
                            <div class="flex justify-between">
                                <div class="mb-3">
                                    <label for="">Role Name</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           required/>
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
