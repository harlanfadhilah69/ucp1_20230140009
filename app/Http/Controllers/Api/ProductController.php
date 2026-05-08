<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Get all products with pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $products = Product::with('category')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Daftar produk berhasil diambil',
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage(),
                ]
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil daftar produk', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengambil daftar produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();

            $validated['user_id'] = Auth::id();

            $product = Product::create($validated);

            Log::info('Menambah data produk', [
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);

            return response()->json([
                'message' => 'Produk berhasil ditambahkan!',
                'data' => $product->load('category'),
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error saat menambah product', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat menambah produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display product by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'Produk berhasil diambil',
                'data' => $product
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data produk', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product
     *
     * @param StoreProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreProductRequest $request, int $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }

            $validated = $request->validated();
            unset($validated['user_id']);
            
            $product->update($validated);

            Log::info('Mengupdate produk', [
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);

            return response()->json([
                'message' => 'Produk berhasil diupdate',
                'data' => $product->load('category'),
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat update produk', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat update produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }

            $product->delete();

            Log::info('Menghapus produk', [
                'user_id' => Auth::id(),
                'product_id' => $id
            ]);

            return response()->json([
                'message' => 'Produk berhasil dihapus',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat menghapus produk', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
