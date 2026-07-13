<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Http\Requests\Admin\FaqRequest;

class FaqController extends Controller
{
    public function index(Request $request) {
        $query = Faq::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $faqs = $query->orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create() {
        return view('admin.faqs.create');
    }

    public function store(FaqRequest $request) {
        \Illuminate\Support\Facades\Cache::flush();
        Faq::create([
            'question' => ['en' => $request->question_en, 'bn' => $request->question_bn],
            'answer' => ['en' => $request->answer_en, 'bn' => $request->answer_bn],
            'status' => $request->has('status'),
            'sort_order' => $request->sort_order ?? 0
        ]);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq) {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq) {
        \Illuminate\Support\Facades\Cache::flush();
        $faq->update([
            'question' => ['en' => $request->question_en, 'bn' => $request->question_bn],
            'answer' => ['en' => $request->answer_en, 'bn' => $request->answer_bn],
            'status' => $request->has('status'),
            'sort_order' => $request->sort_order ?? 0
        ]);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq) {
        \Illuminate\Support\Facades\Cache::flush();
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }
}
