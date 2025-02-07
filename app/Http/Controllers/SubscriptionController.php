<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use App\Services\SubscriptionService;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;

class SubscriptionController extends Controller
{

    protected SubscriptionService $subscriptionservice;

    public function __construct(SubscriptionService $subscriptionservice)
    {
        $this->subscriptionservice = $subscriptionservice;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $subscriptions = $this->subscriptionservice->getSubscriptions();
        return self::success($subscriptions, 'Subscriptions retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->subscriptionservice->storeSubscription($request->validated());
        return self::success($subscription, 'Subscription created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription): JsonResponse
    {
        return self::success($subscription, 'Subscription retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): JsonResponse
    {
        $updatedsubscription = $this->subscriptionservice->updateSubscription($subscription, $request->validated());
        return self::success($updatedsubscription, 'Subscription updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();
        return self::success(null, 'Subscription deleted successfully');
    }
}
