<!-- Page Preloader -->
<div id="page-preloader">
    <div class="preloader-content">
        <!-- Logo -->
        <div class="preloader-logo">
            @if(file_exists(public_path('assets/images/brand-logos/logo.png')))
                <img src="{{ asset('assets/images/brand-logos/logo.png') }}" alt="Logo">
            @elseif(file_exists(public_path('assets/images/brand-logos/logo.svg')))
                <img src="{{ asset('assets/images/brand-logos/logo.svg') }}" alt="Logo">
            @elseif(file_exists(public_path('assets/images/brand-logos/desktop-dark.png')))
                <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="Logo">
            @else
                <div style="width: 160px; height: 50px; background: linear-gradient(135deg, #6259ca, #8b84e3); border-radius: 8px; margin: 0 auto; box-shadow: 0 4px 12px rgba(98, 89, 202, 0.3);"></div>
            @endif
        </div>
        
        <!-- Spinner -->
        <div class="preloader-spinner-wrapper">
            @if(file_exists(public_path('assets/images/media/loader.svg')))
                <img src="{{ asset('assets/images/media/loader.svg') }}" alt="Loading" class="preloader-svg-loader">
            @else
                <div class="preloader-spinner"></div>
            @endif
        </div>
        
        <!-- Loading Text -->
        <div class="preloader-text">Loading</div>
        
        <!-- Progress Bar -->
        <div class="preloader-progress-wrapper">
            <div class="preloader-percentage">0%</div>
            <div class="preloader-progress">
                <div class="preloader-progress-bar"></div>
            </div>
        </div>
        
        <!-- Loading Dots -->
        <div class="preloader-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>

