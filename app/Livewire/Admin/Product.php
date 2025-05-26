<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Product as ModelsProduct;
use App\Models\ProductCategory as Category;
use Livewire\Component;
use Livewire\WithPagination;

class Product extends MainBase
{
    use WithPagination;
    
    public $image;
    public $oldImage;
    public $uploadDirectory = 'pruducts';
    public $searchableFields = ['name', 'title', 'description'];
    
    // Properti untuk many-to-many genres
    public $productcategories;
    public function mount()
    {
         $this->model = ModelsProduct::class;
         $this->fields = [
        'name' => '',
        'title' => '',
        'description' => '',
        'price' => '',
        'stock' => '',
        'product_category_id' => '',
        'image' => '',

    ];
        $this->productcategories = Category::all();
    }

    public function render()
    {
        $products = $this->getQuery($this->model)
                ->with(['category'])
                ->paginate($this->perPage);
        return view('livewire.admin.product',[
        'products' => $products,
        'productcatgories' => $this->productcategories,
        ]);
    }
}
