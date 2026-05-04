<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\SaasPlan;

class HomeController extends Controller
{
    public function index(): View
    {
        // Fetch active SaaS plans for the pricing table
        $plans = SaasPlan::where('is_active', true)->orderBy('price', 'asc')->get();

        return view('welcome', compact('plans'));
    }

    public function features(): View
    {
        return view('pages.features');
    }

    public function pricing(): View
    {
        $plans = SaasPlan::where('is_active', true)->orderBy('price', 'asc')->get();
        return view('pages.pricing', compact('plans'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
