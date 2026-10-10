<?php

namespace App\Support\FileNaming;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;

/**
 * Replaces spatie/laravel-medialibrary's default file namer, which keeps the original
 * filename mostly as-is. Real-world uploads — especially screenshots and phone photos —
 * routinely contain spaces, commas, and other characters that survive into the stored file
 * name and then need URL-encoding every time the file is linked to (e.g. "ChatGPT Image Aug
 * 18, 2026, 11_47_10 AM.png" becoming ".../ChatGPT-Image-Aug-18%2C-2026%2C-...-thumb.jpg").
 * That still technically works, but it's fragile — some downstream tools, CDNs, or copy-paste
 * contexts mishandle encoded punctuation in URLs. Sanitizing at upload time avoids the
 * problem entirely rather than working around it every time a URL is generated.
 */
class SanitizedFileNamer extends FileNamer
{
    public function originalFileName(string $fileName): string
    {
        return $this->sanitize($fileName);
    }

    // Restored the Conversion typehint and extracted the name using ->getName()
    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        return $this->sanitize($fileName).'-'.$conversion->getName();
    }

    public function responsiveFileName(string $fileName): string
    {
        return $this->sanitize($fileName);
    }

    protected function sanitize(string $fileName): string
    {
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        // Str::slug strips commas, collapses whitespace to single hyphens, and lowercases —
        // exactly the class of characters that cause encoded-URL ugliness.
        $slug = Str::slug($name);

        // A file named only in punctuation/emoji could slug down to an empty string —
        // fall back to a short random token rather than storing a blank filename.
        return $slug !== '' ? $slug : Str::random(12);
    }
}
