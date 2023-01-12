<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandChangeRequest;
use App\Http\Requests\UpdateBrandChangeRequest;
use App\Http\Resources\Admin\BrandChangeRequestCollection;
use App\Http\Resources\Admin\BrandChangeRequestResource;
use App\Http\Resources\Admin\BrandResource;
use App\Mail\BrandChangeRequestRejectionMail;
use App\Models\Brand;
use App\Models\BrandChangeRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class BrandChangeRequestController extends Controller
{
    /**
     * Store a Brand change request.
     *
     * @OA\Post (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","created_by"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                 ),
     *                 @OA\Property(
     *                     property="brand_id",
     *                     type="integer",
     *                     description="brand_id",
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="URL of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="image_id",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="Where to find",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="Create user ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand change request created.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     *
     * @param StoreBrandChangeRequest $request
     * @return BrandChangeRequestResource
     */
    public function store(StoreBrandChangeRequest $request): BrandChangeRequestResource
    {
        /** @var BrandChangeRequest $change */
        $brandChangeRequest = BrandChangeRequest::create([
            'data' => $request->validated(),
            'brand_id' => $request->brand_id ?? null,
        ]);

        return new BrandChangeRequestResource($brandChangeRequest);
    }

    /**
     * Approve a Brand change request.
     *
     * @OA\Post (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests/{brand_change_request}/approve",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="brand_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand change request approved",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param BrandChangeRequest $brandChangeRequest
     * @return BrandResource
     */
    public function approve(BrandChangeRequest $brandChangeRequest): BrandResource
    {
        DB::beginTransaction();
        $brand = Brand::updateOrCreate(['id' => $brandChangeRequest->brand->id ?? null], $brandChangeRequest->data);
        $brandChangeRequest->delete();
        DB::commit();

        return new BrandResource($brand);
    }

    /**
     * Reject a Brand change request.
     *
     * @OA\Post (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests/{brand_change_request}/reject",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *    @OA\Parameter(
     *      name="brand_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand change request rejected",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Email send failed",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand Change Request not found",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param BrandChangeRequest $brandChangeRequest
     * @return JsonResponse
     */
    public function reject(BrandChangeRequest $brandChangeRequest): JsonResponse
    {
        $user = User::findOrFail($brandChangeRequest->data['created_by']);

        try {
            Mail::send(new BrandChangeRequestRejectionMail($brandChangeRequest, $user));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email küldés sikertelen'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $brandChangeRequest->delete();

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * Update a brand change request.
     *
     * @OA\Put (
     *     tags={"BrandChangeRequests"},
     *     path="/admin/brand-change-requests/{brand_change_request}",
     *    @OA\Parameter(
     *      name="brand_change_request",
     *      in="path",
     *      description="Brand change request id",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 required={"title","created_by"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                 ),
     *                 @OA\Property(
     *                     property="brand_id",
     *                     type="integer",
     *                     description="brand_id",
     *                 ),
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="URL of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the brand",
     *                 ),
     *                 @OA\Property(
     *                     property="image_id",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property(
     *                     property="where_to_find",
     *                     type="string",
     *                     description="Where to find",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="Create user ID",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand change request updated.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     *
     * @param UpdateBrandChangeRequest $request
     * @param BrandChangeRequest $brandChangeRequest
     * @return BrandChangeRequestResource
     */
    public function update(UpdateBrandChangeRequest $request, BrandChangeRequest $brandChangeRequest): BrandChangeRequestResource
    {
        $brandChangeRequest->data = $request->validated();
        $brandChangeRequest->brand_id = $request->brand_id ?? null;
        $brandChangeRequest->save();

        return new BrandChangeRequestResource($brandChangeRequest);
    }

    /**
     * Show a selected Brand change request.
     * @OA\Get(
     *    tags={"BrandChangeRequests"},
     *    path="/admin/brand-change-requests/{brand_change_request}",
     *    @OA\Response(
     *      response="200",
     *      description="Display a selected Brand Change Request.",
     *      @OA\JsonContent()
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *    @OA\Parameter(
     *         name="brand_change_request",
     *         in="path",
     *         required=true,
     *         description="Brand Change Request ID",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Brand change request Not Found.",
     *         @OA\JsonContent()
     *    ),
     * )
     * @param BrandChangeRequest $brandChangeRequest
     * @return BrandChangeRequestResource
     */
    public function show(BrandChangeRequest $brandChangeRequest): BrandChangeRequestResource
    {
        return new BrandChangeRequestResource($brandChangeRequest);
    }

    /**
     * List of Brand change request.
     * @OA\Get(
     *    tags={"BrandChangeRequests"},
     *    path="/admin/brand-change-requests",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of brand change requests.",
     *      @OA\JsonContent()
     *    ),
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *    )
     * )
     *
     * Display a list of the resource.
     *
     * @param Request $request
     * @return BrandChangeRequestCollection
     */
    public function index(Request $request): BrandChangeRequestCollection
    {
        return new BrandChangeRequestCollection(
            BrandChangeRequest::query()
                ->orderByDesc('created_at')
                ->paginate($request->get('size', 20))
        );
    }
}