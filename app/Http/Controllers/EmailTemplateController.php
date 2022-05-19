<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = auth()->user()->emailTemplates()->paginate();
        return view('email-templates.index', compact('templates'));
    }


    public function create()
    {
        return view('email-templates.create');
    }

    public function store(Request $request)
    {
        $user_id = auth()->id();
        $request->merge(['user_id' => $user_id]);
        $request->validate([
            'name' => 'required|unique:email_templates,name,NULL,id,user_id,' . $user_id,
            'content' => 'required'
        ]);
        EmailTemplate::create($request->only(['user_id', 'name', 'content']));
        return redirect()->route('email-templates.index')->with('success', __('Data saved successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\EmailTemplate $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = auth()->user()->emailTemplates()->findOrFail($id);
        return $template->content;
    }

    public function edit($id)
    {
        $template = auth()->user()->emailTemplates()->findOrFail($id);
        return view('email-templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = auth()->user()->emailTemplates()->findOrFail($id);
        $user_id = $template->user_id;
        $request->validate([
            'name' => "required|unique:email_templates,name,$template->id,id,user_id,$user_id",
            'content' => 'required'
        ]);
        $template->name = $request->get('name', $template->name);
        $template->content = $request->get('content', $template->content);
        $template->save();
        return redirect()->route('email-templates.index')->with('success', __('Data saved successfully'));
    }

    public function destroy($id)
    {
        $template = auth()->user()->emailTemplates()->findOrFail($id);
        $template->delete();
        return redirect()->back()->with('success', __('Data saved successfully'));
    }
}
