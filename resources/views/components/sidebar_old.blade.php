<aside class="bg-blue-600">
    <span
        class="absolute text-white text-4xl top-5 left-4 cursor-pointer"
        onclick="openSidebar()"
    >
        <i class="bi bi-filter-left px-2 bg-gray-900 rounded-md"></i>
    </span>
    <div
        class="sidebar fixed top-0 bottom-0 lg:left-0 p-2 w-[250px] overflow-y-auto text-center bg-gray-900 flex flex-col"
    >
        <div class="text-gray-100 text-xl">
            <div class="p-2.5 mt-1 flex items-center">
                <x-application-logo class="w-8 h-8" />
                <h1 class="font-bold text-gray-200 text-[15px] ml-3">BT Envanteri</h1>
                <i
                    class="bi bi-x cursor-pointer ml-28 lg:hidden"
                    onclick="openSidebar()"
                ></i>
            </div>
            <div class="my-2 bg-gray-600 h-[1px]"></div>
        </div>
        <a
            href="/"
            class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
        >
            <i class="bi bi-house-door-fill"></i>
            <span class="text-[15px] ml-4 text-gray-200 font-bold">Ana Sayfa</span>
        </a>

        @can('view device')
            <div class="my-4 bg-gray-600 h-[1px]"></div>
            <div
                class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
            >
                <i class="bi bi-chat-left-text-fill"></i>
                <div class="flex justify-between w-full items-center">
                    <a href="{{ route('devices.index') }}" class="w-full p-3 flex items-center rounded-md">
                        Cihazlar
                    </a>
                    <span class="text-sm rotate-180" id="arrow">
            <i class="bi bi-chevron-down"></i>
        </span>
                </div>
            </div>
            <div
                class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold"
                id="submenu"
            >
                @can('view deviceInfo')
                    <a href="/devices/orphans" class="block p-3 hover:bg-blue-600 rounded-md px-3">
                        Öksüz cihazlar
                    </a>
                @endcan
            </div>
        @endcan

        @can('view location')
            <div class="my-4 bg-gray-600 h-[1px]"></div>
            <a href="/locations" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                <i class="bi bi-house-door-fill"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Yer Bilgisi</span>
            </a>
        @endcan

        @can('view deviceType')
            <div class="my-4 bg-gray-600 h-[1px]"></div>
            <a href="/device_types" class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                <i class="bi bi-house-door-fill"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Cihaz Tipleri</span>
            </a>
        @endcan

        <div class="my-4 bg-gray-600 h-[1px]"></div>
        <div class="flex flex-col mt-auto space-y-2">
            @can('view user')
                <a href="/users" class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="text-[15px] ml-4 text-gray-200 font-bold">Kullanıcılar</span>
                </a>
            @endcan

            @can('view permission')
                <a href="/permissions" class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="text-[15px] ml-4 text-gray-200 font-bold">Permissions</span>
                </a>
            @endcan

            @can('view role')
                <a href="/roles" class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="text-[15px] ml-4 text-gray-200 font-bold">Roles</span>
                </a>
            @endcan

            <a href="{{ route('dashboard') }}" class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                <i class="bi bi-house-door-fill"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Dashboard</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="text-[15px] ml-4 text-gray-200 font-bold">Logout</span>
                </a>
            </form>
        </div>
    </div>
    </aside>
