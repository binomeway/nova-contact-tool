<?php


namespace BinomeWay\NovaContactTool\Http\Controllers;


use App\Http\Controllers\Controller;
use BinomeWay\NovaContactTool\Services\Contact;
use BinomeWay\NovaContactTool\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnsubscribeController extends Controller
{
    public function __invoke(Subscriber $subscriber, Contact $contact, Request $request)
    {
        // Check if signature is correct, otherwise abort the request.
        if (! $request->hasValidSignature()) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $contact->unsubscribe($subscriber);

        session()->flash('message', __('contact::messages.unsubscribed'));

        return redirect()->to('/');
    }
}
