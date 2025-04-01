<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\member;
use App\Models\Productss;
use App\Models\Transactions;
use App\Models\Transactions_item;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class PosTransaction extends ListRecords
{
    protected static string $resource = TransactionResource::class;
    protected static string $view = 'filament.pages.pos';

    public function getViewData(): array
    {
        return [
            'products' => Productss::all(),
            'transactionsIdLast' => Transactions::latest('id')->value('id'),
            'members' => member::all(),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            // 'member_id' => 'nullable|exists:members,id',
            'pay_amount' => 'required|numeric',
            'paymentMethod' => 'required|string',
            'items' => 'required|array',
            // 'items.*.id' => 'required|exists:products,id',
            // 'items.*.quantity' => 'required|integer|min:1',
            // 'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // Simpan transaksi
            $transaction = Transactions::create([
                'user_id' => $request->user_id,
                'member_id' => $request->member_id,
                'total_amount' => $request->pay_amount,
                'payment_method' => $request->paymentMethod,
            ]);

            // Simpan items transaksi
            foreach ($request->items as $item) {
                Transactions_item::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['quantity'],
                    'price_product' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update stok product
                Productss::where('id', $item['id'])
                    ->decrement('stok', $item['quantity']);
            }

            DB::commit();
            return response()->json(['message' => 'Transaction created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed', 'details' => $e->getMessage()], 500);
        }
    }
}
