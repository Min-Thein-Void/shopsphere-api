<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id', 'image','discount_type','discount_value'];

    //api json return pyan yin database mar ma shi tae column ko htae pay phoz
    protected $appends = ['final_price','discount_label'];

    protected $with = ['category'];

    use HasFactory;

    public function Category(){
        return $this->belongsTo(Category::class);
    }

    //price ko pyin tae a lote lote tar
    public function getFinalPriceAttribute(){
        if(!$this->discount_type){
            return $this->price;
        }
        return $this->discount_type === 'percentage'
               ? $this->price - ($this->price * $this->discount_value / 100)
               : max(0,$this->price - $this->discount_value);
    }


    //UI mar -20% at lo pya phoz
    public function getDiscountLabelAttribute(){
       if (!$this->discount_type){
          return null;
       }
       return $this->discount_type === 'percentage'
              ? "-{$this->discount_value}%"
              : "-{$this->discount_value}";
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function avgRating(){
        return $this->ratings()->avg('rating') ?? 0;
    }
}
