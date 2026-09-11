<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View { return view('pages.about'); }
    public function contact(): View { return view('pages.contact'); }
    public function customOrder(): View { return view('pages.custom-order'); }
    public function services(): View { return view('pages.services'); }
    public function faq(): View { return view('pages.faq'); }
}
