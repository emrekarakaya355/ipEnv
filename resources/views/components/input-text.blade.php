<div>
    <label class="text-sm font-medium text-gray-500">{{ $label }}</label>
    <p contenteditable="false"
       id="{{ $id }}"
       class="px-3 py-2 h-12 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-2 w-full"
       data-name="{{ $dataName }}"
       data-value="{{ $value }}"
       style="max-width: 1200px; white-space: nowrap; overflow: auto; "
    >{{ $value  }}
    </p>
</div>
