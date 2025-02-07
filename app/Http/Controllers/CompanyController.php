<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use App\Services\AttachementService;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{
    protected CompanyService $companyservice;

    public function __construct(CompanyService $companyservice)
    {
        $this->companyservice = $companyservice;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $companies = $this->companyservice->getCompanies();
        return self::success($companies, 'Companies retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->companyservice->storeCompany($request->validated());
        return self::success($company, 'Company created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): JsonResponse
    {
        return self::success($company->with('attachements')->get(), 'Company retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $updatedcompany = $this->companyservice->updateCompany($company, $request->validated());
        return self::success($updatedcompany, 'Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): JsonResponse
    {
       if(Auth::check() && $company->user_id === Auth::id())
        $company->delete();
        return self::success(null, 'Company deleted successfully');
    }

    /**
     * Add an attachment to a company.
     *
     * Adds an attachment to the specified company in the database.
     *
     * @param int $company
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddAttachement($company, Request $request)
    {
        $attachement = $this->companyservice->AddAttachement($company, $request);
        return self::success($attachement, 'Add Logo to Company successfully');
    }
    public function deleteAttachement($companyId,$logoId) {
        $this->companyservice->deleteAttachement($companyId,$logoId);
         return self::success(null, 'Logo deleted successfully');
        }
}
