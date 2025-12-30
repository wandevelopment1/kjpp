<?php

namespace App\Http\Controllers\Page;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Services\UiConfigService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {

        return view('page.contact.index');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Kirim email
        $uiConfigService = app(UiConfigService::class);
        $emailTo = $uiConfigService->getValueByGroupSlugAndKey('contact', 'email1') ?? 'admin@domainmu.com';
        Mail::to($emailTo)->send(new ContactMail($data));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
