<nav class="sidebar bg-gray-900 text-white fixed h-full p-2">
    <div class="sidebar fixed top-0 bottom-0 p-2 flex flex-col bg-gray-900">
        <div class="text-center text-gray-100">
            <div class=" mb-8"></div>
            <x-application-logo class="w-32 h-32 mx-auto" />
            <div class=" mb-8"></div>

            <div class="divider"></div>
        </div>

        <!-- Main Menu -->
        <a href="/dashboard" class="menu-item">
            <i class="fa-solid fa-house mr-4"></i>
            <span >Ana Sayfa</span>
        </a>

        @can('view device')
            <div class="divider"></div>
            <div class="menu-item flex" onclick="toggleSubmenu()">
                <i class="fa-solid fa-server mr-4"></i>
                <a href="/devices" class="flex-grow">Cihazlar</a>
                <span class="submenu-arrow" id="arrow">
                    <i class="bi bi-chevron-down"></i>
                </span>
            </div>

            <!-- Submenu -->
            <div id="submenu" class="submenu hidden">
                @can('view deviceInfo')
                    <a href="/devices/orphans" class="submenu-item">Öksüz cihazlar</a>
                @endcan
            </div>
        @endcan

        @can('view location')
            <div class="divider"></div>
            <a href="/locations" class="menu-item">
                <i class="fa-solid fa-map-location mr-4"></i>
                <span class="text-[15px] ">Yer Bilgisi</span>
            </a>
        @endcan

        @can('view deviceType')
            <div class="divider"></div>
            <a href="/device_types" class="menu-item">
                <i class="fa-solid fa-microchip mr-4"></i>
                <span class="text-[15px] ">Cihaz Tipleri</span>
            </a>
        @endcan

        <!-- Bottom Section -->
        <div class="divider"></div>
        <div class="flex flex-col mt-auto space-y-2">
            @can('view user')
                <a href="/users" class="menu-item">
                    <i class="fa-solid fa-users"></i>
                    <span class="text-[15px] ml-4">Kullanıcılar</span>
                </a>
            @endcan

            @can('view permission')
                    <div class="divider"></div>
                <a href="/permissions" class="menu-item">
                    <i class="fa-solid fa-lock"></i>
                    <span class="text-[15px] ml-4">Permissions</span>
                </a>
            @endcan

            @can('view role')
                    <div class="divider"></div>
                <a href="/roles" class="menu-item">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="ml-4">Roles</span>
                </a>
            @endcan
                <div class="divider"></div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="menu-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="text-[15px] ml-4">Logout</span>
                </a>
            </form>
        </div>
    </div>
</nav>

@vite('resources/css/sidebar.css')
<script>
    function toggleSubmenu() {
        document.getElementById('submenu').classList.toggle('hidden');
        document.getElementById('arrow').classList.toggle('rotate-180');
    }
</script>
