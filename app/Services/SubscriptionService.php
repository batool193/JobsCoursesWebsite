<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class SubscriptionService
{
     /**
     * Retrieve all subscriptions with pagination.
     *
     * Fetch paginated subscriptions
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getSubscriptions()
    {
        return Subscription::paginate(10);
    }

    /**
     * Create a new Subscription with the provided data.
     *
     * @param array $data The validated data to create a Subscription.
     * @return Subscription|null The created Subscription object on success, or null on failure.
     */
    public function storeSubscription(array $data)
    {
        $subscription = Subscription::create($data);
        return $subscription;
    }

    /**
     * Update an existing Subscription with the provided data.
     *
     * @param Subscription $subscription  The Subscription to update.
     * @param array $data The validated data to update the Subscription.
     * @return Subscription|null The updated Subscription object on success, or null on failure.
     */
    public function updateSubscription(Subscription $subscription, array $data)
    {
        $subscription->update(array_filter($data));
        return $subscription;
    }


}
