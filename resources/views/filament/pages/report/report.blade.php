<x-filament::page>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
    @media print {
        .no-print { display: none; }
    }
</style>

<div class="bg-gray-100 dark:bg-gray-900 p-6">
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 p-6 shadow-lg rounded-lg">
        <!-- Header Laporan -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">Laporan Transaksi</h2>
            <p class="text-gray-600">Dicetak: {{ now()->format('d M Y, H:i') }}</p>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <th class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">No</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">ID Transaksi</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">Tanggal</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">Kasir</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $index => $transaction)
                        <tr class="text-center {{ $index % 2 === 0 ? 'bg-gray-100 dark:bg-gray-800' : 'dark:bg-gray-900' }}">
                            <td class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">TWPOS-KS-{{ $transaction->id }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">{{ $transaction->user->name ?? '-' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-xs md:text-sm">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-200 dark:bg-gray-700 font-bold">
                        <td colspan="4" class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-right">Total Keseluruhan:</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-2 md:px-4 py-2 text-center">Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Tombol Cetak -->
        <div class="text-center mt-6 no-print">
            <button onclick="window.print()" class="bg-blue-600 dark:bg-blue-500 text-white px-6 py-2 rounded shadow hover:bg-blue-700 dark:hover:bg-blue-600">
                Cetak Laporan
            </button>
        </div>
    </div>
</div>
</x-filament::page>
