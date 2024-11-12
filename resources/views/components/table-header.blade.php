<div class="flex space-x-4 justify-center">
        <a href="{{ request()->fullUrlWithQuery(['sort' => strtolower($filterName), 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
            <div >{{ $title }}</div>
        </a>
        @isset($title)
        <button type="button" onclick="toggleFilter(`{{ $filterName }}_header`)">
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
                   onkeydown="submitAllForms(event,`{{request()->url()}}`)">
            <button type="button" onclick="clearFilter('{{ $filterName }}_headerInput',`{{request()->url()}}`)" class="flex items-center text-gray-500 hover:bg-amber-400">
                <img src="{{ Vite::asset('resources/images/reload.svg') }}" alt="reload" class="h-8 w-8">
            </button>
        </div>
    </div>

