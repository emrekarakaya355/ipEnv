<th scope="col" class="px-6 py-3 text-center font-bold uppercase tracking-wider border-l border-gray-300">
    <div class="flex items-center justify-between">
        <span >{{ $title }}</span>
        <div class="flex items-center space-x-4">
            <button type="button" onclick="toggleFilter('{{ $filterName }}_header')">
                <img src="{{ Vite::asset('resources/images/filter.svg') }}" alt="Temizle" class="h-4 w-4">
            </button>

            <button type="button" onclick="clearFilter('{{ $filterName }}_headerInput')" class="flex items-center space-x-1 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V1M12 23v-3m9.546-7.868a9.986 9.986 0 00-1.986-3.658M2.454 9.132A9.986 9.986 0 004.44 12c0 1.636.371 3.184 1.04 4.57M15.34 20.456A9.986 9.986 0 0012 21c-5.523 0-10-4.477-10-10S6.477 1 12 1c4.526 0 8.454 3.02 9.618 7.034" />
                </svg>
                <img src="{{ Vite::asset('resources/images/reload.svg') }}" alt="reload" class="h-4 w-4 animate-spin">

            </button>

            <!-- Sorting icons -->
            <a href="{{ request()->fullUrlWithQuery(['sort' => strtolower($filterName), 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                <img src="{{ Vite::asset('resources/images/sort.svg') }}" alt="sort" class="h-4 w-4 ">

            </a>
        </div>
    </div>
    <div id="{{ $filterName }}_header"  class="hidden mt-2 filter-input-container">
        <input type="text" id="{{ $filterName }}_headerInput" name="{{ $filterName }}" value="{{ request($filterName) }}" placeholder="Filtrele..."
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
               onkeydown="submitAllForms(event)">
    </div>
</th>

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
                filters[input.name] = input.value; // Her inputun değerini nesneye ekle
            });

            // Tüm filtre değerlerini bir query string'e dönüştür
            const queryString = new URLSearchParams(filters).toString();

            // Sayfayı yeni query string ile güncelle
            window.location.href = `{{ request()->url() }}?${queryString}`; // Sayfayı güncelle
        }
    }


</script>
