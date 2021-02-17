<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Vanaheim\Core\Http\Requests\CartAddRequest;
use Vanaheim\Core\Http\Requests\CartUpdateRequest;
use Vanaheim\Core\Models\Buyable;
use Vanaheim\Core\Services\Cart\Cart;
use Vanaheim\Core\Services\Cart\CartPersonalia;
use Vanaheim\Core\Services\Cart\CartUpdate;

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

    public function update(CartUpdateRequest $request): JsonResponse
    {
        $cart = app(Cart::class);
        $update = new CartUpdate();

        $personalia = new CartPersonalia(
            $request->get('personalia', [])
        );

        $update->setPersonalia($personalia);


        foreach ($request->get('items', []) as $item) {

            $buyable = Buyable::where('buyable_type', $item['type'])
                ->where('buyable_id', $item['id'])
                ->with('item')
                ->first();

            $update->addBuyable($buyable->item, (int) $item['quantity']);
        }

        $cart->update($update);

        return response()->json($cart);
    }
}
