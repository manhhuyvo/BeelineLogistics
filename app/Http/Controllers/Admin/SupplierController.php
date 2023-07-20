<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /** Display the page for list of all suppliers */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allSuppliers = Supplier::with('account');

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
                $allSuppliers = $allSuppliers->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allSuppliers = $allSuppliers->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allSuppliers = $allSuppliers->appends(request()->except('page'));        
        $returnData = collect($allSuppliers)->only('data')->toArray();
        $paginationData = collect($allSuppliers)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.supplier.list', [
            'suppliers' => $returnData,
            'pagination' => $paginationData,   
            'supplierStatusColors' => Supplier::MAP_STATUSES_COLOR,         
            'supplierTypes' => Supplier::MAP_TYPES,
            'supplierStatuses' => Supplier::MAP_STATUSES,
            'request' => $data,
        ]);
    }

    /** Display page for create new supplier */
    public function create(Request $request)
    {
        return view('admin.supplier.create', [            
            'supplierTypes' => Supplier::MAP_TYPES,
            'supplierStatuses' => Supplier::MAP_STATUSES,
        ]);
    }

    /** Handle request for create new supplier */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.supplier.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newSupplier = new Supplier($data);
        if (!$newSupplier->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.supplier.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($newSupplier->toArray())->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.supplier.create.form')->with(['response' => $responseData]);
    }

    /** Display the page for create new supplier */
    public function show(Request $request, Supplier $supplier)
    {       
        return view('admin.supplier.show', [
            'supplier' => $supplier->toArray(),
            'supplierTypes' => Supplier::MAP_TYPES,
            'supplierStatuses' => Supplier::MAP_STATUSES,
            'supplierStatusColors' => Supplier::MAP_STATUSES_COLOR,
        ]);
    }

    /** Display the page for update supplier details */
    public function edit(Request $request, Supplier $supplier)
    {    
        return view('admin.supplier.edit', [
            'supplier' => $supplier->toArray(),
            'supplierTypes' => Supplier::MAP_TYPES,
            'supplierStatuses' => Supplier::MAP_STATUSES,
            'supplierStatusColors' => Supplier::MAP_STATUSES_COLOR,
        ]);
    }

    /** Handle request update supplier details */
    public function update(Request $request, Supplier $supplier)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.supplier.edit.form', ['supplier' => $supplier->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        if (!$supplier->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.supplier.edit.form', ['supplier' => $supplier->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.supplier.show', ['supplier' => $supplier->id])->with(['response' => $responseData]);
    }    

    /** Handle request delete supplier */
    public function destroy(Request $request, Supplier $supplier)
    {         
        // Perform deletion
        if (!$supplier->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.supplier.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.supplier.list')->with(['response' => $responseData]);
    }

    /** Validate form request for store and update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "full_name" => ["required", "regex:/^[a-zA-Z\s]+$/"],
            "phone" => ["required", "regex:/^[0-9\s]+$/"],
            "address" => ["required"],
            "type" => ["required", "integer"],
            "status" => ["required", "integer"],
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
            'full_name',
            'phone',
            'address',
            'company',
            'status',
            'type',
            'note',
        ])->toArray();
    }
}
