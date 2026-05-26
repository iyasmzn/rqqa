<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('success', 'Keranjang Anda masih kosong.');
        }

        $total = collect($cart)->sum(fn ($item) => $item['book']->price * $item['qty']);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:300'],
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $whatsapp = preg_replace('/\D/', '', setting('shop_whatsapp', setting('social_whatsapp', '')));

        if (empty($whatsapp)) {
            return redirect()->route('checkout.index')
                ->with('error', 'Nomor WhatsApp toko belum dikonfigurasi. Hubungi admin.');
        }

        // Build the WhatsApp message
        $lines = [];
        $lines[] = '🛍️ *PESANAN BUKU BARU*';
        $lines[] = '─────────────────────';
        $lines[] = '👤 Nama    : '.$request->name;
        $lines[] = '📱 HP/WA   : '.$request->phone;
        $lines[] = '📍 Alamat  : '.$request->address;

        if ($request->notes) {
            $lines[] = '📝 Catatan : '.$request->notes;
        }

        $lines[] = '';
        $lines[] = '📚 *DAFTAR BUKU:*';

        $total = 0;
        foreach ($cart as $item) {
            $book = $item['book'];
            $subtotal = $book->price * $item['qty'];
            $total += $subtotal;
            $lines[] = "• {$book->title}";
            $lines[] = "  {$item['qty']} pcs × {$book->formatted_price} = Rp ".number_format($subtotal, 0, ',', '.');
        }

        $lines[] = '─────────────────────';
        $lines[] = '💰 *TOTAL: Rp '.number_format($total, 0, ',', '.').'*';
        $lines[] = '';
        $lines[] = 'Mohon konfirmasi ketersediaan dan info pengiriman. Terima kasih 🙏';

        $message = implode("\n", $lines);
        $waUrl = 'https://wa.me/'.$whatsapp.'?text='.rawurlencode($message);

        // Clear cart after checkout
        session()->forget('cart');

        return redirect($waUrl);
    }
}
