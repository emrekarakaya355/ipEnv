<body class="bg-blue-600">
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
                <i class="bi bi-app-indicator px-2 py-1 rounded-md bg-blue-600"></i>
                <h1 class="font-bold text-gray-200 text-[15px] ml-3">BT Envanteri</h1>
                <i
                    class="bi bi-x cursor-pointer ml-28 lg:hidden"
                    onclick="openSidebar()"
                ></i>
            </div>
            <div class="my-2 bg-gray-600 h-[1px]"></div>
        </div>
        <div
            class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
            >
            <i class="bi bi-house-door-fill"></i>
            <a href="/devices" class="text-[15px] ml-4 text-gray-200 font-bold">Ana Sayfa</a>
        </div>

        <div class="my-4 bg-gray-600 h-[1px]"></div>
        <div
            class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
            onclick="dropdown()"
        >
            <i class="bi bi-chat-left-text-fill"></i>
            <div class="flex justify-between w-full items-center">
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Cihazlar</span>
                <span class="text-sm rotate-180" id="arrow">
            <i class="bi bi-chevron-down"></i>
          </span>
            </div>
        </div>
        <div
            class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold"
            id="submenu"
        >
            <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">
                <a href="/devices" class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Tüm Cihazlar</a>

            </h1>
            <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">
                <a href="{{ route('devices.index.type', ['type' => 'switches']) }}"  class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Switchler</a>

            </h1>
            <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">
                <a href="{{ route('devices.index.type', ['type' => 'ap']) }}"  class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Access Pointler</a>

            </h1>
            <h1 class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">
                <a href="/devices/orphans"  class="cursor-pointer p-2 hover:bg-blue-600 rounded-md mt-1">Öksüz cihazlar</a>
            </h1>

        </div>
        <div class="my-4 bg-gray-600 h-[1px]"></div>

        <div
            class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
        >
            <i class="bi bi-house-door-fill"></i>
            <a href="/locations" class="text-[15px] ml-4 text-gray-200 font-bold">Yer Bilgisi</a>
        </div>
        <div class="my-4 bg-gray-600 h-[1px]"></div>

        <div
            class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
        >
            <i class="bi bi-house-door-fill"></i>
            <a href="/device_types" class="text-[15px] ml-4 text-gray-200 font-bold">Cihaz Tipleri</a>
        </div>
        <div class="my-4 bg-gray-600 h-[1px]"></div>
        <div class="flex flex-col mt-auto space-y-2">
            <div
                class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
            >
                <i class="bi bi-house-door-fill"></i>
                <a href="/users" class="text-[15px] ml-4 text-gray-200 font-bold">Kullanıcılar</a>
            </div>

            <div
                class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
            >
                <i class="bi bi-house-door-fill"></i>
                <a href="{{ route('dashboard') }}" class="text-[15px] ml-4 text-gray-200 font-bold">Dashboard</a>
            </div>

            <div class="p-2.5 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white">
                <i class="bi bi-box-arrow-in-right"></i>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="text-[15px] ml-4 text-gray-200 font-bold"
                    >
                        Logout
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function dropdown(event) {
            //document.querySelector("#submenu").classList.toggle("hidden");
            document.querySelector("#arrow").classList.toggle("rotate-0");

        }
        dropdown();

        function openSidebar() {
            //document.querySelector(".sidebar").classList.toggle("hidden");
        }
    </script>
</body>
</html>
