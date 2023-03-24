<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\BrandChangeRequest;
use App\Models\User;
use Database\Factories\BrandFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class BrandChangeRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-brand-change-requests');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_store_a_new_brand_change_request(): void
    {
        list($brand, $user) = $this->makeDummyRequestData();
        $response = $this->post(route('admin.brand-change-requests.store'), $brand);

        $response->assertCreated();
        $response->assertJsonFragment([
            'title' => $brand['title'],
            'where_to_find' => $brand['where_to_find'],
            'url' => $brand['url'],
            'description' => $brand['description'],
            'created_by' => $user['id'],
        ]);
    }

    /** @test */
    public function it_can_approve_a_brand_change_request(): void
    {
        /** @var BrandChangeRequest $brandChangeRequest */
        $brandChangeRequest = BrandChangeRequest::factory()->create();
        $response = $this->post(route('admin.brand-change-requests.approve', ['brand_change_request' => $brandChangeRequest->id]));
        $response->assertCreated();
        $response->assertJsonFragment([
            'title' => $brandChangeRequest->data['title'],
            'where_to_find' => $brandChangeRequest->data['where_to_find'],
            'url' => $brandChangeRequest->data['url'],
            'description' => $brandChangeRequest->data['description'],
            'created_by' => $brandChangeRequest->data['created_by'],
        ]);

        $this->assertDatabaseHas(Brand::class, Arr::only($brandChangeRequest->data, ['title', 'where_to_find', 'url']));
        $this->assertDatabaseMissing(BrandChangeRequest::class, Arr::only($brandChangeRequest->toArray(), ['id']));
    }

    /** @test */
    public function it_can_update_a_brand_change_request(): void
    {
        /** @var BrandChangeRequest $brand */
        $brand = BrandChangeRequest::factory()->create();
        list($brandChanged, $user) = $this->makeDummyRequestData();
        $response = $this->put(route('admin.brand-change-requests.update', ['brand_change_request' => $brand->id]), $brandChanged);

        $response->assertOk();
        $response->assertJsonFragment([
            'title' => $brandChanged['title'],
            'where_to_find' => $brandChanged['where_to_find'],
            'url' => $brandChanged['url'],
            'description' => $brandChanged['description'],
            'created_by' => $user['id'],
        ]);
    }

    /** @test */
    public function it_can_show_a_brand_change_request(): void
    {
        /** @var BrandChangeRequest $brand */
        $brand = BrandChangeRequest::factory()->create();
        $response = $this->get(route('admin.brand-change-requests.show', ['brand_change_request' => $brand->id]));
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
    public function it_can_show_the_brand_change_request_list(): void
    {
        /** @var Collection|Brand[] $brands */
        $brands = BrandChangeRequest::factory()->count(3)->create();
        $response = $this->get(route('admin.brand-change-requests.index'));
        $response->assertOk();
        $response->assertSessionHasNoErrors();
        foreach ($brands as $brand) {
            $response->assertJsonFragment([
                'id' => $brand['id'],
                'title' => $brand['data']['title'],
                'where_to_find' => $brand['data']['where_to_find'],
                'url' => $brand['data']['url'],
                'description' => $brand['data']['description'],
                'created_by' => $brand['data']['created_by'],
            ]);
        }
    }

    /** @test */
    public function it_can_reject_a_brand_change_request(): void
    {
        /** @var BrandChangeRequest $brandChangeRequest */
        $brandChangeRequest = BrandChangeRequest::factory()->create();
        $response = $this->post(route('admin.brand-change-requests.reject', ['brand_change_request' => $brandChangeRequest->id]));
        $response->assertOk();
        $this->assertNull(BrandChangeRequest::find($brandChangeRequest->id));
        $this->assertDatabaseMissing(Brand::class, Arr::only($brandChangeRequest->data, ['title', 'where_to_find', 'url']));
        $this->assertDatabaseMissing(BrandChangeRequest::class, Arr::only($brandChangeRequest->toArray(), ['id']));
    }

    /**
     * @return array<int, mixed>
     */
    public function makeDummyRequestData(): array
    {
        $brand = BrandFactory::new()->raw();
        $brand['image']['id'] = $brand['image_id'];
        $user = User::factory()->create();
        $brand['created_by'] = $user->id;

        return [$brand, $user];
    }
}
