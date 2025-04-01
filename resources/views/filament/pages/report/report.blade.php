<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f3f4f6;
    }
    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: white;
        padding: 20px;
    }
    h2 {
        text-align: center;
        font-size: 20px;
        margin-bottom: 10px;
    }
    p {
        text-align: center;
        color: #666;
        font-size: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 12px;
        text-align: center;
    }
    th {
        background-color: #f4f4f4;
    }
    tfoot {
        font-weight: bold;
        background-color: #eaeaea;
    }
</style>

<div class="container">
    <h2>Laporan Transaksi</h2>
    <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr style="background-color: {{ $index % 2 === 0 ? '#f9f9f9' : '#ffffff' }};">
                    <td>{{ $index + 1 }}</td>
                    <td>TWPOS-KS-{{ $transaction->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total Keseluruhan:</td>
                <td>Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
