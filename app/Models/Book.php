<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Book extends Model
{
    use HasFactory;

   public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, String $title): Builder
    {
        return $query->where("title","like","%". $title ."%");
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])->orderBy('reviews_count','desc');
    }

    public function scopeHighestRated(Builder $query): Builder {
        return $query->withAvg('reviews','rating')
                    ->orderBy('reviews_avg_rating', 'desc');
    }

public function scopeMinReview(Builder $query, int $minReviews): Builder {
    return $query->having('reviews_count', '>=' , $minReviews);
}

private function dateRangeFilter(Builder $query, $from = null, $to = null) {
            if($from && !$to){
                $query->where('created_at','>=', $from);
            } else if(!$from && $to){
                $query->where('created_at','<=', $to);
            } else if($from && $to){
                $query->whereBetween('created_at', [$from, $to]);
            }
}

public function scopePopularLastMonth(Builder $query): Builder {
    return $query->popular(now()->subMonth(), now())
                ->highestRated(now()->subMonth(), now())
                ->minReview(2);
}
public function scopePopularLast6Months(Builder $query): Builder {
    return $query->popular(now()->subMonths(6), now())
                ->highestRated(now()->subMonths(6), now())
                ->minReview(5);
}

public function scopeHighestLastMonth(Builder $query): Builder {
    return $query->highestRated(now()->subMonth(), now())
                 ->popular(now()->subMonth(), now())
                ->minReview(2);
}
public function scopeHighestLast6Months(Builder $query): Builder {
    return $query->highestRated(now()->subMonths(6), now())
                 ->popular(now()->subMonths(6), now())
                ->minReview(5);
}
}
