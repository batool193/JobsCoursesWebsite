<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CompanySubscriptionController extends Controller
{
    /*
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(Company $company,Subscription $subscription): JsonResponse
    {
        if(Auth::check() && $company->user_id === Auth::id())
        $company->subscriptions()->attach($subscription->id);
        return self::success(null, 'Subscribed successfully', 201);
    }

    /*
     * Display the specified resource.
     */
    public function show(Company $company): JsonResponse
    {
        $subscription =   $company->subscriptions()->get()->makeHidden('pivot');;
        return self::success($subscription, 'Subscriptions retrieved successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company,Subscription $subscription): JsonResponse
    {
        if(Auth::check() && $company->user_id === Auth::id())
        $company->subscriptions()->detach($subscription->id);
        return self::success(null, 'Subscription Removed successfully');
    }
}
