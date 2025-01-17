<x-layout>
    @section('title','Script Düzenle')
    <section>
    <div class="flex-1">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('scripts/'.$script->id) }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-between">
                        <div class="flex space-x-4">
                            <div class="mb-3">
                                <label for="name">Adı</label>
                                <input type="text" name="name" value="{{ $script->name }}" required class="form-control" />

                            </div>
                            <div class="overflow-x-auto">
                                <label for="script">Script</label>
                                <input type="text" name="script" value="{{ $script->script }}" required class="form-control" />

                            </div>
                        </div>
                        <div class="mb-3">
                            <x-primary-button type="submit" class="btn btn-primary"> Save </x-primary-button>
                            <x-danger-button type="button" onclick="window.location.href='{{ route('scripts.index') }}'">Geri Dön </x-danger-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    </section>

</x-layout>
