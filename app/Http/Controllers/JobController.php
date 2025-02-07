<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;

class JobController extends Controller
{
    protected JobService $jobService;
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $jobs = $this->jobService->getJobs();
        return self::success($jobs, 'Jobs retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreJobRequest $request): JsonResponse
    {
        $job = $this->jobService->storeJob($request->validated());
        return self::success($job, 'Job created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): JsonResponse
    {
        return self::success($job, 'Job retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        $updatedjob = $this->jobService->updateJob($job, $request->validated());
        return self::success($updatedjob, 'Job updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job): JsonResponse
    {
        $company = $job->company;
        if (Auth::check() && $company->user_id === Auth::id())
            $job->delete();
        return self::success(null, 'Job deleted successfully');
    }
}
