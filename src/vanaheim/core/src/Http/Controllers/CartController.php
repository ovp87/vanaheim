<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Vanaheim\Core\Http\Requests\CartAddRequest;
use Vanaheim\Core\Models\Buyable;
use Vanaheim\Core\Services\Cart\Cart;

class CartController extends Controller
{
    public function add(CartAddRequest $request): JsonResponse
    {
        $cart = app(Cart::class);

        $buyable = Buyable::where('buyable_type', $request->get('type'))
            ->where('buyable_id', $request->get('id'))
            ->with('item')
            ->first();

        $cart->addItem(
            $buyable->item,
            (int) $request->get('quantity', 1)
        );

        return response()->json($cart);

    }
}
