<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Opportunity_pdf extends App_pdf
{
    protected $opportunity;
    protected $requirements;
    protected $title;

    private $invoice_number;

    public function __construct($data, $tag = '')
    {
        // $GLOBALS['invoice_pdf'] = $invoice;

        parent::__construct();

        
        $this->tag            = $tag;
        $this->opportunity        = $data['opportunity'];
        $this->requirements        = $data['requirements'];
        $this->title        = $data['title'];
        
        $this->SetTitle($this->opportunity->id);
    }

    public function prepare()
    {
        
        $this->set_view_vars([
            'opportunity'         => $this->opportunity,
            'requirements' => $this->requirements,
            'title'  => $this->title,
            
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'opportunity';
    }

    protected function file_path()
    {
        
        
        $actualPath = module_views_path('opportunity','opportunitypdf.php');

        return $actualPath;
    }

}
