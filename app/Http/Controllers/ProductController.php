<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Display a listing of the resource in card view.
     */
    public function cards()
    {
        $products = Product::all();
        return view('products.cards', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // We process price before validation
        if ($request->has('price')) {
            // Handle price with dots as thousand separators
            $request->merge(['price' => $this->formatPrice($request->price)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'featured' => 'boolean',
            'stock_quantity' => 'integer|min:0',
            'track_inventory' => 'boolean',
        ]);

        $data = $request->all();

        // Ensure boolean fields are properly handled
        $data['featured'] = $request->has('featured') ? (bool)$request->featured : false;
        $data['track_inventory'] = $request->has('track_inventory') ? (bool)$request->track_inventory : false;

        if (!isset($data['stock_quantity'])) {
            $data['stock_quantity'] = 0;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Hanya catat view jika user sudah login
        if (Auth::check()) {
            // Catat produk sebagai dilihat dengan mekanisme cooldown 1 menit
            // Jika user sudah melihat produk ini dalam 1 menit terakhir, view tidak bertambah
            $viewRecorded = $product->recordView(Auth::id());

            if ($viewRecorded) {
                // Pencatatan view berhasil (baru pertama kali atau setelah cooldown)
                Log::info("View recorded for product ID: {$product->id} by user ID: " . Auth::id());
            }
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // We process price before validation
        if ($request->has('price')) {
            // Handle price with dots as thousand separators
            $request->merge(['price' => $this->formatPrice($request->price)]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'featured' => 'boolean',
            'stock_quantity' => 'integer|min:0',
            'track_inventory' => 'boolean',
        ]);

        $data = $request->all();

        // Ensure boolean fields are properly handled
        $data['featured'] = $request->has('featured') ? (bool)$request->featured : false;
        $data['track_inventory'] = $request->has('track_inventory') ? (bool)$request->track_inventory : false;

        if (!isset($data['stock_quantity'])) {
            $data['stock_quantity'] = 0;
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Get product details for API request
     */
    public function getProductDetails(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'image_url' => Storage::url($product->image),
            'featured' => $product->featured,
            'key_points' => $this->extractKeyPoints($product->description),
            'stock_status' => $product->stock_status,
            'in_stock' => $product->inStock(),
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at
        ]);
    }

    /**
     * Extract key points from product description text
     * Converts paragraphs into bullet points of key information
     */
    private function extractKeyPoints($description)
    {
        if (empty($description)) {
            return [];
        }

        // Check if there are already bullet points or numbered lists
        $listPattern = '/[\-\*•]\s*([^\n]+)/';
        $numberedPattern = '/\d+\.\s*([^\n]+)/';

        preg_match_all($listPattern, $description, $listMatches);
        preg_match_all($numberedPattern, $description, $numberedMatches);

        // If already formatted as list, use those points
        if (!empty($listMatches[1]) || !empty($numberedMatches[1])) {
            return array_merge($listMatches[1], $numberedMatches[1]);
        }

        // Otherwise, split sentences and extract key points
        $sentences = preg_split('/(\.|\!|\?)\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);

        // If we have many sentences, limit to important ones
        if (count($sentences) > 10) {
            $keyPoints = [];

            // Look for sentences with key indicators
            $keyIndicators = [
                // General product indicators
                'memiliki', 'dilengkapi', 'fitur', 'spesifikasi', 'ukuran',
                'bahan', 'material', 'kapasitas', 'daya', 'power', 'watt',
                'warna', 'kemampuan', 'fungsi', 'cocok', 'sesuai', 'dapat',
                'mampu', 'garansi', 'dimensi', 'tinggi', 'panjang', 'lebar',
                'berat', 'teknologi', 'sistem', 'menggunakan', 'menawarkan',
                'support', 'mendukung', 'kompatibel',

                // Musical instrument / harmonicas specific
                'harmonika', 'diatonik', 'kromatik', 'tremolo', 'blues', 'nada',
                'lubang', 'holes', 'key', 'mayor', 'minor', 'comb', 'reed', 'plate',
                'kuningan', 'perunggu', 'stainless', 'oktav', 'octave', 'lidah',
                'genre', 'musik', 'richter', 'solo tuning', 'ventilasi', 'suara',
                'cover', 'hardcase', 'pouch', 'pembersih', 'anti-slip', 'slide',
                'brand', 'merek', 'suzuki', 'hohner', 'easttop', 'tombo', 'fender',
                'blues', 'rock', 'pop', 'jazz', 'country', 'folk', 'musik'
            ];

            foreach ($sentences as $sentence) {
                $sentence = trim($sentence);
                if (empty($sentence)) continue;

                // Keep short sentences (likely important details)
                if (str_word_count($sentence) <= 7) {
                    $keyPoints[] = $sentence;
                    continue;
                }

                // Keep sentences with key indicators
                foreach ($keyIndicators as $indicator) {
                    if (stripos($sentence, $indicator) !== false) {
                        $keyPoints[] = $sentence;
                        break;
                    }
                }

                // Limit to reasonable number of key points
                if (count($keyPoints) >= 6) {
                    break;
                }
            }

            // If we couldn't extract enough key points, use the first few sentences
            if (count($keyPoints) < 3 && count($sentences) >= 3) {
                return array_slice($sentences, 0, 5);
            }

            return $keyPoints;
        }

        // For short descriptions, use all sentences
        $points = [];
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (!empty($sentence)) {
                // Add a period at the end if missing
                if (!in_array(substr($sentence, -1), ['.', '!', '?'])) {
                    $sentence .= '.';
                }
                $points[] = $sentence;
            }
        }

        return $points;
    }

    /**
     * Format price from Indonesian currency format to decimal
     */
    private function formatPrice($price)
    {
        // Remove all dots (thousand separators in ID format)
        return str_replace('.', '', $price);
    }

    /**
     * Record view for product via API
     */
    public function recordViewApi(Product $product)
    {
        $viewRecorded = false;

        if (Auth::check()) {
            $viewRecorded = $product->recordView(Auth::id());
        }

        return response()->json([
            'success' => true,
            'view_recorded' => $viewRecorded,
            'views' => $product->views
        ]);
    }
}
