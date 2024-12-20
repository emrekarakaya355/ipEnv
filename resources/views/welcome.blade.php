<x-layout>
        <div class="lg:w-6/12 xl:w-3/12 px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white rounded-lg mb-6 xl:mb-0 shadow-lg">
                <div class="flex-auto p-4">
                    <canvas id="deviceStatusChart" ></canvas>

                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                            <h5 class="text-blueGray-400 uppercase font-bold text-xs">Traffic</h5>
                            <span class="font-bold text-xl">350,897</span>
                        </div>
                        <div class="relative w-auto pl-4 flex-initial">
                            <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500"><i class="far fa-chart-bar"></i></div>
                        </div>
                    </div>
                    <p class="text-sm text-blueGray-500 mt-4"><span class="text-emerald-500 mr-2"><i class="fas fa-arrow-up"></i> 3.48%</span><span class="whitespace-nowrap">Since last month</span></p>
                </div>
            </div>
        </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('deviceStatusChart').getContext('2d');
        const data = {
            labels: ['Aktif Cihazlar', 'Pasif Cihazlar',"test","yok"],
            datasets: [{
                data: [20, 30,50,10],
                backgroundColor: ['#4CAF50', '#F44336','black','blue']
            }]
        };

        const deviceStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: data
        });
    </script>
</x-layout>
