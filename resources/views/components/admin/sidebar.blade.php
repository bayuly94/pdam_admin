<div class=" md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
    <!-- Sidebar component, show/hide based on sidebar state. -->
    <div class="flex flex-col flex-grow border-r border-gray-200 bg-white pt-5 pb-4 overflow-y-auto">
        <div class="flex items-center flex-shrink-0 px-4">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-24 h-24 object-contain" />
        </div>
        <div class="mt-5 flex-grow flex flex-col">
            <nav class="flex-1 px-2 space-y-1 bg-white">
                <!-- Dashboard -->
                {{-- <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </x-slot>
                    {{ __('Dashboard') }}
                </x-admin.nav-link> --}}


                  <x-admin.nav-link :href="route('admin.volume-histories.index')" :active="request()->routeIs('admin.volume-histories.*')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot>
                    {{ __('Scan Meter') }}
                </x-admin.nav-link>


                  {{-- customers --}}
                <x-admin.nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot>
                    {{ __('Pelanggan') }}
                </x-admin.nav-link>

                  {{-- employee --}}
                <x-admin.nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot>
                    {{ __('Petugas') }}
                </x-admin.nav-link>


                {{-- pengguna --}}
                <x-admin.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot>
                    {{ __('User Admin') }}
                </x-admin.nav-link>


                {{-- settings --}}
                 <x-admin.nav-link :href="route('admin.settings.form')" :active="request()->routeIs('admin.settings.form')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </x-slot>
                    {{ __('Pengaturan') }}
                </x-admin.nav-link>



                <!-- Add more navigation items here -->
                <!-- Example: Users -->
                <!--
                <x-admin.nav-link href="#" :active="request()->routeIs('admin.users')">
                    <x-slot name="icon">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot>
                    {{ __('Users') }}
                </x-admin.nav-link>
                -->
            </nav>
        </div>
    </div>
</div>
