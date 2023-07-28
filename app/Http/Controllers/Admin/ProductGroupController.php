<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Product\Group as ProductGroup;

class ProductGroupController extends Controller
{
    /** Display page for create product group */
    public function create(Request $request)
    {        
        return view('admin.product.group.create');
    }

    /** Handle request for store action */
    public function store (Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.product-group.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newProductGroup = new ProductGroup($data);
        if (!$newProductGroup->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.product-group.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($newProductGroup->toArray())->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.product-group.create.form')->with(['response' => $responseData]);
    }

    /** Display the page for create new product group */
    public function show(Request $request, ProductGroup $group)
    {       
        return view('admin.product.group.show', [
            'productGroup' => $group->toArray(),
        ]);
    }

    /** Display the page for create new product group */
    public function edit(Request $request, ProductGroup $group)
    {
        return view('admin.product.group.edit', [
            'productGroup' => $group->toArray(),
        ]);
    }
    
    /** Handle request update product group details */
    public function update(Request $request, ProductGroup $group)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.product-group.edit.form', ['group' => $group->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        if (!$group->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.product-group.edit.form', ['group' => $group->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($group->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.product-group.show', ['group' => $group->id])->with(['response' => $responseData]);
    }    

    /** Validate form request for store and update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required"],
        ]);

        return $validator;
    }

    /** Format the data before saving to database */
    private function formatRequestData(Request $request)
    {
        $data = $request->all();
        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }
        return collect($data)->only([
            'name',
            'description',
            'note',
        ])->toArray();
    }
}
