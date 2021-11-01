<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApplicationFlowFromCartToPayingInvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function authenticate(){
        return User::where('email', 'admin@evermos.com')->first()->createToken('API Token')->plainTextToken;
    }

    protected function createProduct(){
        $data = [
            'name' => 'test',
            'price' => 1000,
            'stock' => 10
        ];
        $token = $this->authenticate();
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/products', $data, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    'name', 'price', 'stock'
                ]
            ]);
        return Product::where(['name' => 'test', 'stock' => 10, 'price' => 1000])->first();
    }

    protected function createCart(){
        $product = Product::where('stock', '>', 5)->first();
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('carts.store'), ['product_id' => $product->id, 'qty' => 2]);
        $response->assertStatus(201);
        return Cart::where('product_id', $product->id)->first();
    }

    protected function createInvoice(){
        $cart = $this->createCart();
        $product_id = $cart->product_id;
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('checkouts.store'), [
            'description' => '-',
            'details' => [
                ['cart_id' => $cart->id]
            ]
        ]);
        $response->assertStatus(201);
        return Invoice::whereHas('details', function ($q) use ($product_id) {
           $q->where('product_id', $product_id);
        })->where('status', 'awaiting')->first();
    }

    public function testCreateProduct(){
        $data = [
            'name' => 'test',
            'price' => 1000,
            'stock' => 10
        ];
        $token = $this->authenticate();
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/products', $data, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    'name', 'price', 'stock'
                ]
            ]);
        Product::where(['name' => 'test', 'stock' => 10, 'price' => 1000])->delete();
    }

    public function testAddProductToCart()
    {
        $product = $this->createProduct();
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('carts.store'), ['product_id' => $product->id, 'qty' => 5]);
        $response->assertStatus(201);
        $product->delete();
    }

    public function testCheckout(){
        $cart = $this->createCart();
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('checkouts.store'), [
            'description' => '-',
            'details' => [
                ['cart_id' => $cart->id]
            ]
        ]);
        $response->assertStatus(201);
    }

    public function testPayBill(){
        $invoice = $this->createInvoice();
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('PUT', route('checkouts.update', ['checkout' => $invoice->id]), [
            'status' => 'paid'
        ]);
        $response->assertStatus(202);
        $invoice->delete();
    }
}
