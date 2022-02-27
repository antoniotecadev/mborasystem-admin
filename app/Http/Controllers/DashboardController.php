<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard/Index');
        
    }
}
