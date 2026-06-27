<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Support\PdfDefaults;
use Illuminate\Database\Seeder;

class PdfTextSeeder extends Seeder
{
    public function run(): void
    {
        $texts = [
            'pdf_tekst_artikel_2'            => PdfDefaults::ARTIKEL_2,
            'pdf_tekst_artikel_2_afbakening' => PdfDefaults::ARTIKEL_2_AFBAKENING,
            'pdf_tekst_artikel_3'            => PdfDefaults::ARTIKEL_3,
            'pdf_tekst_artikel_3_2'          => PdfDefaults::ARTIKEL_3_2,
            'pdf_tekst_artikel_5'            => PdfDefaults::ARTIKEL_5,
            'pdf_tekst_artikel_6_afbakening' => PdfDefaults::ARTIKEL_6_AFBAKENING,
            'pdf_tekst_artikel_6_2'          => PdfDefaults::ARTIKEL_6_2,
            'pdf_tekst_afbakening_service'   => PdfDefaults::AFBAKENING_SERVICE,
            'pdf_tekst_artikel_7'            => PdfDefaults::ARTIKEL_7,
            'pdf_tekst_artikel_8'            => PdfDefaults::ARTIKEL_8,
            'pdf_tekst_artikel_8_2'          => PdfDefaults::ARTIKEL_8_2,
            'pdf_tekst_artikel_9_1'          => PdfDefaults::ARTIKEL_9_1,
            'pdf_tekst_artikel_9_2'          => PdfDefaults::ARTIKEL_9_2,
            'pdf_tekst_artikel_9_3'          => PdfDefaults::ARTIKEL_9_3,
            'pdf_tekst_artikel_9_4'          => PdfDefaults::ARTIKEL_9_4,
            'pdf_tekst_artikel_9_5'          => PdfDefaults::ARTIKEL_9_5,
            'pdf_tekst_artikel_9_6'          => PdfDefaults::ARTIKEL_9_6,
            'pdf_tekst_artikel_9_7'          => PdfDefaults::ARTIKEL_9_7,
            'pdf_tekst_artikel_10_footer'    => PdfDefaults::ARTIKEL_10_FOOTER,
        ];

        foreach ($texts as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
