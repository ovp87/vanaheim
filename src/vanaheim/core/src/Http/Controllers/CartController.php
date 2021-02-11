<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanaheim\Core\Models\Buyable;
use Vanaheim\Core\Models\Product;
use Vanaheim\Core\Services\Cart\Cart;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $cart = app(Cart::class);

        $buyable = Buyable::where('buyable_type', $request->type)
            ->where('buyable_id', $request->id)
            ->with('item')
            ->first();

        $cart->addItem($buyable->item, $request->get('quantity', 1));

        return response()->json($cart);

    }

    public function createDemo()
    {
        Product::firstOrCreate([
            'title' => 'My first product',
            'price' => 10000,
        ])->buyable()->firstOrCreate([
            'url' => 'products/my-first-product'
        ]);
    }
}
