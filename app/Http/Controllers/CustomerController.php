<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Storage;
use Throwable;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('isCustomer', 1)->latest()->paginate(15);
        return view('customer.index', compact('customers'));
    }
    public function create()
    {
        return view('customer.create');
    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|min:8',
            ]);

            if (
                $data['password'] == $data['password_confirmation']
            ) {

                $data['password'] = Hash::make($data['password']);
                $customer = User::create($data);
            } else {
                return back()->with('error', 'password does not match');
            }
            return redirect()->route('customer.index')->with('success', 'customer created successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $customer = User::findOrFail($id);
            return view('customer.edit', compact('customer'));
        } catch (Throwable $th) {
            dd($th->getMessage);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $customer = User::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
            ]);
            // dd($request->all(), $customer, $data);
            $customer->name = $data['name'];
            $customer->email = $data['email'];

            $customer->save();
            return redirect()->route('customer.index')->with('success', 'customer updated successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }

    }
    public function destroy(Request $request)
    {
        try {
            $id = $request->validate([
                'id' => 'required'
            ]);
            $customer = User::find($id);
            if (empty($customer)) {
                return back()->with('errors', 'the specific id does not exist');
            }
            $customer[0]->delete();
            return redirect()->route('customer.index')->with('success', 'product deleted successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

}
