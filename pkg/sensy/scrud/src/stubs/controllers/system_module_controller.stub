<?php

namespace App\Http\Controllers;

use App\Models\SystemModule;
use App\Models\SystemModuleCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemModuleController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = ucfirst('System Module');
    }

    public function index()
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        #Retrieve all SystemModules
        $listings = SystemModule::all();
        return view('pages.backend.system-modules.system-modules-index', compact('listings', 'title', 'sub_title'));
    }

    public function create()
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        $system_module_categories = SystemModuleCategory::all();

        return view('pages.backend.system-modules.system-modules-create', compact('title', 'sub_title', 'system_module_categories'));
    }

    public function store(Request $request)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        #Validate request data
        $data = $request->validate(['name' => 'required']);

        #check is active
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['has_add'] = $request->has('has_add') ? true : false;
        $data['has_show'] = $request->has('has_show') ? true : false;
        $data['has_update'] = $request->has('has_update') ? true : false;
        $data['has_destroy'] = $request->has('has_destroy') ? true : false;

        #Create a new SystemModule instance
        SystemModule::create($data);

        return redirect()->route('system-modules.index')->with('success', 'SystemModule created successfully');
    }

    public function show($id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            #Retrieve the SystemModule with the given ID
            $data = SystemModule::findOrFail($id);
            return view('pages.backend.system-modules.system-modules-show', compact('data', 'title', 'sub_title'));
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-modules.index')->with('error', 'Resource not found');
        }
    }

    public function edit($id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            #Retrieve the SystemModule with the given ID
            $data = SystemModule::findOrFail($id);
            $system_module_categories = SystemModuleCategory::all();

            return view('pages.backend.system-modules.system-modules-create', compact('data', 'title', 'sub_title', 'system_module_categories'));
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-modules.index')->with('error', 'Resource not found');
        }
    }

    public function update(Request $request, $id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            #Retrieve the SystemModule with the given ID
            $item = SystemModule::findOrFail($id);

            #Validate request data
            $data = $request->validate(['system_module_category_id'=>'required','name' => 'required']);

            ## Incase of image Upload
            ## $data['image_path'] = $this->uploadFile($request, 'image_path', ['required']);

            #check is active
            $data['is_active'] = $request->has('is_active') ? true : false;
            $data['has_add'] = $request->has('has_add') ? true : false;
            $data['has_show'] = $request->has('has_show') ? true : false;
            $data['has_update'] = $request->has('has_update') ? true : false;
            $data['has_destroy'] = $request->has('has_destroy') ? true : false;

            #Update the SystemModule instance
            $item->update($data);

            return redirect()->route('system-modules.index')->with('success', 'SystemModule updated successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-modules.index')->with('error', 'Resource not found');
        }
    }

    public function destroy($id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            #Retrieve the SystemModule with the given ID
            $data = SystemModule::findOrFail($id);

            #Delete the SystemModule instancesystem_module_model.stub
            $data->delete();

            return redirect()->route('system-modules.index')->with('success', 'SystemModule deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-modules.index')->with('error', 'Resource not found');
        }
    }
}
