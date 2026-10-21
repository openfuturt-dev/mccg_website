<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use App\Models\PageSeo;
use App\Models\Service;
use App\Services\ResendContactMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    public function create()
    {
        return view('pages.contact', [
            'services' => Service::where('is_active', true)->get(),
            'seo' => PageSeo::for('contact'),
        ]);
    }

    public function store(StoreContactRequest $request, ResendContactMailer $mailer)
    {
        $data = $request->validated();
        [$firstName, $lastName] = array_pad(explode(' ', trim($data['full_name']), 2), 2, '');
        $data['first_name'] = $firstName;
        $data['last_name'] = $lastName;
        unset($data['full_name']);

        $contactMessage = ContactMessage::create($data);

        try {
            $mailer->send($contactMessage);
        } catch (Throwable $exception) {
            Log::error('Échec de l’envoi Resend du formulaire de contact.', [
                'contact_message_id' => $contactMessage->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->withInput()->with(
                'error',
                'Une erreur est survenue lors de l’envoi de votre demande. Veuillez réessayer.'
            );
        }

        return back()->with(
            'success',
            'Votre demande a bien été envoyée. Notre équipe vous contactera prochainement.'
        )->with('mccg_lead_id', (string) Str::uuid());
    }
}
