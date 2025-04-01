<x-filament::page>
    <div class="container mx-auto mt-10 space-y-6">
        <h2 class="mb-6 text-center text-2xl font-bold dark:text-gray-100 ">Download Report</h2>
    
        <div class="flex justify-center flex-wrap gap-8">

    
            <!-- Download Inventory Report -->
            {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-xs mb-4 flex flex-col items-center justify-center">
                <h4 class="text-center text-lg font-semibold mb-4 dark:text-gray-100">Download Inventory Report</h4>
                <form method="POST" action="{{ route('filament.report.inventory.download') }}" class="w-full flex flex-col items-center">
                    @csrf
                    <button type="submit" class="btn-download">Download Report</button>
                </form>
            </div> --}}
            
            
    
            <!-- Download Income/Expense Report -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-xs mb-4">
                <h4 class="text-center text-lg font-semibold mb-4 dark:text-gray-100">Download Report</h4>
                <form method="POST" action="{{ route('filament.report.incomeExpense.download') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-white">From: </label>
                        <input type="date" name="start_date" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-700 dark:text-gray-300" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-white">To: </label>
                        <input type="date" name="end_date" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-700 dark:text-gray-300" required>
                    </div>
    
                    <button type="submit" class="btn-download w-full">Download Report</button>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        .btn-download {
            background-color: #ff8303;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-radius: 0.375rem;
        }
    </style>
</x-filament::page>