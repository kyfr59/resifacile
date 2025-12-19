<?php

namespace App\Http\Controllers\SaleProcess;

use App\Contracts\Cart;
use App\DataTransferObjects\ModelData;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Template;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LetterController extends Controller
{
    /**
     * @param string|null $slug
     * @return View
     */
    public function edit(?string $slug = null, Cart $cart): View
    {
        if($cart->getProduct() !== $slug) {
            $cart->destroy();
            $cart->addProduct($slug);
        }

        $product = null;
        $templateModel = null;

        if(isset($slug)) {
            $category = Category::where('slug', $slug)->first();

            if($category) {
                return view('lettre-resiliation', [
                    'category' => $category,
                ]);
            }

            $product = Brand::where('slug', $slug)->first();

            if($product) {
                if($product->has_childs) {
                    return view('marque', [
                        'entry' => $product,
                    ]);
                }

                $template = $product->template->model->toArray();
            } else {
                $templateModel = Template::where('slug', $slug)->first();
                $template = $templateModel->model->toArray();
            }
        } else {
            $template = (new ModelData([], null, true))->toArray();
        }

        return view('sale-process.letter-edit', [
            'product' => $product,
            'page' => $product ?? $templateModel,
            'template' => $template,
            'handle' => ($product) ? 'marques' : 'modeles',
        ]);
    }
}
