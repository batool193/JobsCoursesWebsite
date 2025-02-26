<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use App\Services\AttachementService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Company;

class CourseController extends Controller
{
    protected CourseService $courseservice;
    public function __construct(CourseService $courseservice)
    {
        $this->courseservice = $courseservice;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $courses = $this->courseservice->getCourses();
        return self::success($courses, 'courses retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseservice->storeCourse($request->validated());
        return self::success($course, 'Course created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): JsonResponse
    {
       $company = Company::find($course->company_id);
      $logo =  $company->attachements()->get('file_path');
      $course = $course->load('attachements');

        return self::success(['course' =>$course ,'company_name'=>$company->name,'logo'=>$logo], 'Course retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $updatedcourse = $this->courseservice->updateCourse($course, $request->validated());
        return self::success($updatedcourse, 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): JsonResponse
    {
        $company = $course->company;
         if(Auth::check() && $company->user_id === Auth::id())
        $course->delete();
        return self::success(null, 'Course deleted successfully');
    }

    /**
     * Add an attachment to a course.
     *
     * Adds an attachment to the specified course in the database.
     *
     * @param int $course
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddAttachement($course, Request $request)
    {
        $attachement = $this->courseservice->AddAttachement($course, $request);
        return self::success($attachement, 'Add Attachement to Course successfully');
    }

    public function deleteAttachement($courseId,$videoId) {
        $this->courseservice->deleteAttachement($courseId,$videoId);
         return self::success(null, 'Video deleted successfully');
        }

}
