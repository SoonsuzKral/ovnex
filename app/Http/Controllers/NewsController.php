<?php
/*
 * OVNEX — Haber API kontrolcüsü
 * Haber akışı verilerini JSON olarak döner
 */
namespace App\Http\Controllers;

use App\Models\NewsFeed;

class NewsController extends Controller
{
    public function index()
    {
        $query = NewsFeed::orderBy('published_at', 'desc');

        if (request()->has('category')) {
            $query->where('category', request('category'));
        }

        if (request()->has('province')) {
            $query->where('province', request('province'));
        }

        $news = $query->take(50)->get();

        return response()->json($news);
    }

    public function latest()
    {
        $news = NewsFeed::orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($news);
    }
}
