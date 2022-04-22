<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formula;
use App\Traits\File;
use Illuminate\Http\Request;

class FormulaController extends Controller
{
    use File;

    public function index()
    {
        $formulas = Formula::paginate();
        return view('admin.formulas.index', compact('formulas'));
    }

    public function create()
    {
        return view('admin.formulas.create');
    }

    public function store(Request $request)
    {
        $uploadFileTypes = implode(',', Formula::$uploadFileTypes);
        $uploadFileMaxSize = Formula::MAX_FILE_SIZE;
        $request->validate([
            'name' => 'required|unique:formulas',
            'document' => "required|mimes:$uploadFileTypes|max:$uploadFileMaxSize",
        ]);
        $request->merge(['file' => $this->fileUpload($request->file('document'), Formula::UPLOAD_FILE_PATH)]);
        Formula::create($request->only(['name', 'file']));
        return redirect()->route('admin.formulas.index')->with('success', __('Data saved successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $formula = Formula::findOrFail($id);
        return view('admin.formulas.edit', compact('formula'));
    }

    public function update(Request $request, $id)
    {
        $uploadFileTypes = implode(',', Formula::$uploadFileTypes);
        $uploadFileMaxSize = Formula::MAX_FILE_SIZE;
        $formula = Formula::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:formulas,name,' . $id,
            'document' => "sometimes|nullable|mimes:$uploadFileTypes|max:$uploadFileMaxSize",
        ]);
        $formula->name = $request->get('name', $formula->name);
        if ($request->hasFile('document')){
            $this->deleteFiles([$formula->file]);
            $formula->file = $this->fileUpload($request->file('document'), Formula::UPLOAD_FILE_PATH);
        }
        $formula->save();
        return redirect()->route('admin.formulas.index')->with('success', __('Data saved successfully'));
    }

    public function destroy($id)
    {
        $formula = Formula::findOrFail($id);
        $this->deleteFiles([$formula->file]);
        $formula->delete();
        return redirect()->route('admin.formulas.index')->with('success', __('Formula deleted'));
    }
}
