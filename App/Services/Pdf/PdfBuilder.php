<?php

namespace Arkit\Services\Pdf;

\Loader::import('Html2Pdf', 'Libs.Html2pdf.vendor.autoload');

/**
 * Class for building a pdf from a web page
 */
class PdfBuilder
{

    // Formats ----------------------------------
    /**
     * A4 Format: 8.27 x 11.69 in = 210 x 297 mm
     */
    const PDF_FORMAT_A4 = 'A4';

    /**
     * E4 Format: 8.66 x 12.20 in = 220 x 310 mm
     */
    const PDF_FORMAT_E4 = 'E4';

    /**
     * RA4 Format: 8.46 x 12.01 in = 215 x 305 mm
     */
    const PDF_FORMAT_RA4 = 'RA4';

    /**
     * SRA4 Format: 8.86 x 12.60 in = 225 x 320 mm
     */
    const PDF_FORMAT_SRA4 = 'SRA4';

    /**
     * SUPER_A4 Format: 8.94 x 14.02 in = 227 x 356 mm
     */
    const PDF_FORMAT_SUPER_A4 = 'SUPER_A4';

    /**
     * A4_LONG Format: 8.27 x 13.70 in = 210 x 348 mm
     */
    const PDF_FORMAT_A4_LONG = 'A4_LONG';

    /**
     * F4 Format: 8.27 x 12.99 in = 210 x 330 mm
     */
    const PDF_FORMAT_F4 = 'F4';

    /**
     * P4 Format: 8.46 x 11.02 in = 215 x 280 mm
     */
    const PDF_FORMAT_P4 = 'P4';

    /**
     * LETTER Format: 8.50 x 11.00 in = 216 x 279 mm
     */
    const PDF_FORMAT_LETTER = 'LETTER';

    /**
     * LEGAL Format: 8.50 x 14.00 in = 216 x 356 mm
     */
    const PDF_FORMAT_LEGAL = 'LEGAL';

    /**
     * GOVERNMENTLETTER Format: 8.00 x 10.50 in = 203 x 267 mm
     */
    const PDF_FORMAT_GOVERNMENTLETTER = 'GOVERNMENTLETTER';

    /**
     * GOVERNMENTLEGAL Format: 8.50 x 13.00 in = 216 x 330 mm
     */
    const PDF_FORMAT_GOVERNMENTLEGAL = 'GOVERNMENTLEGAL';

    // Orientations -----------------------------------
    /**
     * Portrait Orientation
     */
    const PDF_ORIENTATION_PORTRAIT = 'P';

    /**
     * Landscape Orientation
     */
    const PDF_ORIENTATION_LANDSCAPE = 'L';

    /**
     * Automatic Orientation
     */
    const PDF_ORIENTATION_AUTO = '';

    // Units ------------------------------------------
    /**
     * Point
     */
    const PDF_UNIT_POINT = 'pt';

    /**
     * Millimeter
     */
    const PDF_UNIT_MILLIMETER = 'mm';

    /**
     * Centimeter
     */
    const PDF_UNIT_CENTIMETER = 'cm';

    /**
     * Inch
     */
    const PDF_UNIT_INCH = 'cm';

    // Fonts ----------------------------------------
    const PDF_FONT_AEFURAT = 'aefurat';

    const PDF_FONT_DEJAVUSANS = 'dejavusans';

    const PDF_FONT_DEJAVUSANSMONO = 'dejavusansmono';

    const PDF_FONT_DEJAVUSERIF = 'dejavuserif';

    const PDF_FONT_FREEMONO = 'freemono';

    const PDF_FONT_FREESANS = 'freesans';

    const PDF_FONT_FREESERIF = 'freeserif';

    const PDF_FONT_HELVETICA = 'helvetica';

    const PDF_FONT_PDFACOURIER = 'pdfacourier';

    const PDF_FONT_PDFAHELVETICA = 'pdfahelvetica';

    const PDF_FONT_PDFATIMES = 'pdfatimes';

    const PDF_FONT_TIMES = 'times';

    /**
     * @var \HTML2PDF
     */
    private \Html2Pdf $pdf;

    /**
     * @param string $orientation
     * @param string $format
     * @param string $language
     * @param array $margin
     */
    public function __construct(string $orientation, string $format, string $language = 'en', array $margin = [7, 7, 7, 10])
    {
        $this->pdf = new \HTML2PDF($orientation, $format, $language, true, 'UTF-8', $margin);
    }

    /**
     * @param string $fontName
     * @return void
     */
    public function setFont(string $fontName): void
    {
        $this->pdf->setDefaultFont($fontName);
    }

    /**
     * @param string $html
     * @return void
     */
    public function writeHTML(string $html): void
    {
        $this->pdf->writeHTML($html);
    }

    /**
     * @return void
     */
    public function addPage(): void
    {
        $this->pdf->pdf->AddPage();
    }

    /**
     * @param string $fileName
     * @return void
     * @throws \HTML2PDF_exception
     */
    public function save(string $fileName): void
    {
        $this->pdf->Output($fileName, 'F');
    }

    /**
     * @param string $outputName
     * @return void
     * @throws \HTML2PDF_exception
     */
    public function display(string $outputName): void
    {
        $this->pdf->Output($outputName, 'I');
    }

    /**
     * @param string $outputName
     * @return void
     * @throws \HTML2PDF_exception
     */
    public function download(string $outputName): void
    {
        $this->pdf->Output($outputName, 'D');
    }

}