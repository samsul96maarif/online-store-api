<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
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

    public function testAddProductExceedTheRemainingStockToCart()
    {
        $product = $this->createProduct();
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST',route('carts.store'), ['product_id' => $product->id, 'qty' => 11]);
        $response->assertStatus(400);
        $product->delete();
    }
}
