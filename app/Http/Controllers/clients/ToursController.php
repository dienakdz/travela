<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Illuminate\Http\Request;

class ToursController extends Controller
{

    private $tours;


    public function __construct()
    {
        $this->tours = new Tours();
    }

    public function index()
    {
        $title = 'Tours';
        $tours = $this->tours->getAllTours();
        $domain = $this->tours->getDomain();
        $domainsCount = [
            'mien_bac' => optional($domain->firstWhere('domain', 'b'))->count,
            'mien_trung' => optional($domain->firstWhere('domain', 't'))->count,
            'mien_nam' => optional($domain->firstWhere('domain', 'n'))->count,
        ];
        return view('clients.tours', compact('title', 'tours', 'domainsCount'));
    }

    //Xử lý filter tours
    public function filterTours(Request $req)
    {

        $conditions = [];
        $sorting = [];

        // Handle price filter
        if ($req->filled('minPrice') && $req->filled('maxPrice')) {
            $minPrice = $req->minPrice;
            $maxPrice = $req->maxPrice;
            $conditions[] = ['priceAdult', '>=', $minPrice];
            $conditions[] = ['priceAdult', '<=', $maxPrice];
        }

        // Handle domain filter
        if ($req->filled('domain')) {
            $domain = $req->domain;
            $conditions[] = ['domain', '=', $domain];
        }

        // Handle star rating filter
        if ($req->filled('star')) {
            $star = (int) $req->star;
            $conditions[] = ['averageRating', '>=', $star];
        }

        // Handle duration filter
        if ($req->filled('time')) {
            $duration = $req->time;
            $time = [
                '3n2d' => '3 ngày 2 đêm',
                '4n3d' => '4 ngày 3 đêm',
                '5n4d' => '5 ngày 4 đêm'
            ];
            $conditions[] = ['time', '=', $time[$duration]];
        }

        // Handle orderby filter
        if ($req->filled('sorting')) {
            $sortingOption = trim($req->sorting); // Remove any whitespace
        
            // Handle sorting options
            if ($sortingOption == 'new') {
                $sorting[] = ['tourId', 'DESC']; // Sort by creation date, newest first
            } elseif ($sortingOption == 'old') {
                $sorting[] = ['tourId', 'ASC']; // Sort by creation date, oldest first
            } elseif ($sortingOption == "hight-to-low") {
                $sorting[] = ['priceAdult', 'DESC']; // Sort by price in descending order
            } elseif($sortingOption == "low-to-high") {
                $sorting[] = ['priceAdult', 'ASC']; // Sort by price in ascending order
            }
        }

        // For debugging purposes, check the sorting array
        // dd($sorting);
        $tours = $this->tours->filterTours($conditions, $sorting);
        return view('clients.partials.filter-tours', compact('tours'));

    }
}
