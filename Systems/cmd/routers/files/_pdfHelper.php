<?php

import('PdfBuilder','Services.Pdf.PdfBuilder');

class pdfHelper
{

    private PdfBuilder $builder;

    public function __construct()
    {
        $this->builder = new PdfBuilder(PdfBuilder::PDF_ORIENTATION_PORTRAIT, PdfBuilder::PDF_FORMAT_A4);

        //TODO: continue implementation
    }

    public function build() : void
    {
        // TODO: Implement it
    }

}