<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class StudentsTemplateExport implements FromView
{
    /**
     * @return View
     */
    public function view(): View
    {
        return view('exports.students_template');
    }
}
