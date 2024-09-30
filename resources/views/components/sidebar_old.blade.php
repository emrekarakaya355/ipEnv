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
                    <a href="/devices" class="w-1 p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
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

                <a href="{{ route('devices.index.type', ['type' => 'switches']) }}" class="block p-2 hover:bg-blue-600 rounded-md mt-1">
                    Switchler
                </a>
                <a href="{{ route('devices.index.type', ['type' => 'ap']) }}" class="block p-2 hover:bg-blue-600 rounded-md mt-1">
                    Access Pointler
                </a>
                @can('view deviceInfo')
                    <a href="/devices/orphans" class="block p-2 hover:bg-blue-600 rounded-md mt-1">
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

            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                <i class="bi bi-box-arrow-in-right"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Logout</span>
            </a>
        </div>
    </div>
    </aside>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Obtener todas las opciones principales con desplegables
            const opcionesConDesplegable = document.querySelectorAll(".opcion-con-desplegable");

            // Agregar evento de clic a cada opción principal
            opcionesConDesplegable.forEach(function (opcion) {
                opcion.addEventListener("click", function () {
                    // Obtener el desplegable asociado a la opción
                    const desplegable = opcion.querySelector(".desplegable");

                    // Alternar la clase "hidden" para mostrar u ocultar el desplegable
                    desplegable.classList.toggle("hidden");
                });
            });
        });
    </script>