<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\TextareaField;

class LoginFormComponent extends BaseComponent
{
    protected string $name = 'Login Form';
    protected string $type = 'LoginForm';
    protected string $category = 'auth';
    protected string $description = 'User login form with email/password authentication';
    protected ?string $icon = 'login';
    protected int $sortOrder = 90;

    protected array $defaultConfig = [
        'title' => 'Sign in',
        'subtitle' => 'Hello there, sign in to continue!',
        'emailLabel' => 'Username or email',
        'emailPlaceholder' => 'Enter your username or email',
        'passwordLabel' => 'Password',
        'passwordPlaceholder' => 'Enter your password',
        'loginButtonText' => 'Sign In',
        'forgotPasswordText' => 'Forgot Password?',
        'signUpText' => "Don't have an account? Sign up",
        'showTitle' => true,
        'showSubtitle' => true,
        'showForgotPassword' => true,
        'showSignUpLink' => true,
        'showSocialLogin' => false,
        'socialLoginText' => 'Sign in with Google',
        'rememberMe' => false,
        'backgroundColor' => '#FFFFFF',
        'primaryColor' => '#6366F1',
        'textColor' => '#1F2937',
        'subtitleColor' => '#6B7280',
        'inputBackgroundColor' => '#F9FAFB',
        'inputBorderColor' => '#D1D5DB',
        'buttonBackgroundColor' => '#6366F1',
        'buttonTextColor' => '#FFFFFF',
        'linkColor' => '#6366F1',
        'borderRadius' => 8,
        'padding' => 20,
        'spacing' => 16,
        'titleFontSize' => 28,
        'subtitleFontSize' => 16,
        'inputHeight' => 56,
        'buttonHeight' => 56,
        'logoUrl' => '',
        'showLogo' => false,
        'formStyle' => 'modern',
        'layout' => 'center',
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Title', 'Sign in', false)
                ->placeholder('Enter form title')
                ->maxLength(50)
                ->helpText('Main title of the login form'),
            TextField::create('subtitle', 'Subtitle', 'Hello there, sign in to continue!', false)
                ->placeholder('Enter subtitle')
                ->maxLength(100)
                ->helpText('Subtitle or welcome message'),
            BooleanField::create('showTitle', 'Show Title', true)
                ->helpText('Display the form title'),
            BooleanField::create('showSubtitle', 'Show Subtitle', true)
                ->helpText('Display the subtitle'),
            TextField::create('emailLabel', 'Email Field Label', 'Username or email', false)
                ->placeholder('Email field label')
                ->maxLength(50),
            TextField::create('emailPlaceholder', 'Email Placeholder', 'Enter your username or email', false)
                ->placeholder('Email field placeholder')
                ->maxLength(100),
            TextField::create('passwordLabel', 'Password Field Label', 'Password', false)
                ->placeholder('Password field label')
                ->maxLength(50),
            TextField::create('passwordPlaceholder', 'Password Placeholder', 'Enter your password', false)
                ->placeholder('Password field placeholder')
                ->maxLength(100),
            TextField::create('loginButtonText', 'Login Button Text', 'Sign In', false)
                ->placeholder('Login button text')
                ->maxLength(30),
            TextField::create('forgotPasswordText', 'Forgot Password Text', 'Forgot Password?', false)
                ->placeholder('Forgot password link text')
                ->maxLength(50),
            TextField::create('signUpText', 'Sign Up Text', "Don't have an account? Sign up", false)
                ->placeholder('Sign up link text')
                ->maxLength(100),
            BooleanField::create('showForgotPassword', 'Show Forgot Password', true)
                ->helpText('Display forgot password link'),
            BooleanField::create('showSignUpLink', 'Show Sign Up Link', true)
                ->helpText('Display sign up link'),
            BooleanField::create('rememberMe', 'Show Remember Me', false)
                ->helpText('Display remember me checkbox'),
            BooleanField::create('showSocialLogin', 'Show Social Login', false)
                ->helpText('Display social login options'),
            TextField::create('socialLoginText', 'Social Login Text', 'Sign in with Google', false)
                ->placeholder('Social login button text')
                ->maxLength(50),
            TextField::create('logoUrl', 'Logo URL', '', false)
                ->placeholder('Enter logo URL')
                ->helpText('URL for the logo image'),
            BooleanField::create('showLogo', 'Show Logo', false)
                ->helpText('Display logo above the form'),
            SelectField::create('formStyle', 'Form Style', [
                'modern' => 'Modern',
                'classic' => 'Classic',
                'minimal' => 'Minimal',
                'card' => 'Card Style',
            ], 'modern')
                ->helpText('Visual style of the form'),
            SelectField::create('layout', 'Layout', [
                'center' => 'Center',
                'left' => 'Left Aligned',
                'right' => 'Right Aligned',
                'full' => 'Full Width',
            ], 'center')
                ->helpText('Form layout alignment'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('Form background color'),
            ColorField::create('primaryColor', 'Primary Color', '#6366F1')
                ->helpText('Primary brand color'),
            ColorField::create('textColor', 'Text Color', '#1F2937')
                ->helpText('Main text color'),
            ColorField::create('subtitleColor', 'Subtitle Color', '#6B7280')
                ->helpText('Subtitle text color'),
            ColorField::create('inputBackgroundColor', 'Input Background', '#F9FAFB')
                ->helpText('Input field background color'),
            ColorField::create('inputBorderColor', 'Input Border Color', '#D1D5DB')
                ->helpText('Input field border color'),
            ColorField::create('buttonBackgroundColor', 'Button Background', '#6366F1')
                ->helpText('Login button background color'),
            ColorField::create('buttonTextColor', 'Button Text Color', '#FFFFFF')
                ->helpText('Login button text color'),
            ColorField::create('linkColor', 'Link Color', '#6366F1')
                ->helpText('Color for links and clickable text'),
            NumberField::create('borderRadius', 'Border Radius', 8, false, 0, 50)
                ->helpText('Corner rounding for form elements'),
            NumberField::create('padding', 'Form Padding', 20, false, 0, 50)
                ->helpText('Internal padding of the form'),
            NumberField::create('spacing', 'Element Spacing', 16, false, 0, 50)
                ->helpText('Spacing between form elements'),
            NumberField::create('titleFontSize', 'Title Font Size', 28, false, 12, 48)
                ->helpText('Font size for the title'),
            NumberField::create('subtitleFontSize', 'Subtitle Font Size', 16, false, 10, 24)
                ->helpText('Font size for the subtitle'),
            NumberField::create('inputHeight', 'Input Height', 56, false, 32, 80)
                ->helpText('Height of input fields'),
            NumberField::create('buttonHeight', 'Button Height', 56, false, 32, 80)
                ->helpText('Height of the login button'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'LoginForm',
            'data' => [
                'title' => $mergedConfig['title'],
                'subtitle' => $mergedConfig['subtitle'],
                'showTitle' => $mergedConfig['showTitle'],
                'showSubtitle' => $mergedConfig['showSubtitle'],
                'emailField' => [
                    'label' => $mergedConfig['emailLabel'],
                    'placeholder' => $mergedConfig['emailPlaceholder'],
                    'type' => 'email',
                    'required' => true,
                ],
                'passwordField' => [
                    'label' => $mergedConfig['passwordLabel'],
                    'placeholder' => $mergedConfig['passwordPlaceholder'],
                    'type' => 'password',
                    'required' => true,
                ],
                'loginButton' => [
                    'text' => $mergedConfig['loginButtonText'],
                    'action' => 'login',
                ],
                'forgotPassword' => [
                    'show' => $mergedConfig['showForgotPassword'],
                    'text' => $mergedConfig['forgotPasswordText'],
                    'action' => 'forgot-password',
                ],
                'signUpLink' => [
                    'show' => $mergedConfig['showSignUpLink'],
                    'text' => $mergedConfig['signUpText'],
                    'action' => 'navigate-signup',
                ],
                'rememberMe' => [
                    'show' => $mergedConfig['rememberMe'],
                    'text' => 'Remember me',
                ],
                'socialLogin' => [
                    'show' => $mergedConfig['showSocialLogin'],
                    'text' => $mergedConfig['socialLoginText'],
                    'provider' => 'google',
                    'action' => 'social-login',
                ],
                'logo' => [
                    'show' => $mergedConfig['showLogo'],
                    'url' => $mergedConfig['logoUrl'],
                ],
                'layout' => $mergedConfig['layout'],
                'formStyle' => $mergedConfig['formStyle'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'primaryColor' => $mergedConfig['primaryColor'],
                    'textColor' => $mergedConfig['textColor'],
                    'subtitleColor' => $mergedConfig['subtitleColor'],
                    'linkColor' => $mergedConfig['linkColor'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'padding' => (float) $mergedConfig['padding'],
                    'spacing' => (float) $mergedConfig['spacing'],
                    'titleStyle' => [
                        'fontSize' => (float) $mergedConfig['titleFontSize'],
                        'fontWeight' => 'w700',
                        'color' => $mergedConfig['textColor'],
                    ],
                    'subtitleStyle' => [
                        'fontSize' => (float) $mergedConfig['subtitleFontSize'],
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['subtitleColor'],
                    ],
                    'inputStyle' => [
                        'height' => (float) $mergedConfig['inputHeight'],
                        'backgroundColor' => $mergedConfig['inputBackgroundColor'],
                        'borderColor' => $mergedConfig['inputBorderColor'],
                        'borderRadius' => (float) $mergedConfig['borderRadius'],
                        'fontSize' => 16.0,
                        'padding' => 16.0,
                    ],
                    'buttonStyle' => [
                        'height' => (float) $mergedConfig['buttonHeight'],
                        'backgroundColor' => $mergedConfig['buttonBackgroundColor'],
                        'textColor' => $mergedConfig['buttonTextColor'],
                        'borderRadius' => (float) $mergedConfig['borderRadius'],
                        'fontSize' => 16.0,
                        'fontWeight' => 'w600',
                    ],
                    'linkStyle' => [
                        'color' => $mergedConfig['linkColor'],
                        'fontSize' => 14.0,
                        'fontWeight' => 'w500',
                    ],
                ],
                'validation' => [
                    'email' => ['required', 'email'],
                    'password' => ['required', 'min:6'],
                ],
                'apiEndpoint' => '/api/auth/login',
                'successAction' => 'navigate-dashboard',
            ],
        ];
    }
}