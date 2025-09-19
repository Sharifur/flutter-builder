<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class AudioPlayerComponent extends BaseComponent
{
    protected string $name = 'Audio Player';
    protected string $type = 'AudioPlayer';
    protected string $category = 'media';
    protected string $description = 'Audio player with playback controls and waveform visualization';
    protected ?string $icon = 'music-note';
    protected int $sortOrder = 71;

    protected array $defaultConfig = [
        'audioUrl' => 'https://www.soundjay.com/misc/sounds/bell-ringing-05.wav',
        'title' => 'Audio Track',
        'artist' => 'Unknown Artist',
        'albumArt' => 'https://via.placeholder.com/150x150/6366F1/FFFFFF?text=♪',
        'autoPlay' => false,
        'loop' => false,
        'showControls' => true,
        'showPlayButton' => true,
        'showProgressBar' => true,
        'showVolumeControl' => true,
        'showTimeDisplay' => true,
        'showTitle' => true,
        'showArtist' => true,
        'showAlbumArt' => true,
        'showWaveform' => false,
        'backgroundColor' => '#FFFFFF',
        'primaryColor' => '#6366F1',
        'secondaryColor' => '#8B5CF6',
        'textColor' => '#1F2937',
        'subtitleColor' => '#6B7280',
        'progressBarColor' => '#6366F1',
        'progressBarBackgroundColor' => '#E5E7EB',
        'playButtonSize' => 56,
        'elevation' => 2,
        'borderRadius' => 12,
        'startAt' => 0,
        'volume' => 1.0,
        'playbackSpeed' => 1.0,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('audioUrl', 'Audio URL', 'https://www.soundjay.com/misc/sounds/bell-ringing-05.wav', true)
                ->placeholder('Enter audio URL')
                ->helpText('URL of the audio file (MP3, WAV, etc.)'),
            TextField::create('title', 'Track Title', 'Audio Track', false)
                ->placeholder('Enter track title')
                ->maxLength(100)
                ->helpText('Title of the audio track'),
            TextField::create('artist', 'Artist Name', 'Unknown Artist', false)
                ->placeholder('Enter artist name')
                ->maxLength(100)
                ->helpText('Name of the artist'),
            TextField::create('albumArt', 'Album Art URL', 'https://via.placeholder.com/150x150/6366F1/FFFFFF?text=♪', false)
                ->placeholder('Enter album art URL')
                ->helpText('URL of the album artwork'),
            BooleanField::create('autoPlay', 'Auto Play', false)
                ->helpText('Start playing automatically'),
            BooleanField::create('loop', 'Loop', false)
                ->helpText('Repeat audio when it ends'),
            BooleanField::create('showControls', 'Show Controls', true)
                ->helpText('Display playback controls'),
            BooleanField::create('showPlayButton', 'Show Play Button', true)
                ->helpText('Show play/pause button'),
            BooleanField::create('showProgressBar', 'Show Progress Bar', true)
                ->helpText('Display audio progress bar'),
            BooleanField::create('showVolumeControl', 'Show Volume Control', true)
                ->helpText('Display volume slider'),
            BooleanField::create('showTimeDisplay', 'Show Time Display', true)
                ->helpText('Show current time and duration'),
            BooleanField::create('showTitle', 'Show Title', true)
                ->helpText('Display track title'),
            BooleanField::create('showArtist', 'Show Artist', true)
                ->helpText('Display artist name'),
            BooleanField::create('showAlbumArt', 'Show Album Art', true)
                ->helpText('Display album artwork'),
            BooleanField::create('showWaveform', 'Show Waveform', false)
                ->helpText('Display audio waveform visualization'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('Background color of the player'),
            ColorField::create('primaryColor', 'Primary Color', '#6366F1')
                ->helpText('Primary accent color'),
            ColorField::create('secondaryColor', 'Secondary Color', '#8B5CF6')
                ->helpText('Secondary accent color'),
            ColorField::create('textColor', 'Text Color', '#1F2937')
                ->helpText('Color for title and main text'),
            ColorField::create('subtitleColor', 'Subtitle Color', '#6B7280')
                ->helpText('Color for artist and subtitle text'),
            ColorField::create('progressBarColor', 'Progress Bar Color', '#6366F1')
                ->helpText('Color of the progress bar'),
            ColorField::create('progressBarBackgroundColor', 'Progress Background', '#E5E7EB')
                ->helpText('Background color of progress bar'),
            NumberField::create('playButtonSize', 'Play Button Size', 56, false, 32, 100)
                ->helpText('Size of the play/pause button'),
            NumberField::create('elevation', 'Elevation', 2, false, 0, 10)
                ->helpText('Shadow depth of the player'),
            NumberField::create('borderRadius', 'Border Radius', 12, false, 0, 30)
                ->helpText('Corner rounding of the player'),
            NumberField::create('startAt', 'Start At (seconds)', 0, false, 0, 3600)
                ->helpText('Start playing from this time'),
            NumberField::create('volume', 'Default Volume', 1.0, false, 0.0, 1.0)
                ->step(0.1)
                ->helpText('Initial volume level (0.0 to 1.0)'),
            NumberField::create('playbackSpeed', 'Playback Speed', 1.0, false, 0.5, 3.0)
                ->step(0.1)
                ->helpText('Playback speed multiplier'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'AudioPlayer',
            'data' => [
                'audioUrl' => $mergedConfig['audioUrl'],
                'title' => $mergedConfig['title'],
                'artist' => $mergedConfig['artist'],
                'albumArt' => $mergedConfig['albumArt'],
                'autoPlay' => $mergedConfig['autoPlay'],
                'loop' => $mergedConfig['loop'],
                'startAt' => (float) $mergedConfig['startAt'],
                'volume' => (float) $mergedConfig['volume'],
                'playbackSpeed' => (float) $mergedConfig['playbackSpeed'],
                'display' => [
                    'showTitle' => $mergedConfig['showTitle'],
                    'showArtist' => $mergedConfig['showArtist'],
                    'showAlbumArt' => $mergedConfig['showAlbumArt'],
                    'showWaveform' => $mergedConfig['showWaveform'],
                ],
                'controls' => [
                    'show' => $mergedConfig['showControls'],
                    'showPlayButton' => $mergedConfig['showPlayButton'],
                    'showProgressBar' => $mergedConfig['showProgressBar'],
                    'showVolumeControl' => $mergedConfig['showVolumeControl'],
                    'showTimeDisplay' => $mergedConfig['showTimeDisplay'],
                ],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'primaryColor' => $mergedConfig['primaryColor'],
                    'secondaryColor' => $mergedConfig['secondaryColor'],
                    'textColor' => $mergedConfig['textColor'],
                    'subtitleColor' => $mergedConfig['subtitleColor'],
                    'elevation' => (float) $mergedConfig['elevation'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'playButtonStyle' => [
                        'size' => (float) $mergedConfig['playButtonSize'],
                        'backgroundColor' => $mergedConfig['primaryColor'],
                        'iconColor' => '#FFFFFF',
                        'borderRadius' => (float) $mergedConfig['playButtonSize'] / 2,
                    ],
                    'progressBarStyle' => [
                        'activeColor' => $mergedConfig['progressBarColor'],
                        'backgroundColor' => $mergedConfig['progressBarBackgroundColor'],
                        'height' => 4.0,
                        'thumbRadius' => 8.0,
                    ],
                    'titleStyle' => [
                        'fontSize' => 16.0,
                        'fontWeight' => 'w600',
                        'color' => $mergedConfig['textColor'],
                    ],
                    'artistStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['subtitleColor'],
                    ],
                    'timeStyle' => [
                        'fontSize' => 12.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['subtitleColor'],
                    ],
                ],
                'constraints' => [
                    'minHeight' => 80.0,
                    'maxHeight' => 200.0,
                ],
                'layout' => [
                    'albumArtSize' => 60.0,
                    'padding' => 16.0,
                    'spacing' => 12.0,
                ],
            ],
        ];
    }
}