<?php

namespace Contracargos\Http\Controllers;

use Illuminate\Http\Request;
use Contracargos\Exports\UsersExport;
use Maatwebsite\Excel\Exporter;

class ExportsController
{
    private $exporter;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    public function export()
    {
        return $this->exporter->download(new UsersExport, 'users.xlsx');
    }
}
