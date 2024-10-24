<div class="lg:w-6/12 xl:w-3/12 px-4">
    <div class="relative flex flex-col min-w-0 break-words bg-white rounded-lg mb-6 xl:mb-0 shadow-lg">
        <div class="p-4">
            <h5 class="text-blueGray-400 uppercase font-bold  text-center">{{$title}} </h5>
            <div style="width: 100%; height: 300px;">
                <canvas id="{{ $id }}" style="width: 100%; height: 100%;"></canvas>
            </div>

            <div class="flex flex-wrap mt-4">
                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">Toplam </h5>
                    <span class="font-bold text-xl">{{$total}}</span>
                </div>
                <div class="relative w-auto pl-4 flex-initial">
                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                        <i class="far fa-chart-bar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('{{ $id }}').getContext('2d');
            const chartData = {!! json_encode($chartData) !!}; // Use JSON to pass data
            const options = {!! json_encode($options) !!}; // Use JSON to pass options

            const dynamicChart = new Chart(ctx, {
                type: '{{ $type }}', // Dynamic chart type
                data: chartData,
                options: options // Include options if provided
            });
        });
    </script>

