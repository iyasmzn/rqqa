<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsappOrderLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_number_normalizes_common_formats(): void
    {
        $this->assertSame('6281234567890', whatsapp_number('6281234567890'));
        $this->assertSame('6281234567890', whatsapp_number('+62 812-3456-7890'));
        $this->assertSame('6281234567890', whatsapp_number('0812 3456 7890'));
        $this->assertNull(whatsapp_number(''));
        $this->assertNull(whatsapp_number(null));
        $this->assertNull(whatsapp_number('-'));
    }

    public function test_book_page_orders_through_the_shop_whatsapp_number(): void
    {
        Setting::setMany([
            'shop_whatsapp' => '6281234567890',
            'social_whatsapp' => '6289999999999',
        ]);

        $book = Book::factory()->create(['title' => 'Riyadhus Shalihin', 'stock' => 5]);

        $this->get(route('books.show', $book))
            ->assertOk()
            ->assertSee('https://wa.me/6281234567890?text='.rawurlencode("Assalamu'alaikum, saya ingin memesan buku: Riyadhus Shalihin"), false);
    }

    public function test_book_page_falls_back_to_the_general_whatsapp_number(): void
    {
        Setting::set('social_whatsapp', '0812-3456-7890');

        $book = Book::factory()->create(['stock' => 5]);

        $this->get(route('books.show', $book))
            ->assertOk()
            ->assertSee('https://wa.me/6281234567890?text=', false);
    }

    public function test_book_page_without_a_number_points_to_the_contact_page(): void
    {
        $book = Book::factory()->create(['stock' => 5]);

        $this->get(route('books.show', $book))
            ->assertOk()
            ->assertDontSee('https://wa.me/?text=', false)
            ->assertSee('Hubungi Kami untuk Memesan')
            ->assertSee('href="'.route('contact.index').'"', false);
    }

    public function test_checkout_sends_the_order_to_the_same_number(): void
    {
        Setting::set('social_whatsapp', '+62 811-2222-3333');

        $book = Book::factory()->create(['stock' => 5]);

        $this->actingAs(User::factory()->create())
            ->withSession(['cart' => [$book->id => ['qty' => 2]]])
            ->post(route('checkout.process'), [
                'name' => 'Budi',
                'phone' => '081234567890',
                'address' => 'Jl. Mawar No. 1',
            ])
            ->assertRedirectContains('https://wa.me/6281122223333?text=');
    }

    public function test_donation_contact_uses_the_general_whatsapp_number(): void
    {
        Setting::set('social_whatsapp', '0812 3456 7890');

        $this->get(route('donasi.index'))
            ->assertOk()
            ->assertSee('href="https://wa.me/6281234567890"', false);
    }
}
