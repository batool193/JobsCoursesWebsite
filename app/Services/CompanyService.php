<?php

namespace App\Services;

use App\Models\Attachement;
use Exception;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompanyService
{
    protected $attachmentService;
 public function __construct(AttachementService $attachementService) {
    $this->attachmentService = $attachementService;
}
    /**
     * Retrieve all companies with pagination.
     *
     * Fetch paginated companies
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getCompanies()
    {
        return Company::with('attachements')->paginate(10);
    }

    /**
     * Create a new Company with the provided data.
     *
     * @param array $data The validated data to create a Company.
     * @return Company|null The created Company object on success, or null on failure.
     */
    public function storeCompany(array $data)
    {
        $user = auth('api')->user();
        $company = Company::create([
            'name' => $data["name"],
            'email' => $data["email"],
            'user_id' => $user->id,
        ]);
        return $company;
    }

    /**
     * Update an existing Company with the provided data.
     *
     * @param Company $company  The Company to update.
     * @param array $data The validated data to update the Company.
     * @return Company|null The updated Company object on success, or null on failure.
     */
    public function updateCompany(Company $company, array $data)
    {
        $company->update(array_filter($data));
        return $company;
    }

    public function AddAttachement($company, $request)
    {
        // Get the company
        $company = Company::findOrFail($company);

        if (Auth::check() && $company->user_id === Auth::id())
            // Store the attachment
            $url = $this->attachmentService->storeAttachement($company, $request);

        // If the attachment was stored successfully, return the URL
        if ($url) {
            return response()->json(['url' => $url], 200);
        } else {
            // If there was an error, return an error response
            return response()->json(['error' => 'Failed to upload attachment'], 500);
        }
    }
    public function deleteAttachement($companyId,$logoId)
    {
        $company = Company::findOrFail($companyId);
        $photo = Attachement::where('id', $logoId)->first();
        // Check if the authenticated user is the owner of the company
        if (Auth::check() && $company->user_id === Auth::id()) {
                $this->attachmentService->deletePhoto($photo);

        }
    }
}
