<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\TranslationToolService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TranslationController extends Controller
{
    public function __construct(protected TranslationToolService $translationTool) {}

    public function edit(Request $request): View
    {
        $locale = $request->string('locale')->toString() ?: (Language::where('is_default', true)->first()?->code ?? 'en');

        return view('admin.settings.translations', [
            'languages' => Language::orderBy('order')->get(),
            'currentLocale' => $locale,
            'strings' => $this->translationTool->stringsForLocale($locale),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'locale' => ['required', 'string', 'exists:languages,code'],
            'translations' => ['required', 'array'],
        ]);

        $this->translationTool->save($data['locale'], $data['translations']);

        return redirect()->route('admin.settings.translations', ['locale' => $data['locale']])
            ->with('status', 'Translations saved.');
    }
}
