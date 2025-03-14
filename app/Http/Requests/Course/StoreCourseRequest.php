<?php

namespace App\Http\Requests\Course;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;



class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:50'],
            'subtitles' => ['required', 'string'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'company_id' => ['required', 'exists:companies,id'],
            'subscription_id' => ['required', 'exists:subscriptions,id'],
        ];
    }

    /**
     * Define human-readable attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'Course Title',
            'subtitles' => 'Course Subtitles',
            'price' => 'price',
            'company_id' => 'Company',
        ];
    }

    /**
     * Define custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'string' => 'the :attribute must be string',
            'exists' => 'The :attribute must exist.',
            'numeric' => 'The :attribute must be numeric.',

        ];
    }

    /**
     * Handle validation errors and throw an exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator The validation instance.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'A server error has occurred',
                'errors' => $errors,
            ], 400)
        );
    }
}
