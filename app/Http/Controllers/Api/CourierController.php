<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourierRequest;
use App\Http\Requests\UpdateCourierRequest;
use App\Models\Courier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourierController extends Controller
{
    /**
     * Display a paginated listing of couriers.
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $perPage = min(max($request->integer('per_page', 15), 1), 100);

        return Courier::query()
            ->search($request->query('search'))
            ->filterLevel($request->query('level'))
            ->sort($request->query('sort'))
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Store a newly created courier.
     */
    public function store(StoreCourierRequest $request): JsonResponse
    {
        $courier = Courier::create($request->validated());

        return response()->json($courier, Response::HTTP_CREATED);
    }

    /**
     * Display the specified courier.
     */
    public function show(Courier $courier): Courier
    {
        return $courier;
    }

    /**
     * Update the specified courier.
     */
    public function update(UpdateCourierRequest $request, Courier $courier): Courier
    {
        $courier->update($request->validated());

        return $courier;
    }

    /**
     * Remove the specified courier.
     */
    public function destroy(Courier $courier): Response
    {
        $courier->delete();

        return response()->noContent();
    }
}
