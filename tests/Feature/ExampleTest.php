<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testPincodeSearch()
    {
        $response = $this->get('/api/324005');
        $response->assertOk()
                 ->assertJsonFragment(['city' => 'Kota']);

        $response = $this->get('/api/abc');
        $response->assertNotFound();

        $response = $this->get('/api/32400100');
        $response->assertNotFound();

        $response = $this->get('/api/000320');
        $response->assertNotFound();

        $response = $this->get('/api/32400a');
        $response->assertNotFound();

        $response = $this->get('/api/');
        $response->assertNotFound();
    }

    public function testLocationSearch()
    {
        $response = $this->get('/api/info/');
        $response->assertNotFound();

        $response = $this->get('/api/info/?state=&city=');
        $response->assertNotFound();

        $response = $this->get('/api/info/?state=andhra');
        $response->assertOk()
                 ->assertJsonFragment(['city' => 'Kurnool']);

        $response = $this->get('/api/info/?state=andhra&city=kurn');
        $response->assertOk()
                 ->assertJsonFragment(['518004']);

        $response = $this->get('/api/info/?state=andhra&city=123');
        $response->assertNotFound();

        $response = $this->get('/api/info/?state=4567&city=kur');
        $response->assertNotFound();
    }

}
