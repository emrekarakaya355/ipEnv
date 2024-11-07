    <div class="flex space-x-4">
        <a href="{{ request()->fullUrlWithQuery(['sort' => strtolower($filterName), 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
            <div >{{ $title }}</div>
        </a>
        @isset($title)
        <button type="button" onclick="toggleFilter('{{ $filterName }}_header')">
            <img src="{{ Vite::asset('resources/images/filter.svg') }}" alt="Filtrele" class="h-4 w-4 hover:bg-amber-400">
        </button>
        @endisset
        <!--button type="button" onclick="clearFilter('{{ $filterName }}_headerInput')" class="flex items-center space-x-1 text-gray-500 hover:text-gray-700">
            <img src="{{ Vite::asset('resources/images/reload.svg') }}" alt="reload" class="h-4 w-4">
        </button-->

            <!-- Sorting icons -->
            <!--img src="{{ Vite::asset('resources/images/sort.svg') }}" alt="sort" class="h-4 w-4 "-->
    </div>
    <div id="{{ $filterName }}_header"  class="hidden mt-2 filter-input-container">
        <div class="flex space-x-2">

            <input type="text" id="{{ $filterName }}_headerInput" name="{{ $filterName }}" value="{{ request($filterName) }}" placeholder="Filtrele..."
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm "
                   onkeydown="submitAllForms(event)">
            <button type="button" onclick="clearFilter('{{ $filterName }}_headerInput')" class="flex items-center text-gray-500 hover:bg-amber-400">
                <img src="{{ Vite::asset('resources/images/reload.svg') }}" alt="reload" class="h-8 w-8">
            </button>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterInputs = document.querySelectorAll('.filter-input-container');

        filterInputs.forEach(container => {
            const input = container.querySelector('input');
            if (input && input.value) {
                container.classList.remove('hidden'); // Eğer inputta değer varsa 'hidden' sınıfını kaldır
            }
        });
    });
    function toggleFilter(filterId) {
        const filterInputContainer = document.getElementById(filterId);
        if (filterInputContainer.classList.contains('hidden')) {
            filterInputContainer.classList.remove('hidden');
        } else {
            filterInputContainer.classList.add('hidden');
        }
    }
    function clearFilter(filterId) {
        const filterInput = document.getElementById(filterId);
        if (filterInput) {
            filterInput.value = ''; // Input değerini sıfırla
            submitAllForms({key: 'Enter'}); // Tüm formları gönder
        }
    }

    function submitAllForms(event) {
        if (event && event.key === 'Enter') {
            const filters = {}; // Filtre değerlerini tutacak bir nesne
            const inputs = document.querySelectorAll('.filter-input-container input'); // Tüm filtre inputlarını seç
            inputs.forEach(input => {
                if (input.value.trim() !== '') { // Boş olmayan inputları ekle
                    filters[input.name] = input.value; // Her inputun değerini nesneye ekle
                }
            });
            // Tüm filtre değerlerini bir query string'e dönüştür
            const queryString = new URLSearchParams(filters).toString();
            // Sayfayı yeni query string ile güncelle
            window.location.href = `{{ request()->url() }}?${queryString}`; // Sayfayı güncelle
        }
    }
</script>
