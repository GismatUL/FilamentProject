<?php

namespace Modules\Reports\app\Http\Controllers;

use Illuminate\Routing\Controller;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports::index');
    }
}
