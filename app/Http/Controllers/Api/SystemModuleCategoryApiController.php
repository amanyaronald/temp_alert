<?php
namespace App\Http\Controllers;

use App\Models\SystemModuleCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SystemModuleCategoryController extends Controller
{
    public $title;
    public $paginate = 10;

    public function __construct()
    {
        $this->title = ucfirst('System Module Category');
    }

    public function index(Request $request)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        $query = $request->input('query');

        if ($query) {
            # Perform search using the search method
            $listings = $this->search($query)->paginate($this->paginate);
        } else {
            # Retrieve all SystemModuleCategorys
            $listings = SystemModuleCategory::paginate($this->paginate);
        }

        return view('pages.backend.system-module-categories.system-module-categories-index', compact('listings', 'title', 'sub_title'));
    }

    public function create()
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;


        return view('pages.backend.system-module-categories.system-module-categories-create', compact('title', 'sub_title' ));
    }

    public function store(Request $request)
    {
        try {
            $title = $this->title;
            $sub_title = __FUNCTION__;

            # Validate request data
            $data = $request->validate(['position' => 'nullable|integer', 'name' => 'required', 'description' => 'nullable', 'is_active' => 'nullable']);

            # Bool State
            $data['is_active'] = $request->has('is_active') ? true : false;

            # Incase of image Upload
            # $data['image_path'] = $this->uploadFile($request, 'image_path', ['required']);

            # Create a new SystemModuleCategory instance
            SystemModuleCategory::create($data);

            return redirect()->route('system-module-categories.index')->with('success', 'SystemModuleCategory created successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            # Retrieve the SystemModuleCategory with the given ID
            $data = SystemModuleCategory::findOrFail($id);
            return view('pages.backend.system-module-categories.system-module-categories-show', compact('data', 'title', 'sub_title'));
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-module-categories.index')->with('error', 'Resource not found');
        }
    }

    public function edit($id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            # Retrieve the SystemModuleCategory with the given ID
            $data = SystemModuleCategory::findOrFail($id);


            return view('pages.backend.system-module-categories.system-module-categories-create', compact('data', 'title', 'sub_title' ));
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-module-categories.index')->with('error', 'Resource not found');
        }
    }

    public function update(Request $request, $id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            # Retrieve the SystemModuleCategory with the given ID
            $item = SystemModuleCategory::findOrFail($id);

            # Validate request data
            $data = $request->validate(['position' => 'nullable|integer', 'name' => 'required', 'description' => 'nullable', 'is_active' => 'nullable']);

            # Incase of image Upload
            # $data['image_path'] = $this->uploadFile($request, 'image_path', ['required']);

            # if (is_null($data['image']))
            #    $data['image'] = $item->image;

            # Bool State
            $data['is_active'] = $request->has('is_active') ? true : false;

            # Update the SystemModuleCategory instance
            $item->update($data);

            return redirect()->route('system-module-categories.index')->with('success', 'SystemModuleCategory updated successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-module-categories.index')->with('error', 'Resource not found');
        }
    }

    public function destroy($id)
    {
        $title = $this->title;
        $sub_title = __FUNCTION__;

        try {
            # Retrieve the SystemModuleCategory with the given ID
            $data = SystemModuleCategory::findOrFail($id);

            # Delete the SystemModuleCategory instance
            $data->delete();

            return redirect()->route('system-module-categories.index')->with('success', 'SystemModuleCategory deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('system-module-categories.index')->with('error', 'Resource not found');
        }
    }

    private function search($query)
    {
        # Perform search on SystemModuleCategory model
        #return SystemModuleCategory::where('column_name', 'LIKE', "%{$query}%")->paginate($this->paginate); // Adjust 'column_name' as needed
    Return SystemModuleCategory::where('position', 'LIKE', '%' . $query . '%')
						->orWhere('name', 'LIKE', '%' . $query . '%')
						->orWhere('description', 'LIKE', '%' . $query . '%')
						->orWhere('is_active', 'LIKE', '%' . $query . '%');
    }
}
