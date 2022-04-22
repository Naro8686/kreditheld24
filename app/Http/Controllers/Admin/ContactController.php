<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::paginate();
        return view('admin.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:contacts',
            'firstName' => 'required',
            'lastName' => 'required',
        ]);
        Contact::create($request->only(['email', 'firstName', 'lastName', 'phone']));
        return redirect()->route('admin.contacts.index')->with('success', __('Data saved successfully'));
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
        $contact = Contact::findOrFail($id);
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $request->validate([
            'email' => 'required|email|unique:contacts,email,' . $id,
            'firstName' => 'required',
            'lastName' => 'required',
        ]);
        $contact->email = $request->get('email', $contact->email);
        $contact->phone = $request->get('phone', $contact->phone);
        $contact->firstName = $request->get('firstName', $contact->firstName);
        $contact->lastName = $request->get('lastName', $contact->lastName);
        $contact->save();
        return redirect()->route('admin.contacts.index')->with('success', __('Data saved successfully'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', __('Contact deleted'));
    }
}
