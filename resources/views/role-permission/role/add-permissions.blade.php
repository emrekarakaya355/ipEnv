<x-layout>
    @section('title','Yetki Ver')


    <div class="w-full ">


        <div class="card">
            <div class="card-body">
                <form action="{{ url('roles/'.$role->id.'/give-permissions') }}" method="POST" class="bg-white shadow-lg rounded-2xl p-8 form-container">
                    @csrf
                    @method('PUT')

                    {{-- Role Management Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Rol Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2" data-section="role-management" />
                                Hepsini Seç
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="role-management">
                            @foreach ($permissions->filter(fn($perm) => Str::startsWith($perm->name, 'view role') || Str::startsWith($perm->name, 'create role') || Str::startsWith($perm->name, 'update role') || Str::startsWith($perm->name, 'delete role')) as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Permission Management Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">İzin Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2" data-section="permission-management" />
                                Hepsini Seç
                            </label>                          </div>
                        <div class="grid grid-cols-2 gap-4" id="permission-management">
                            @foreach ($permissions->filter(fn($perm) => Str::startsWith($perm->name, 'view permission') || Str::startsWith($perm->name, 'create permission') || Str::startsWith($perm->name, 'update permission') || Str::startsWith($perm->name, 'delete permission')) as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- User Management Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Kullanıcı Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2" data-section="user-management" />
                                Hepsini Seç
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="user-management">
                            @foreach ($permissions->filter(fn($perm) => Str::startsWith($perm->name, 'view user') || Str::startsWith($perm->name, 'create user') || Str::startsWith($perm->name, 'update user') || Str::startsWith($perm->name, 'delete user')) as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Device Management Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Cihaz Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2" data-section="device-management"/>
                                Hepsini Seç
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="device-management">
                            @foreach ($permissions->filter(fn($perm) => $perm->name === 'view device' ||
                                                                     $perm->name === 'create device' ||
                                                                     $perm->name === 'update device' ||
                                                                     $perm->name === 'delete device') as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Device Info Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Cihaz Bilgileri Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class=" toggle-select-all-checkbox mr-2" data-section="deviceInfo-management"/>
                                Hepsini Seç
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="deviceInfo-management">
                            @foreach ($permissions->filter(fn($perm) => $perm->name === 'view deviceInfo' ||
                                                                     $perm->name === 'create deviceInfo' ||
                                                                     $perm->name === 'update deviceInfo' ||
                                                                     $perm->name === 'delete deviceInfo'
                                                                     ) as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Device Type Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Cihaz Tipi Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2" data-section="deviceType-management"/>
                                Hepsini Seç
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="deviceType-management">
                            @foreach ($permissions->filter(fn($perm) => $perm->name === 'view deviceType' ||
                                                                     $perm->name === 'create deviceType' ||
                                                                     $perm->name === 'update deviceType' ||
                                                                     $perm->name === 'delete deviceType') as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- location Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Yer Bilgisi Yönetimi</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2"  data-section="location-management" />
                                Hepsini Seç
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4" id="location-management">
                            @foreach ($permissions->filter(fn($perm) => $perm->name === 'view location' ||
                                                                     $perm->name === 'create location' ||
                                                                     $perm->name === 'update location' ||
                                                                     $perm->name === 'delete location') as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    {{-- Diğer Permissions --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold mb-4">Diğer Yetkiler</h2>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="toggle-select-all-checkbox mr-2"  data-section="other-permissions" />
                                Hepsini Seç
                            </label>                          </div>
                        <div class="grid grid-cols-2 gap-4" id="other-permissions">
                            @foreach ($permissions->filter(fn($perm) => !Str::startsWith($perm->name, ['view role', 'create role', 'update role', 'delete role', 'view permission', 'create permission', 'update permission', 'delete permission', 'view user', 'create user', 'update user', 'delete user', 'view device', 'create device', 'update device', 'delete device','view location','create location','update location','delete location'])) as $permission)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-primary-button type="submit" class="btn btn-primary">Güncelle</x-primary-button>
                        <x-danger-button type="button" onclick="window.location.href='{{ route('roles.index') }}'">Geri Dön</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(sectionId) {
            const checkboxes = document.querySelectorAll(`#${sectionId} input[type="checkbox"]`);
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
        }
        document.addEventListener('DOMContentLoaded', () => {
            const selectAllCheckboxes = document.querySelectorAll('.toggle-select-all-checkbox');

            selectAllCheckboxes.forEach(selectAllCheckbox => {
                const sectionId = selectAllCheckbox.getAttribute('data-section');
                const checkboxes = document.querySelectorAll(`#${sectionId} input[type="checkbox"]`);

                // Initial check to see if all checkboxes in the section are checked
                selectAllCheckbox.checked = Array.from(checkboxes).every(cb => cb.checked);

                selectAllCheckbox.addEventListener('click', () => {
                    toggleSelectAll(sectionId);
                });

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        selectAllCheckbox.checked = Array.from(checkboxes).every(cb => cb.checked);
                    });
                });
            });
        });

    </script>
</x-layout>
