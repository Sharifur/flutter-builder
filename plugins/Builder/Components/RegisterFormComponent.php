<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\ArrayField;

class RegisterFormComponent extends BaseComponent
{
    protected string $name = 'Register Form';
    protected string $type = 'RegisterForm';
    protected string $category = 'auth';
    protected string $description = 'User registration form with customizable fields';
    protected ?string $icon = 'user-plus';
    protected int $sortOrder = 91;

    protected array $defaultConfig = [
        'title' => 'Create Account',
        'subtitle' => 'Join us today! Create your account to get started.',
        'nameLabel' => 'Full Name',
        'namePlaceholder' => 'Enter your full name',
        'emailLabel' => 'Email Address',
        'emailPlaceholder' => 'Enter your email address',
        'passwordLabel' => 'Password',
        'passwordPlaceholder' => 'Create a password',
        'confirmPasswordLabel' => 'Confirm Password',
        'confirmPasswordPlaceholder' => 'Confirm your password',
        'phoneLabel' => 'Phone Number',
        'phonePlaceholder' => 'Enter your phone number',
        'registerButtonText' => 'Create Account',
        'signInText' => 'Already have an account? Sign in',
        'termsText' => 'I agree to the Terms of Service and Privacy Policy',
        'showTitle' => true,
        'showSubtitle' => true,
        'showSignInLink' => true,
        'showPhoneField' => false,
        'showTermsCheckbox' => true,
        'showSocialRegister' => false,
        'socialRegisterText' => 'Sign up with Google',
        'requireEmailVerification' => false,
        'passwordMinLength' => 8,
        'showPasswordStrength' => true,
        'backgroundColor' => '#FFFFFF',
        'primaryColor' => '#6366F1',
        'textColor' => '#1F2937',
        'subtitleColor' => '#6B7280',
        'inputBackgroundColor' => '#F9FAFB',
        'inputBorderColor' => '#D1D5DB',
        'buttonBackgroundColor' => '#6366F1',
        'buttonTextColor' => '#FFFFFF',
        'linkColor' => '#6366F1',
        'errorColor' => '#EF4444',
        'successColor' => '#10B981',
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
        'additionalFields' => [],
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Title', 'Create Account', false)
                ->placeholder('Enter form title')
                ->maxLength(50)
                ->helpText('Main title of the registration form'),
            TextField::create('subtitle', 'Subtitle', 'Join us today! Create your account to get started.', false)
                ->placeholder('Enter subtitle')
                ->maxLength(150)
                ->helpText('Subtitle or welcome message'),
            BooleanField::create('showTitle', 'Show Title', true)
                ->helpText('Display the form title'),
            BooleanField::create('showSubtitle', 'Show Subtitle', true)
                ->helpText('Display the subtitle'),
            TextField::create('nameLabel', 'Name Field Label', 'Full Name', false)
                ->placeholder('Name field label')
                ->maxLength(50),
            TextField::create('namePlaceholder', 'Name Placeholder', 'Enter your full name', false)
                ->placeholder('Name field placeholder')
                ->maxLength(100),
            TextField::create('emailLabel', 'Email Field Label', 'Email Address', false)
                ->placeholder('Email field label')
                ->maxLength(50),
            TextField::create('emailPlaceholder', 'Email Placeholder', 'Enter your email address', false)
                ->placeholder('Email field placeholder')
                ->maxLength(100),
            TextField::create('passwordLabel', 'Password Field Label', 'Password', false)
                ->placeholder('Password field label')
                ->maxLength(50),
            TextField::create('passwordPlaceholder', 'Password Placeholder', 'Create a password', false)
                ->placeholder('Password field placeholder')
                ->maxLength(100),
            TextField::create('confirmPasswordLabel', 'Confirm Password Label', 'Confirm Password', false)
                ->placeholder('Confirm password label')
                ->maxLength(50),
            TextField::create('confirmPasswordPlaceholder', 'Confirm Password Placeholder', 'Confirm your password', false)
                ->placeholder('Confirm password placeholder')
                ->maxLength(100),
            BooleanField::create('showPhoneField', 'Show Phone Field', false)
                ->helpText('Include phone number field'),
            TextField::create('phoneLabel', 'Phone Field Label', 'Phone Number', false)
                ->placeholder('Phone field label')
                ->maxLength(50),
            TextField::create('phonePlaceholder', 'Phone Placeholder', 'Enter your phone number', false)
                ->placeholder('Phone field placeholder')
                ->maxLength(100),
            TextField::create('registerButtonText', 'Register Button Text', 'Create Account', false)
                ->placeholder('Register button text')
                ->maxLength(30),
            TextField::create('signInText', 'Sign In Text', 'Already have an account? Sign in', false)
                ->placeholder('Sign in link text')
                ->maxLength(100),
            TextField::create('termsText', 'Terms Text', 'I agree to the Terms of Service and Privacy Policy', false)
                ->placeholder('Terms and conditions text')
                ->maxLength(200),
            BooleanField::create('showSignInLink', 'Show Sign In Link', true)
                ->helpText('Display sign in link'),
            BooleanField::create('showTermsCheckbox', 'Show Terms Checkbox', true)
                ->helpText('Display terms and conditions checkbox'),
            BooleanField::create('showSocialRegister', 'Show Social Register', false)
                ->helpText('Display social registration options'),
            TextField::create('socialRegisterText', 'Social Register Text', 'Sign up with Google', false)
                ->placeholder('Social register button text')
                ->maxLength(50),
            BooleanField::create('requireEmailVerification', 'Require Email Verification', false)
                ->helpText('Send email verification after registration'),
            NumberField::create('passwordMinLength', 'Password Min Length', 8, false, 6, 20)
                ->helpText('Minimum password length requirement'),
            BooleanField::create('showPasswordStrength', 'Show Password Strength', true)
                ->helpText('Display password strength indicator'),
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
            ArrayField::create('additionalFields', 'Additional Fields', [], false)
                ->itemType('object')
                ->maxItems(10)
                ->helpText('Custom additional fields for registration'),
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
                ->helpText('Register button background color'),
            ColorField::create('buttonTextColor', 'Button Text Color', '#FFFFFF')
                ->helpText('Register button text color'),
            ColorField::create('linkColor', 'Link Color', '#6366F1')
                ->helpText('Color for links and clickable text'),
            ColorField::create('errorColor', 'Error Color', '#EF4444')
                ->helpText('Color for error messages'),
            ColorField::create('successColor', 'Success Color', '#10B981')
                ->helpText('Color for success messages'),
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
                ->helpText('Height of the register button'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        $fields = [
            [
                'name' => 'name',
                'label' => $mergedConfig['nameLabel'],
                'placeholder' => $mergedConfig['namePlaceholder'],
                'type' => 'text',
                'required' => true,
                'validation' => ['required', 'string', 'min:2', 'max:100'],
            ],
            [
                'name' => 'email',
                'label' => $mergedConfig['emailLabel'],
                'placeholder' => $mergedConfig['emailPlaceholder'],
                'type' => 'email',
                'required' => true,
                'validation' => ['required', 'email', 'unique:app_users,email'],
            ],
            [
                'name' => 'password',
                'label' => $mergedConfig['passwordLabel'],
                'placeholder' => $mergedConfig['passwordPlaceholder'],
                'type' => 'password',
                'required' => true,
                'validation' => ['required', 'string', 'min:' . $mergedConfig['passwordMinLength']],
                'showStrength' => $mergedConfig['showPasswordStrength'],
            ],
            [
                'name' => 'password_confirmation',
                'label' => $mergedConfig['confirmPasswordLabel'],
                'placeholder' => $mergedConfig['confirmPasswordPlaceholder'],
                'type' => 'password',
                'required' => true,
                'validation' => ['required', 'same:password'],
            ],
        ];

        if ($mergedConfig['showPhoneField']) {
            $fields[] = [
                'name' => 'phone',
                'label' => $mergedConfig['phoneLabel'],
                'placeholder' => $mergedConfig['phonePlaceholder'],
                'type' => 'tel',
                'required' => false,
                'validation' => ['nullable', 'string', 'regex:/^[\+]?[1-9][\d]{0,15}$/'],
            ];
        }

        // Add additional custom fields
        foreach ($mergedConfig['additionalFields'] as $additionalField) {
            if (isset($additionalField['name']) && isset($additionalField['type'])) {
                $fields[] = $additionalField;
            }
        }

        return [
            'type' => 'RegisterForm',
            'data' => [
                'title' => $mergedConfig['title'],
                'subtitle' => $mergedConfig['subtitle'],
                'showTitle' => $mergedConfig['showTitle'],
                'showSubtitle' => $mergedConfig['showSubtitle'],
                'fields' => $fields,
                'registerButton' => [
                    'text' => $mergedConfig['registerButtonText'],
                    'action' => 'register',
                ],
                'signInLink' => [
                    'show' => $mergedConfig['showSignInLink'],
                    'text' => $mergedConfig['signInText'],
                    'action' => 'navigate-login',
                ],
                'termsCheckbox' => [
                    'show' => $mergedConfig['showTermsCheckbox'],
                    'text' => $mergedConfig['termsText'],
                    'required' => true,
                ],
                'socialRegister' => [
                    'show' => $mergedConfig['showSocialRegister'],
                    'text' => $mergedConfig['socialRegisterText'],
                    'provider' => 'google',
                    'action' => 'social-register',
                ],
                'logo' => [
                    'show' => $mergedConfig['showLogo'],
                    'url' => $mergedConfig['logoUrl'],
                ],
                'layout' => $mergedConfig['layout'],
                'formStyle' => $mergedConfig['formStyle'],
                'settings' => [
                    'requireEmailVerification' => $mergedConfig['requireEmailVerification'],
                    'passwordMinLength' => $mergedConfig['passwordMinLength'],
                    'showPasswordStrength' => $mergedConfig['showPasswordStrength'],
                ],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'primaryColor' => $mergedConfig['primaryColor'],
                    'textColor' => $mergedConfig['textColor'],
                    'subtitleColor' => $mergedConfig['subtitleColor'],
                    'linkColor' => $mergedConfig['linkColor'],
                    'errorColor' => $mergedConfig['errorColor'],
                    'successColor' => $mergedConfig['successColor'],
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
                'apiEndpoint' => '/api/auth/register',
                'successAction' => $mergedConfig['requireEmailVerification'] ? 'show-verification-message' : 'navigate-login',
            ],
        ];
    }
}