<?php

namespace Sensy\Scrud\Traits;

use Illuminate\Http\Request;

trait FileUploadTrait
{
    public function uploadFile(Request $request, $param, $rules = ['required'], $fileName = null, $fileObject = null, $location = null)
    {
        if ($request->hasFile($param)) {

            $file = $request->file($param);

            // if (isset($rules))
            //     $request->validate([$param => $rules]);

            // # Check for file size
            // $fileSize = $request->file($param)->getSize();
            // if ($fileSize > (1024 * 1024 * 5)) {
            //     return response()->json(['error' => 'File size should be less than 5MB'], 422);
            // }

            // Check for multiple
            if (! is_null($fileObject)) {
                dd($fileObject);
                $file = $fileObject;
            } else {
                $file = $request->file($param);
            }

            if (isset($fileName)) {
                $filename = str_replace(' ', '_', $fileName).'.'.$file->getClientOriginalExtension();
            } else {
                $filename = time().'-'.rand(rand(0, 1000), rand(1000, 2000)).'.'.$file->getClientOriginalExtension();
            }

            if (isset($location)) {
                $path = $file->storeAs($location, $filename);
            } else {
                $path = $file->storeAs('uploads/'.strtolower(str_replace(' ', '_', $this->title)), $filename);
            }

            return $path;
        }

        return null;
    }
}
