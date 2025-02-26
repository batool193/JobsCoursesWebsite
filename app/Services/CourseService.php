<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Company;
use App\Models\Attachement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class CourseService
{
    protected $attachmentService;
 public function __construct(AttachementService $attachementService) {
    $this->attachmentService = $attachementService;
}
     /**
     * Retrieve all courses with pagination.
     *
     * Fetch paginated courses
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getCourses()
    {
        return Course::paginate(10);
    }

    /**
     * Create a new Course with the provided data.
     *
     * @param array $data The validated data to create a Course.
     * @return Course|null The created Course object on success, or null on failure.
     */
    public function storeCourse(array $data)
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
                $currentCourseCount = $company->courses()
                    ->where('subscription_id', $subscription->id)
                    ->count();

                if ($currentCourseCount < $subscription->courses_count) {
                    $course = Course::create([
                        'title'=> $data["title"],
                        'subtitles'=> $data["subtitles"],
                        'price'=> $data["price"],
                        'company_id'=> $data["company_id"],
                        'subscription_id'=> $data['subscription_id']
                    ]);
                }
            }
            return $course;
        }
    }

    /**
     * Update an existing Course with the provided data.
     *
     * @param Course $course  The Course to update.
     * @param array $data The validated data to update the Course.
     * @return Course|null The updated Course object on success, or null on failure.
     */
    public function updateCourse(Course $course, array $data)
    {
         $company = $course->company;
         if(Auth::check() && $company->user_id === Auth::id())
        {$course->update(array_filter($data));}
        $course->makeHidden('company');
        return $course;
    }

    public function AddAttachement($course,  $request)
    {
        // Get the course
        $course = Course::findOrFail($course); // Ensure $course is an object
        $company = $course->company;
        if(Auth::check() && $company->user_id === Auth::id())
        // Store the attachment
        $url = $this->attachmentService->storeAttachement($course, $request);

        // If the attachment was stored successfully, return the URL
        if ($url) {
            return response()->json(['url' => $url], 200);
        } else {
            // If there was an error, return an error response
            return response()->json(['error' => 'Failed to upload attachment'], 500);
        }
    }
    public function deleteAttachement($courseId,$videoId)
    {

        $course = Course::findOrFail($courseId);
       $video = Attachement::where('id', $videoId)->first();
        $company = Company::findOrFail($course->company_id);
        // Check if the authenticated user is the owner of the company
        if (Auth::check() && $company->user_id == Auth::user()->id) {
            $this->attachmentService->deletePhoto($video);
        }
    }


}
