<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\MetaInformation;
use App\Services\SEOAnalyzerService;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    protected $seoAnalyzer;

    public function __construct(SEOAnalyzerService $seoAnalyzer)
    {
        $this->seoAnalyzer = $seoAnalyzer;
    }

    public function index(Request $request)
    {
        $query = Page::with(['creator', 'metaInformation']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pages = $query->latest()->paginate(10);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(StorePageRequest $request)
    {
        $pageData = $request->only([
            'title', 'slug', 'content', 'status', 'show_breadcrumb'
        ]);

        // Sanitize inputs for security
        $pageData['title'] = strip_tags($pageData['title']);
        $pageData['content'] = $this->sanitizeContent($pageData['content']);
        
        // Handle boolean conversion for checkbox
        $pageData['show_breadcrumb'] = $request->has('show_breadcrumb') ? true : false;

        $page = new Page($pageData);

        // Always ensure slug is properly formatted
        if (empty($page->slug)) {
            $page->slug = Str::slug($page->title);
        } else {
            // Properly format the provided slug
            $page->slug = Str::slug($page->slug);
        }

        $page->created_by = Auth::guard('admin')->id();
        $page->updated_by = Auth::guard('admin')->id();
        $page->save();

        // Create meta information if provided
        $metaFields = [
            'meta_title', 'meta_description', 'meta_keywords', 'focus_keyword',
            'og_title', 'og_description', 'og_image', 'og_type', 'og_url', 'og_site_name',
            'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
            'twitter_site', 'twitter_creator', 'canonical_url', 'robots', 'schema_markup'
        ];

        if ($request->hasAny($metaFields)) {
            $metaData = $request->only($metaFields);
            
            // Calculate SEO score if meta data is provided
            if ($request->filled(['meta_title', 'meta_description']) || $request->filled('content')) {
                $seoAnalysis = $this->seoAnalyzer->analyzePage(
                    $request->meta_title ?: $request->title,
                    $request->meta_description,
                    $request->content,
                    $request->meta_keywords
                );
                $metaData['seo_score'] = $seoAnalysis['score'];
            }
            
            $page->metaInformation()->create($metaData);
        }

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page created successfully.');
    }

    public function show(Page $page)
    {
        $page->load(['creator', 'updater', 'metaInformation']);
        
        $seoAnalysis = null;
        if ($page->metaInformation) {
            $seoAnalysis = $this->seoAnalyzer->analyzePage(
                $page->metaInformation->effective_meta_title,
                $page->metaInformation->effective_meta_description,
                $page->content,
                $page->metaInformation->meta_keywords
            );
        }

        return view('admin.pages.show', compact('page', 'seoAnalysis'));
    }

    public function edit(Page $page)
    {
        $page->load('metaInformation');
        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->fill($request->only([
            'title', 'slug', 'content', 'status', 'show_breadcrumb'
        ]));

        // Always ensure slug is properly formatted
        if (empty($page->slug)) {
            $page->slug = Str::slug($page->title);
        } else {
            // Properly format the provided slug
            $page->slug = Str::slug($page->slug);
        }

        $page->updated_by = Auth::guard('admin')->id();
        $page->save();

        // Update or create meta information
        $metaData = $request->only([
            'meta_title', 'meta_description', 'meta_keywords',
            'og_title', 'og_description', 'og_image', 'og_type', 'og_url',
            'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
            'twitter_site', 'twitter_creator', 'canonical_url', 'robots'
        ]);

        if ($page->metaInformation) {
            $page->metaInformation->update($metaData);
        } else {
            $page->metaInformation()->create($metaData);
        }

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->metaInformation?->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page deleted successfully.');
    }

    public function analyzeSEO(Request $request)
    {
        $analysis = $this->seoAnalyzer->analyzePage(
            $request->title,
            $request->description,
            $request->content,
            $request->keywords
        );

        return response()->json($analysis);
    }

    /**
     * Sanitize content to prevent XSS attacks while allowing safe HTML
     */
    private function sanitizeContent($content)
    {
        // Strip malicious scripts but allow basic HTML formatting
        $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $content);
        $content = preg_replace('/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi', '', $content);
        $content = preg_replace('/on\w+="[^"]*"/i', '', $content);
        $content = preg_replace('/on\w+=\'[^\']*\'/i', '', $content);
        $content = preg_replace('/javascript:[^"\']*["\']/i', '', $content);
        
        return $content;
    }
}