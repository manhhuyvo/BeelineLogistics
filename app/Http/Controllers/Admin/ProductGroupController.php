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
    /** Display the page for list of all product groups */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allProductGroups = ProductGroup::with('products');

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || $key == 'page' || $key == '_method') {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allSuppliers = $allProductGroups->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allProductGroups = $allProductGroups->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allProductGroups = $allProductGroups->appends(request()->except('page'));        
        $returnData = collect($allProductGroups)->only('data')->toArray();
        $returnData['data'] = collect($returnData['data'])->map(function($eachGroup) {
            $eachGroup['productCount'] = count($eachGroup['products']);

            return collect($eachGroup)->except(['products'])->toArray();
        });
        $paginationData = collect($allProductGroups)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.product.group.list', [
            'productGroups' => $returnData,
            'pagination' => $paginationData,
            'request' => $data,
        ]);
    }

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

    /** Handle request delete supplier */
    public function destroy(Request $request, ProductGroup $group)
    {         
        // Perform deletion
        if (!$group->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.product-group.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($group->toArray())->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.product-group.list')->with(['response' => $responseData]);
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
