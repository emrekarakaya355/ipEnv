<!-- Sidebar -->
<div class="bg-gray-900 text-gray-300 w-full md:w-64 h-screen">
    <div class="flex items-center justify-center mt-10">
        <span class="text-2xl font-bold">BTEnvanteri</span>
    </div>
    <nav class="mt-10">
        <a href="#" class="flex items-center py-2 px-8 bg-gray-700 text-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
            </svg>
            <span class="mx-4 font-medium">Anasayfa</span>
        </a>
        <div x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="flex items-center py-2 px-8 w-full text-left focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                </svg>
                <span class="mx-4 font-medium">Donanımlar</span>
                <svg class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="bg-gray-800">
                <a href="/devices" class="block py-2 px-8 text-gray-300 hover:bg-gray-700">Ağ Cihazları</a>
                <a href="/devices/switches" class="block py-2 px-8 text-gray-300 hover:bg-gray-700 pl-8">Switchler</a>
                <a href="/devices/access-points" class="block py-2 px-8 text-gray-300 hover:bg-gray-700 pl-8">Access Pointler</a>
                <a href="#" class="block py-2 px-8 text-gray-300 hover:bg-gray-700">Cluster/Stack</a>
                <a href="#" class="block py-2 px-8 text-gray-300 hover:bg-gray-700">Bilgisayarlar</a>
                <a href="/locations" class="block py-2 px-8 text-gray-300 hover:bg-gray-700">Yerler</a>
                <!-- Add more submenu items here -->
            </div>
        </div>
        <!-- Add more menu items here -->
    </nav>
</div>
