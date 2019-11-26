<?php

namespace App\Http\Controllers;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @group Customers
 *
 * APIs for managing customers
 */
class CustomerController extends Controller
{
    public function getCustomers(Request $request)
    {
        return response()->json(Customer::all(), 200);
    }

    public function getCustomer(int $customer_id, Request $request)
    {
        return response()->json(Customer::query()->where('id', $customer_id)->first(), 200);
    }

    public function createCustomer(Request $request)
    {
        if (Customer::query()->where("id", $request->id)->exists()) {
            return response(['status' => 'customer already exists'], 409);
        }

        $customer = new Customer(['name' => $request->name, 'random_number' => $request->random_number, 'company_id' => $request->company_id]);
        $customer->save();

        return response(['status' => 'customer created', 'id' => $customer->id, 'data' => $customer], 201);
    }

    public function updateCustomer(Request $request)
    {
        $customer = Customer::query()->where("id", $request->id)->first();

        if (empty($customer)) {
            return response(['status' => 'customer not found'], 404);
        }

        $customer->name = $request->name ? $request->name : $customer->name;
        $customer->random_number = $request->random_number ? $request->random_number : $customer->random_number;
        $customer->save();

        return response(['status' => 'customer updated', 'id' => $customer->id, 'data' => $customer], 200);
    }

    public function deleteCustomer(int $customer_id, Request $request)
    {
        $customer = Customer::query()->where('id', $customer_id);
        if (empty($customer)) {
            return response(['status' => 'customer not found'], 404);
        }

        $customer->delete();

        return response(['status' => 'customer deleted', 'id' => $customer_id], 200);
    }

    public function getTransactionLog(Request $request)
    {
        $startDate = Carbon::create($request->query('start_date')); // start_date has to be UTC
        $created = Customer::query()->where('created_at', '>', $startDate)->select('id', 'created_at')->get()->toArray();
        $updated = Customer::query()->where('updated_at', '>', $startDate)->whereColumn('updated_at', '!=', 'created_at')->select('id', 'updated_at')->get()->toArray();
        $deleted = Customer::onlyTrashed()->where('deleted_at', '>', $startDate)->select('id', 'deleted_at')->get()->makeVisible(['deleted_at'])->toArray();
        return response(['created' => $created, 'updated' => $updated, 'deleted' => $deleted], 200);
    }
}
