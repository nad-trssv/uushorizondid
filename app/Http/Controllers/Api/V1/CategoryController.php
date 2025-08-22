<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;

class CategoryController extends Controller
{
    use HandlesLocale;

    public function __construct(protected CategoryService $service) {}

    public function index(Request $request)
    {
        $locale = $this->setAndGetLocale($request);
        $categories = $this->service->getRootWithChildren($locale);
        return CategoryResource::collection($categories);
    }

    public function show($id, Request $request)
    {
        $locale = $this->setAndGetLocale($request);
        $category = $this->service->getByIdWithTranslations($id, $locale);
        return new CategoryResource($category);
    }

    public function move(Request $request)
    {
        $request->validate([
            'categoryId' => 'required|exists:categories,id',
            'targetId' => 'nullable|exists:categories,id',
            'position' => 'required|in:change,root,before,after',
            'parentId' => 'nullable|exists:categories,id'
        ]);
        
        $category = Category::findOrFail($request->categoryId);
        $target = $request->targetId ? Category::findOrFail($request->targetId) : null;

        DB::beginTransaction();
        try {
            switch ($request->position) {
                case 'change':
                    $category->parent_id = $request->parentId;
                    $category->save();
                    $category->moveToEnd($target);
                    break;
                case 'root':
                    $category->parent_id = null;
                    $category->save();
                    $category->moveToEnd(null);
                    break;
                case 'before':
                    if ($target) {
                        $category->parent_id = $target->parent_id;
                        $category->save();
                        $category->moveBefore($target);
                    } else {
                        throw new \Exception('Target category not found.');
                    }
                    break;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'category' => new CategoryResource($category)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->service->store($request->validated());
            if (!$category) {
                throw new \Exception('Failed to create category.');
            }
            \Log::info('Category created successfully', ['category' => $category]);
    
            $locale = $this->setAndGetLocale($request);
            $categories = $this->service->getRootWithChildren($locale);
            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating category', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}