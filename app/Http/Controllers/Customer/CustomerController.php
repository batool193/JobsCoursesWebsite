<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\AttachementService;
use Illuminate\Support\Facades\Auth;
use App\Services\Customer\CustomerService;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;

class CustomerController extends Controller
{

    protected CustomerService $customerservice;

    public function __construct(CustomerService $customerservice)
    {
        $this->customerservice = $customerservice;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $users = $this->customerservice->getCustomers();
        return self::success($users, 'Customers retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $user = $this->customerservice->storeCustomer($request->validationData());
        return self::success($user, 'Customer created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        return self::success($customer->load('attachements'), 'Customer retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $updatedCustomer = $this->customerservice->updateCustomer($customer, $request->validationData());
        return self::success($updatedCustomer, 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        if(Auth::check() && $customer->id === Auth::id())
        {$customer->delete();
        return self::success(null, 'Customer deleted successfully');}
        else
        return self::error(null,'you do not have permission');
    }

    /**
     * Add an attachment to a customer.
     *
     * Adds an attachment to the specified customer in the database.
     *
     * @param int $customer
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddAttachement($customer, Request $request)
    {
        $attachement = $this->customerservice->AddAttachement($customer, $request);
        return self::success($attachement, 'Add Attachement for Customer successfully');
    }
    public function deleteAttachement($customerId,$photoId) {
        $this->customerservice->deleteAttachement($customerId,$photoId);
         return self::success(null, 'Photo deleted successfully');
        }
}
