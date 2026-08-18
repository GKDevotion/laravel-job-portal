<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\LocalizationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageController extends Controller
{
    public function __construct(protected LocalizationSettingsService $localizationService) {}

    public function index(): View
    {
        return view('admin.settings.languages', ['languages' => Language::orderBy('order')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:10', 'unique:languages,code'],
            'locale' => ['required', 'string', 'max:10'],
            'is_rtl' => ['boolean'],
        ]);

        Language::create($data + ['status' => true, 'order' => Language::max('order') + 1]);
        $this->localizationService->refreshLanguages();

        return back()->with('status', 'Language added. It will appear in the switcher immediately.');
    }

    public function toggleStatus(Language $language): RedirectResponse
    {
        // Guard against disabling the only active language / the default language accidentally
        if ($language->is_default && $language->status) {
            return back()->with('error', 'Cannot disable the default language — set a different default first.');
        }

        $language->update(['status' => ! $language->status]);
        $this->localizationService->refreshLanguages();

        return back()->with('status', 'Language updated.');
    }

    public function setDefault(Language $language): RedirectResponse
    {
        $this->localizationService->setDefaultLanguage($language);

        return back()->with('status', "{$language->name} is now the default language.");
    }

    public function destroy(Language $language): RedirectResponse
    {
        if ($language->is_default) {
            return back()->with('error', 'Cannot delete the default language.');
        }

        $language->delete();
        $this->localizationService->refreshLanguages();

        return back()->with('status', 'Language removed.');
    }
}
