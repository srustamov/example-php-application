<?php

namespace App\Controllers;

use App\Models\Form;
use App\Support\Http\Middleware\RateLimiterMiddleware;
use App\Support\Http\Request;

class FormController
{
    public function index(): string|bool
    {
        return view('form');
    }

    public function store(Request $request): string
    {
        $validate = validate($request->getRequest(), [
            'body' => 'required|min:10|max:255'
        ]);

        if (!empty($validate)) {
            return view('form', [
                'errors' => $validate['body']
            ]);
        }

        $limiter = new RateLimiterMiddleware(limit: 1, interval: 60, storageKey: 'rate_limiter_form');

        $limiter->handle($request, fn($request) => $request);

        $form = new Form;

        $form->body = htmlspecialchars($request->post('body'));

        return view('form', [
            'success' => $form->save(),
            'body' => $form->body,
        ]);
    }
}