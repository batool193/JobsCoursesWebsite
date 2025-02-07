<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class JobService
{
    /**
     * Retrieve all jobs with pagination.
     *
     * Fetch paginated jobs
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getJobs()
    {
        return Job::paginate(10);
    }

    /**
     * Create a new Job with the provided data.
     *
     * @param array $data The validated data to create a Job.
     * @return Job|null The created Job object on success, or null on failure.
     */
    public function storeJob(array $data)
    {
        $company = Company::findOrFail($data['company_id']);

        if (Auth::check() && $company->user_id === Auth::id()) {
            // Get the company's active subscriptions
            $subscription = $company->subscriptions()
                ->where('ends', '>=', now())
                ->where('subscriptions.id', $data['subscription_id'])
                ->first();
            // Check each subscription individually
            if ($subscription) {
                $currentJobCount = $company->jobs()
                    ->where('subscription_id', $subscription->id)
                    ->count();

                if ($currentJobCount < $subscription->jobs_count) {
                    $job = Job::create([
                        'title'=> $data["title"],
                        'description'=> $data["description"],
                        'company_id'=> $data["company_id"],
                        'subscription_id'=> $data['subscription_id']
                    ]);
                }
            }
            return $job;
        }
    }

    /**
     * Update an existing Job with the provided data.
     *
     * @param Job $job  The Job to update.
     * @param array $data The validated data to update the Job.
     * @return Job|null The updated Job object on success, or null on failure.
     */
    public function updateJob(Job $job, array $data)
    {
        $company = $job->company;
        if (Auth::check() && $company->user_id === Auth::id()) {
            $job->update(array_filter($data));
        }
        $job->makeHidden('company');
        return $job;
    }
}
