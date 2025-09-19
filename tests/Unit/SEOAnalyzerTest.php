<?php

use App\Services\SEOAnalyzerService;

describe('SEO Analyzer Service', function () {
    beforeEach(function () {
        $this->seoAnalyzer = new SEOAnalyzerService();
    });

    test('analyzes page with optimal title length', function () {
        $analysis = $this->seoAnalyzer->analyzePage(
            'This is a Perfect Title Length for SEO Optimization',
            'This is a perfect meta description that falls within the optimal range for search engines and provides excellent user experience.',
            'This is comprehensive content that provides valuable information to users. It contains more than 300 words which is considered good for SEO. The content is well-structured and informative, covering all the important aspects of the topic in detail.',
            'seo, optimization, test'
        );

        expect($analysis['score'])->toBeGreaterThan(80);
        expect($analysis['grade'])->toBe('good');
        expect($analysis['checks']['title_length']['status'])->toBe('good');
    });

    test('analyzes page with short title', function () {
        $analysis = $this->seoAnalyzer->analyzePage(
            'Short',
            'Description',
            'Content',
            'keywords'
        );

        expect($analysis['score'])->toBeLessThan(50);
        expect($analysis['checks']['title_length']['status'])->toBe('error');
        expect($analysis['suggestions'])->toContain('Title should be between 50-60 characters');
    });

    test('analyzes page with long meta description', function () {
        $longDescription = str_repeat('This is a very long meta description that exceeds the recommended character limit for search engine optimization and may be truncated in search results. ', 5);

        $analysis = $this->seoAnalyzer->analyzePage(
            'Good Title Length for SEO Testing and Analysis Purpose',
            $longDescription,
            'Good content with sufficient length for SEO analysis.',
            'test, seo'
        );

        expect($analysis['checks']['description_length']['status'])->toBe('warning');
    });

    test('analyzes content length appropriately', function () {
        $shortContent = 'Very short content.';
        $goodContent = str_repeat('This is good content with sufficient length for SEO purposes. ', 20);

        $shortAnalysis = $this->seoAnalyzer->analyzePage(
            'Test Title for Short Content Analysis Testing',
            'Test description for the analysis of short content impact on SEO scoring and recommendations system implementation.',
            $shortContent,
            'test'
        );

        $goodAnalysis = $this->seoAnalyzer->analyzePage(
            'Test Title for Good Content Analysis Testing',
            'Test description for the analysis of good content impact on SEO scoring and recommendations system implementation.',
            $goodContent,
            'test'
        );

        expect($shortAnalysis['checks']['content_length']['status'])->toBe('error');
        expect($goodAnalysis['checks']['content_length']['status'])->toBe('good');
    });

    test('analyzes keyword count', function () {
        $fewKeywords = 'seo, test, optimization';
        $manyKeywords = 'seo, test, optimization, keywords, meta, title, description, content, analysis, scoring';

        $fewAnalysis = $this->seoAnalyzer->analyzePage(
            'SEO Analysis Test Title for Keyword Count Testing',
            'SEO analysis description for testing keyword count impact on overall scoring system and recommendations.',
            'Good content for testing.',
            $fewKeywords
        );

        $manyAnalysis = $this->seoAnalyzer->analyzePage(
            'SEO Analysis Test Title for Keyword Count Testing',
            'SEO analysis description for testing keyword count impact on overall scoring system and recommendations.',
            'Good content for testing.',
            $manyKeywords
        );

        expect($fewAnalysis['checks']['keywords']['status'])->toBe('good');
        expect($manyAnalysis['checks']['keywords']['status'])->toBe('warning');
        expect($manyAnalysis['suggestions'])->toContain('Focus on 3-5 main keywords');
    });

    test('analyzes readability based on sentence length', function () {
        $goodReadability = 'This is good content. Sentences are short. Easy to read. Good for SEO. ' . str_repeat('Another good sentence. ', 50);
        $poorReadability = str_repeat('This is a very long sentence that contains too many words and becomes difficult to read and understand for users which negatively impacts the overall user experience and SEO performance of the page content. ', 10);

        $goodAnalysis = $this->seoAnalyzer->analyzePage(
            'Readability Test Title for SEO Analysis Testing',
            'Readability test description for analyzing sentence length impact on SEO scoring and user experience optimization.',
            $goodReadability,
            'readability, test'
        );

        $poorAnalysis = $this->seoAnalyzer->analyzePage(
            'Readability Test Title for SEO Analysis Testing',
            'Readability test description for analyzing sentence length impact on SEO scoring and user experience optimization.',
            $poorReadability,
            'readability, test'
        );

        expect($goodAnalysis['checks']['readability']['status'])->toBe('good');
        expect($poorAnalysis['checks']['readability']['status'])->toBe('error');
    });

    test('returns correct grade based on score', function () {
        expect($this->seoAnalyzer->getGrade(95))->toBe('excellent');
        expect($this->seoAnalyzer->getGrade(85))->toBe('good');
        expect($this->seoAnalyzer->getGrade(65))->toBe('average');
        expect($this->seoAnalyzer->getGrade(45))->toBe('poor');
        expect($this->seoAnalyzer->getGrade(25))->toBe('critical');
    });

    test('score never exceeds 100', function () {
        $analysis = $this->seoAnalyzer->analyzePage(
            'Perfect SEO Title Length for Maximum Optimization Score',
            'Perfect meta description length that falls exactly within the optimal range for search engine optimization and provides excellent user experience for visitors.',
            str_repeat('Perfect content with excellent readability and great length. Short sentences make it easy to read. Great for SEO optimization. ', 50),
            'perfect, seo, optimization'
        );

        expect($analysis['score'])->toBeLessThanOrEqual(100);
    });
});