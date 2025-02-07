<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\Attachement;
use App\Services\AttachementService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class CustomerService
{

protected $attachmentService;
 public function __construct(AttachementService $attachementService) {
    $this->attachmentService = $attachementService;
}
    /**
     * Retrieve all users with pagination.
     *
     * Fetch paginated users
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getCustomers()
    {
        return Customer::paginate(10);
    }

    /**
     * Create a new user with the provided data.
     *
     * @param array $data The validated data to create a user.
     * @return Customer|null The created user object on success, or null on failure.
     */
    public function storeCustomer(array $data)
    {
        $customer = Customer::create($data);
        return $customer;
    }

    /**
     * Update an existing user with the provided data.
     *
     * @param Customer $customer  The user to update.
     * @param array $data The validated data to update the user.
     * @return Customer|null The updated user object on success, or null on failure.
     */
    public function updateCustomer(Customer $customer, array $data)
    {
        $customer->update(array_filter($data));
        return $customer;
    }
    public function AddAttachement($customer,  $request)
    {
        // Get the customer
        $customer = Customer::findOrFail($customer); // Ensure $customer is an object

        if(Auth::check() && $customer->id === Auth::id())
        // Store the attachment
        $url = $this->attachmentService->storeAttachement($customer, $request);

        // If the attachment was stored successfully, return the URL
        if ($url) {
            return response()->json(['url' => $url.'  '.'uploaded successfully'], 200);
        } else {
            // If there was an error, return an error response
            return response()->json(['error' => 'Failed to upload attachment'], 500);
        }
    }
    public function deleteAttachement($customerId,$photoId)
    {
        $customer = Customer::findOrFail($customerId);
        $photo = Attachement::where('id', $photoId)->first();
        // Check if the authenticated user is the owner of the company
        if (Auth::check() && $customer->id === Auth::id()) {
                $this->attachmentService->deletePhoto($photo);

        }
    }


}
