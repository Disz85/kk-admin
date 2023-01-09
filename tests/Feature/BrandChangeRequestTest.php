<?php

namespace Tests\Feature;

use App\Models\BrandChangeRequest;
use Database\Factories\BrandFactory;
use Tests\TestCase;

class BrandChangeRequestTest extends TestCase
{
    /** @test */
    public function it_can_store_a_new_brand_change_request()
    {
        $brand = BrandFactory::new()->raw();
        $response = $this->post(route('brand-change-requests.store'), $brand);
        $response->assertCreated();
        $response->assertJsonFragment([
            'data' => $brand,
        ]);
    }

    /** @test */
    public function it_can_approve_a_brand_change_request()
    {
        $brandChangeRequest = BrandChangeRequest::factory()->create();
        $response = $this->post(route('brand-change-requests.approve', ['brand_change_request' => $brandChangeRequest->id]));
        $response->assertCreated();
        $response->assertJsonFragment([
            'title' => $brandChangeRequest->data['title'],
            'where_to_find' => $brandChangeRequest->data['where_to_find'],
            'url' => $brandChangeRequest->data['url'],
            'description' => $brandChangeRequest->data['description'],
            'created_by' => $brandChangeRequest->data['created_by'],
        ]);
    }

    /** @test */
    public function it_can_update_a_brand_change_request()
    {
        $brand = BrandChangeRequest::factory()->create();
        $brandChanged = BrandFactory::new()->raw();
        $response = $this->put(route('brand-change-requests.update', ['brand_change_request' => $brand->id]), $brandChanged);
        $response->assertOk();
        $response->assertJsonFragment([
            'title' => $brandChanged['title'],
            'where_to_find' => $brandChanged['where_to_find'],
            'description' => $brandChanged['description'],
            'created_by' => $brandChanged['created_by'],
        ]);
    }

    /** @test */
    public function it_can_show_a_brand_change_request()
    {
        $brand = BrandChangeRequest::factory()->create();
        $response = $this->get(route('brand-change-requests.show', ['brand_change_request' => $brand->id]));
        $response->assertOk();
        $response->assertJsonFragment([
            'title' => $brand->data['title'],
            'where_to_find' => $brand->data['where_to_find'],
            'url' => $brand->data['url'],
            'description' => $brand->data['description'],
            'created_by' => $brand->data['created_by'],
        ]);
    }

    /** @test */
    public function it_can_show_the_brand_change_request_list()
    {
        $brands = BrandChangeRequest::factory()->count(3)->create();
        $response = $this->get(route('brand-change-requests.index'));
        $response->assertOk();
        foreach ($brands as $brand) {
            $response->assertJsonFragment([
                'id' => $brand['id'],
                'data' => $brand['data'],
            ]);
        }
    }

    /** @test */
    public function it_can_reject_a_brand_change_request()
    {
        $brandChangeRequest = BrandChangeRequest::factory()->create();
        $response = $this->post(route('brand-change-requests.reject', ['brand_change_request' => $brandChangeRequest->id]));
        $response->assertOk();
        $this->assertNull(BrandChangeRequest::find($brandChangeRequest->id));
    }
}
