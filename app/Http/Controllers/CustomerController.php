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
        return response()->json(Customer::all());
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
        $customer = Customer::query()->where('id', $request->id);
        if (empty($customer)) {
            return response(['status' => 'customer not found'], 404);
        }

        $customer->delete();

        return response(['status' => 'customer deleted', 'id' => $customer->id], 200);
    }

    public function getTransactionLog(Request $request) {
        $startDate = Carbon::create($request->query('start_date'));
        $created = Customer::query()->whereDate('created_at', '>', $startDate)->get()->pluck('id');
        $updated = Customer::query()->whereDate('updated_at', '>', $startDate)->whereDate('updated_at', '!=', 'created_at')->get()->pluck('id');
        $deleted = Customer::query()->whereNotNull('deleted_at')->whereDate('deleted_at', '>', $startDate)->get()->pluck('id';
        return response(['created' => $created, 'updated' => $updated, 'deleted' => $deleted], 200);
    }
}
