<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class VideoPlayerComponent extends BaseComponent
{
    protected string $name = 'Video Player';
    protected string $type = 'VideoPlayer';
    protected string $category = 'media';
    protected string $description = 'Video player with controls and customizable appearance';
    protected ?string $icon = 'play-circle';
    protected int $sortOrder = 70;

    protected array $defaultConfig = [
        'videoUrl' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
        'posterImage' => 'https://via.placeholder.com/640x360/000000/FFFFFF?text=Video+Poster',
        'autoPlay' => false,
        'loop' => false,
        'muted' => false,
        'showControls' => true,
        'showPlayButton' => true,
        'showProgressBar' => true,
        'showVolumeControl' => true,
        'showFullscreenButton' => true,
        'showTimeDisplay' => true,
        'aspectRatio' => 16/9,
        'fit' => 'contain',
        'backgroundColor' => '#000000',
        'controlsBackgroundColor' => '#000000',
        'controlsOpacity' => 0.7,
        'playButtonSize' => 48,
        'playButtonColor' => '#FFFFFF',
        'progressBarColor' => '#3B82F6',
        'progressBarBackgroundColor' => '#374151',
        'startAt' => 0,
        'allowScrubbing' => true,
        'allowFullscreen' => true,
        'showClosedCaptions' => false,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('videoUrl', 'Video URL', 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4', true)
                ->placeholder('Enter video URL')
                ->helpText('URL of the video file (MP4, WebM, etc.)'),
            TextField::create('posterImage', 'Poster Image', 'https://via.placeholder.com/640x360/000000/FFFFFF?text=Video+Poster', false)
                ->placeholder('Enter poster image URL')
                ->helpText('Thumbnail image shown before playing'),
            BooleanField::create('autoPlay', 'Auto Play', false)
                ->helpText('Start playing automatically'),
            BooleanField::create('loop', 'Loop', false)
                ->helpText('Repeat video when it ends'),
            BooleanField::create('muted', 'Muted', false)
                ->helpText('Start with audio muted'),
            BooleanField::create('showControls', 'Show Controls', true)
                ->helpText('Display video control bar'),
            BooleanField::create('showPlayButton', 'Show Play Button', true)
                ->helpText('Show play/pause button overlay'),
            BooleanField::create('showProgressBar', 'Show Progress Bar', true)
                ->helpText('Display video progress bar'),
            BooleanField::create('showVolumeControl', 'Show Volume Control', true)
                ->helpText('Display volume control'),
            BooleanField::create('showFullscreenButton', 'Show Fullscreen Button', true)
                ->helpText('Display fullscreen toggle button'),
            BooleanField::create('showTimeDisplay', 'Show Time Display', true)
                ->helpText('Show current time and duration'),
            NumberField::create('aspectRatio', 'Aspect Ratio', 16/9, false, 0.5, 5.0)
                ->step(0.1)
                ->helpText('Width to height ratio (16:9 = 1.78)'),
            SelectField::create('fit', 'Video Fit', [
                'contain' => 'Contain (fit within bounds)',
                'cover' => 'Cover (fill bounds, may crop)',
                'fill' => 'Fill (stretch to fit)',
                'fitWidth' => 'Fit Width',
                'fitHeight' => 'Fit Height',
            ], 'contain')
                ->helpText('How video fits in the container'),
            ColorField::create('backgroundColor', 'Background Color', '#000000')
                ->helpText('Background color behind the video'),
            ColorField::create('controlsBackgroundColor', 'Controls Background', '#000000')
                ->helpText('Background color for control bar'),
            NumberField::create('controlsOpacity', 'Controls Opacity', 0.7, false, 0.0, 1.0)
                ->step(0.1)
                ->helpText('Opacity of the control bar'),
            NumberField::create('playButtonSize', 'Play Button Size', 48, false, 24, 100)
                ->helpText('Size of the play button overlay'),
            ColorField::create('playButtonColor', 'Play Button Color', '#FFFFFF')
                ->helpText('Color of the play button'),
            ColorField::create('progressBarColor', 'Progress Bar Color', '#3B82F6')
                ->helpText('Color of the progress bar'),
            ColorField::create('progressBarBackgroundColor', 'Progress Background', '#374151')
                ->helpText('Background color of progress bar'),
            NumberField::create('startAt', 'Start At (seconds)', 0, false, 0, 3600)
                ->helpText('Start playing from this time'),
            BooleanField::create('allowScrubbing', 'Allow Scrubbing', true)
                ->helpText('Allow seeking by dragging progress bar'),
            BooleanField::create('allowFullscreen', 'Allow Fullscreen', true)
                ->helpText('Enable fullscreen mode'),
            BooleanField::create('showClosedCaptions', 'Show Closed Captions', false)
                ->helpText('Display closed captions if available'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'VideoPlayer',
            'data' => [
                'videoUrl' => $mergedConfig['videoUrl'],
                'posterImage' => $mergedConfig['posterImage'],
                'autoPlay' => $mergedConfig['autoPlay'],
                'loop' => $mergedConfig['loop'],
                'muted' => $mergedConfig['muted'],
                'startAt' => (float) $mergedConfig['startAt'],
                'aspectRatio' => (float) $mergedConfig['aspectRatio'],
                'fit' => $mergedConfig['fit'],
                'allowScrubbing' => $mergedConfig['allowScrubbing'],
                'allowFullscreen' => $mergedConfig['allowFullscreen'],
                'showClosedCaptions' => $mergedConfig['showClosedCaptions'],
                'controls' => [
                    'show' => $mergedConfig['showControls'],
                    'showPlayButton' => $mergedConfig['showPlayButton'],
                    'showProgressBar' => $mergedConfig['showProgressBar'],
                    'showVolumeControl' => $mergedConfig['showVolumeControl'],
                    'showFullscreenButton' => $mergedConfig['showFullscreenButton'],
                    'showTimeDisplay' => $mergedConfig['showTimeDisplay'],
                ],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'controlsStyle' => [
                        'backgroundColor' => $mergedConfig['controlsBackgroundColor'],
                        'opacity' => (float) $mergedConfig['controlsOpacity'],
                    ],
                    'playButtonStyle' => [
                        'size' => (float) $mergedConfig['playButtonSize'],
                        'color' => $mergedConfig['playButtonColor'],
                        'backgroundColor' => 'rgba(0, 0, 0, 0.5)',
                        'borderRadius' => (float) $mergedConfig['playButtonSize'] / 2,
                    ],
                    'progressBarStyle' => [
                        'activeColor' => $mergedConfig['progressBarColor'],
                        'backgroundColor' => $mergedConfig['progressBarBackgroundColor'],
                        'height' => 4.0,
                    ],
                ],
                'constraints' => [
                    'minHeight' => 100.0,
                    'maxHeight' => 500.0,
                ],
            ],
        ];
    }
}