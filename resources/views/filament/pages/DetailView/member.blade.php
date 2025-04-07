<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    @vite('resources/css/app.css')

    <style>
        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
            color: #a0aec0;
        }
    </style>
    @livewireStyles
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="container mx-auto p-4 w-full">
        <div class="grid grid-cols-4 gap-4 w-full">

            <!-- Profile Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 col-span-1">
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-3xl font-semibold mb-4">
                        Y
                    </div>
                    <div class="text-center">
                        <h2 class="text-xl font-bold">
                            {{ $getRecord()->name }}

                            @if ($getRecord()->gender == 'male')
                                <span class="ml-1 text-blue-500">♂</span>
                            @elseif ($getRecord()->gender == 'female')
                                <span class="ml-1 text-pink-500">♀</span>
                            @else
                                <span class="ml-1 text-gray-500 dark:text-gray-400">Unknown</span>
                            @endif
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400">Joined at {{ $getRecord()->created_at }}</p>
                        @if (date('d-m-Y', strtotime($getRecord()->birthdate)) == date('d-m-Y'))
                            <span class="bg-green-200 text-green-800 text-xs px-2 py-1 rounded-full mt-2">Happy
                                Birthday
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Plan Categories</h3>
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Gym</span>
                </div>
                {{-- Basik Info --}}
                <div class="mt-4">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Basic Info</h3>
                    <div>
                        <div class="flex items-center mb-2">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-7 2v4m-7-4v4m5 0h.01M9 21h6a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-700 dark:text-gray-300">E-Mail</h3>
                                <p>{{ $getRecord()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684L18.26 14.14a1 1 0 01-.076.923l-2.188 1.79a1 1 0 01-.725.328H7.414a1 1 0 01-.948-.684L2.19 10.757a1 1 0 010-1.514l4.26-3.28a1 1 0 01.684-.299zm9 12a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zm-4 0a1 1 0 001-1v-2a1 1 0 00-1-1H7a1 1 0 00-1 1v2a1 1 0 001 1zm4-4a1 1 0 00-1 1H7a1 1 0 00-1-1v-2a1 1 0 001-1h2a1 1 0 001 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Phone Number</h3>
                            <p>{{ $getRecord()->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Address</h3>
                            <p>{{ $getRecord()->address }}</p>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Date of Birth</h3>
                            <p>{{ date('d-m-Y', strtotime($getRecord()->birthdate)) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Member ID</h3>
                            <p>MEM{{ $getRecord()->id }}</p>
                        </div>

                    </div>
                    <div class="flex items-center mb-2">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.55 4.55M10 14l4.55-4.55M10 10l4.55 4.55M15 4l-4.55 4.55M4 15l4.55-4.55M19 10l-4.55 4.55m0 0l4.55-4.55M10 19l4.55-4.55M19 15l-4.55 4.55">
                            </path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Identification Id</h3>
                            <p>{{ $getRecord()->identification }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 col-span-3">
                <div class="md:flex md:items-center md:justify-between mb-4">
                    <h1 class="text-2xl font-bold mb-2 md:mb-0">Membership</h1>
                    <div class="flex space-x-4">
                        <button
                            class="tab-button px-4 py-2 rounded-md focus:outline-none active font-semibold text-blue-500 border-b-2 border-blue-500"
                            data-tab="tab1">Membership</button>
                        <button class="tab-button px-4 py-2 rounded-md focus:outline-none" data-tab="tab2">PT
                            Session</button>
                        <button class="tab-button px-4 py-2 rounded-md focus:outline-none"
                            data-tab="tab3">Class</button>
                        <button class="tab-button px-4 py-2 rounded-md focus:outline-none"
                            data-tab="tab4">Loyalty</button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div>
                    <!-- Membership Tab -->
                    <div id="tab1" class="tab-content active">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Summary</h3>
                        <div class="md:flex md:justify-between">
                            <div class="mb-4 md:w-1/2">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span>Total Membership: {{ $getRecord()->Membership()->count() }}</span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Total Checkin: {{ $getRecord()->checkIns()->count() }}</span>
                                </div>
                            </div>
                            <div class="mb-4 md:w-1/2">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Total Duration: {{ $getRecord()->durationMembership() ?? 0 }} days</span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Last Checkin: {{ $getRecord()->lastCheckIn()->value('check_in_at') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-700 dark:text-gray-300">List Membership</h3>
                                <a href="{{route('filament.admin.resources.member-subscriptions.create',['member_id' => $getRecord()->id])}}" 
                                class="text-blue-500 hover:text-blue-600">Buy Membership</a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($getRecord()->Membership as $item)
                                    <div x-data="{ open: null }" class="rounded-lg shadow-md overflow-visible">
                                        <div class="bg-gray-100 px-4 py-3 rounded-t-xl">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p class="font-semibold text-gray-700">
                                                        {{ $item->membershipType->name }}</p>
                                                    <p class="text-gray-500 text-sm">
                                                        {{ date('d M', strtotime($item->start_date)) }} -
                                                        {{ date('d M Y', strtotime($item->end_date)) }}
                                                    </p>
                                                </div>
                            
                                                <!-- Tombol Tiga Titik -->
                                                <div class="relative">
                                                    <button @click="open = (open === {{$item->id}} ? null : {{$item->id}})" 
                                                        class="text-gray-500 hover:text-gray-600 focus:outline-none">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 6a1 1 0 110-2 1 1 0 010 2z">
                                                            </path>
                                                        </svg>
                                                    </button>
                            
                                                    <!-- Popup Menu -->
                                                    <div x-show="open === {{$item->id}}" @click.away="open = null"
                                                        class="absolute right-0 top-full mt-2 w-32 bg-white border border-gray-200 
                                                        rounded-lg shadow-lg z-50">
                                                        <ul class="py-2 text-gray-700">
                                                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Detail</a></li>
                                                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Edit</a></li>
                                                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Archive</a></li>
                                                            <li><a href="#" class="block px-4 py-2 text-red-500 hover:bg-gray-100">Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            
                                        @if ($item->status == 'active')
                                            <div class="bg-blue-600 text-white px-4 py-3 rounded-b-xl">
                                        @else
                                            <div class="bg-red-500 text-white px-4 py-3">
                                        @endif
                                                <p>{!! 'Rp ' . number_format($item->membershipType->price ?? 0, 0, ',', '.') !!}</p>
                                                <div class="mt-2 flex justify-start">
                                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full mr-2">
                                                        {{ $item->status }}
                                                    </span>
                                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">paid off</span>
                                                </div>
                                            </div>
                                    </div>
                                @endforeach
                            </div>
                            
                        </div>
                    </div>

                    <!-- PT Session Tab -->
                    <div id="tab2" class="tab-content hidden">
                        <p>Class details go here.</p>
                    </div>

                    <!-- Class Tab -->
                    <div id="tab3" class="tab-content hidden">
                        <p>Class details go here.</p>
                    </div>

                    <!-- Loyalty Tab -->
                    <div id="tab4" class="tab-content hidden">
                        <p>Loyalty details go here.</p>
                    </div>
                 </div>
            </div>
        </div>
    </div>
    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetTab = button.getAttribute('data-tab');

                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.classList.remove('text-blue-500');
                        btn.classList.remove('border-b-2');
                        btn.classList.remove('border-blue-500');
                    });
                    tabContents.forEach(content => content.classList.remove('active'));

                    button.classList.add('active');
                    button.classList.add('text-blue-500');
                    button.classList.add('border-b-2');
                    button.classList.add('border-blue-500');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        });
    </script>
</body>

</html>
