<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model { protected $fillable=['category_id','sku','name','purchase_price','selling_price','stock','minimum_stock']; protected function casts(): array{return ['purchase_price'=>'decimal:2','selling_price'=>'decimal:2','stock'=>'integer','minimum_stock'=>'integer'];} public function category(){return $this->belongsTo(Category::class);} public function purchaseItems(){return $this->hasMany(PurchaseItem::class);} public function saleItems(){return $this->hasMany(SaleItem::class);} }
