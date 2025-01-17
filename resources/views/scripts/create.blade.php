<x-layout>
    @section('title','Script Ekle')

    <div class="w-full ">

        <div class="card">
            <div class="card-body">
                <form action="{{ url('scripts') }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                    @csrf
                    <div class="flex justify-between">
                        <div class="mb-3">
                            <label for="">Adı</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   required/>
                        </div>
                        <div class="">
                            <label for="">Script</label>
                            <input type="text"
                                   name="script"
                                   class="form-control"
                                   required/>
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
</x-layout>
