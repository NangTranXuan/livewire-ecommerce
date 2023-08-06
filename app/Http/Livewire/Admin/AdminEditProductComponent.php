<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminEditProductComponent extends Component
{
    use WithFileUploads;

    public $name;

    public $slug;

    public $short_description;

    public $description;

    public $regular_price;

    public $stock_status;


    public $quantity;

    public $image;

    public $category_id;

    public $newimage;

    public $product_id;

    public function mount($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->regular_price = $product->regular_price;
        $this->stock_status = $product->stock_status;
        $this->quantity = $product->quantity;
        $this->image = $product->image;
        $this->category_id = $product->category_id;
        $this->product_id = $product->id;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name, '-');
    }

    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'name' => 'required',
            'slug' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'stock_status' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required',
        ]);
        if ($this->newimage) {
            $this->validateOnly($fields, [
                'newimage' => 'required|mimes:jpeg,png',
            ]);
        }
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'stock_status' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required',
        ]);
        if ($this->newimage) {
            $this->validate([
                'newimage' => 'required|mimes:jpeg,png',
            ]);
        }
        $product = Product::find($this->product_id);
        $product->name = $this->name;
        $product->slug = $this->slug;
        $product->short_description = $this->short_description;
        $product->description = $this->description;
        $product->regular_price = $this->regular_price;
        $product->stock_status = $this->stock_status;
        $product->quantity = $this->quantity;

        if ($this->newimage) {
            unlink('assets/images/products' . '/' . $product->image);
            $imageName = Carbon::now()->timestamp . '.' . $this->newimage->extension();
            $this->newimage->storeAs('products', $imageName);
            $product->image = $imageName;
        }

        $product->category_id = $this->category_id;
        $product->save();
        flash()->addSuccess('Product has been updated successfully!');
        return redirect()->route('admin.products');
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.admin.admin-edit-product-component', ['categories' => $categories])->layout('layouts.base');
    }
}
