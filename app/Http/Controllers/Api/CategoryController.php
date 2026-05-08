<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Get all categories with pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $categories = Category::paginate($perPage);

            return response()->json([
                'message' => 'Daftar kategori berhasil diambil',
                'data' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'last_page' => $categories->lastPage(),
                ]
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil daftar kategori', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengambil daftar kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created category (requires authentication)
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = Category::create($validated);

            Log::info('Menambah data kategori', [
                'user_id' => Auth::id(),
                'category_id' => $category->id
            ]);

            return response()->json([
                'message' => 'Kategori berhasil ditambahkan!',
                'data' => $category,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error saat menambah kategori', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat menambah kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display category by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $category = Category::with('products')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'Kategori berhasil diambil',
                'data' => $category
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data kategori', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal mengambil data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update category (requires authentication)
     *
     * @param StoreCategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreCategoryRequest $request, int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            $validated = $request->validated();
            $category->update($validated);

            Log::info('Mengupdate kategori', [
                'user_id' => Auth::id(),
                'category_id' => $category->id
            ]);

            return response()->json([
                'message' => 'Kategori berhasil diupdate',
                'data' => $category,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat update kategori', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat update kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete category (requires authentication)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            $category->delete();

            Log::info('Menghapus kategori', [
                'user_id' => Auth::id(),
                'category_id' => $id
            ]);

            return response()->json([
                'message' => 'Kategori berhasil dihapus',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat menghapus kategori', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error saat menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
