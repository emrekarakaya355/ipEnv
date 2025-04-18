<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">

<div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <x-application-logo class="w-8 h-8 mx-auto" />

            <span class="mx-2 text-2xl font-semibold text-white">Bt Envanter</span>
        </div>
    </div>

    <nav class="mt-10">
        <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25" href="/">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>

            <span class="mx-3">Dashboard</span>
        </a>
        @can('view device')
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
           href="/devices">
            <i class="fa-solid fa-server  w-6 h-6 text-gray-500"></i>
            <span class="mx-3">Cihazlar</span>
            <span class="submenu-arrow" id="arrow">
                    <i class="fa-solid fa-arrow-down"></i>
            </span>
        </a>
        @endcan
        @can('view location')
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
           href="/locations">

            <i class="fa-solid fa-map-location  w-6 h-6 text-gray-500" ></i>
            <span class="mx-3">Yer Bilgileri</span>
        </a>
        @endcan
        @can('view deviceType')
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
           href="/device_types">
            <i class="fa-solid fa-microchip w-6 h-6 text-gray-500"></i>
            <span class="mx-3">Cihaz Tipleri</span>
        </a>
        @endcan
        @can('view user')
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
           href="/users">
            <i class="fa-solid fa-users w-6 h-6 text-gray-500"></i>
            <span class="mx-3">Kullanıcılar</span>
        </a>
        @endcan
        @can('view role')
            <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
               href="/roles">
                <i class="fa-solid fa-lock w-6 h-6 text-gray-500"></i>
                <span class="mx-3">Roller</span>
            </a>
        @endcan
        @can('view permission')
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
           href="/permissions">
            <i class="fa-solid fa-lock w-6 h-6 text-gray-500"></i>
            <span class="mx-3">Yetkiler</span>
        </a>
        @endcan
            @can('view script')
        <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
           href="/scripts">
            <i class="fa-solid fa-lock w-6 h-6 text-gray-500"></i>
            <span class="mx-3">Scriptler</span>
        </a>
        @endcan


    </nav>
</div>
<div class="flex flex-col flex-1 overflow-hidden">
    <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-indigo-600">
        <div class="flex items-center">
            <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round"></path>
                </svg>
            </button>

            <div class="relative mx-4 lg:mx-0 search-box">
                <div class="row">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                           <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    <form id="mainSearchForm">
                        <input class="w-32 pr-4 rounded-md form-input sm:w-64 focus:border-indigo-600" type="text" id="mainSearchInput" name="search"
                               placeholder="Search" autocomplete="off">
                    </form>
                </div>
                <div class="result-box bg-white absolute z-10">
                    <!--javascript ile dolacak-->
                </div>
            </div>
        </div>

        <div class="flex items-center">
            @auth
                <div>{{ auth()->user()->roles()->first()->name ?? 'Rol Bulunamadı' }}</div>
            @endauth
            <div x-data="{ notificationOpen: false }" class="relative">
                <button @click="notificationOpen = ! notificationOpen"
                        class="flex mx-4 text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </button>

                <div x-show="notificationOpen" @click="notificationOpen = false"
                     class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

                <div x-show="notificationOpen"
                     class="absolute right-0 z-10 mt-2 overflow-hidden bg-white rounded-lg shadow-xl w-80"
                     style="width: 20rem; display: none;">
                    <a href="#"
                       class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-indigo-600">

                        <p class="mx-2 text-sm">
                            <span class="font-bold" href="#">Emre</span> Yeni Cihaz  <span
                                class="font-bold text-indigo-400" href="#">cisco_5120</span> Ekledi
                        </p>
                    </a>

                </div>
            </div>

            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = ! dropdownOpen"
                        class="relative block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none bg-blue-500">
                    @auth()
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endauth
                </button>

                <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-10 w-full h-full"
                     style="display: none;"></div>

                <div x-show="dropdownOpen"
                     class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl"
                     style="display: none;">
                    <a href="/profile"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a>
                    @can('view deleted devices')
                    <a href="/devices/deleted-devices"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Silinen Cihazlar</a>
                    @endcan
                    @can('view orphans')
                        <a href="/devices/orphans"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Öksüz Cihazlar</a>
                    @endcan
                    @can('view user')
                    <a href="/users"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Kullanıcılar</a>
                    @endcan
                    @can('view role')
                    <a href="/roles"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Roller</a>
                    @endcan
                    @can('view permission')
                    <a href="/permissions"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Yetkiler</a>

                    @endcan
                    @can('view script')
                    <a href="/scripts"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Scriptler</a>

                    @endcan

                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
