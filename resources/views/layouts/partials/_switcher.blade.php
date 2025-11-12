<!-- Start Switcher -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom d-block p-0">
        <div class="d-flex align-items-center justify-content-between p-3">
            <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <nav class="border-top border-block-start-dashed">
            <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home" type="button" role="tab" aria-controls="switcher-home" aria-selected="true">Theme Styles</button>
                <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile" type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">Theme Colors</button>
            </div>
        </nav>
    </div>
    <div class="offcanvas-body">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab" tabindex="0">
                <div class="">
                    <p class="switcher-style-head">Theme Color Mode:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-light-theme">Light</label>
                                <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme" checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-dark-theme">Dark</label>
                                <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <p class="switcher-style-head">Directions:</p>
                    <div class="row switcher-style gx-0">
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-ltr">LTR</label>
                                <input class="form-check-input" type="radio" name="direction" id="switcher-ltr" checked>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-check switch-select">
                                <label class="form-check-label" for="switcher-rtl">RTL</label>
                                <input class="form-check-input" type="radio" name="direction" id="switcher-rtl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                <div class="theme-colors">
                    <p class="switcher-style-head">Theme Primary:</p>
                    <div class="d-flex switcher-style pb-2">
                        <div class="theme-container-primary"></div>
                        <div class="pickr-container-primary"></div>
                    </div>
                </div>
                <div class="theme-colors">
                    <p class="switcher-style-head">Theme Background:</p>
                    <div class="d-flex switcher-style pb-2">
                        <div class="theme-container-background"></div>
                        <div class="pickr-container-background"></div>
                    </div>
                </div>
                <div class="theme-colors">
                    <p class="switcher-style-head">Menu Colors:</p>
                    <div class="d-flex switcher-style pb-2">
                        <div class="form-check switch-select me-3">
                            <input class="form-check-input color-input color-white" type="radio" name="menu-colors" id="switcher-menu-light" checked>
                        </div>
                        <div class="form-check switch-select me-3">
                            <input class="form-check-input color-input color-dark" type="radio" name="menu-colors" id="switcher-menu-dark">
                        </div>
                        <div class="form-check switch-select me-3">
                            <input class="form-check-input color-input color-primary" type="radio" name="menu-colors" id="switcher-menu-primary">
                        </div>
                    </div>
                </div>
                <div class="theme-colors">
                    <p class="switcher-style-head">Header Colors:</p>
                    <div class="d-flex switcher-style pb-2">
                        <div class="form-check switch-select me-3">
                            <input class="form-check-input color-input color-white" type="radio" name="header-colors" id="switcher-header-light" checked>
                        </div>
                        <div class="form-check switch-select me-3">
                            <input class="form-check-input color-input color-dark" type="radio" name="header-colors" id="switcher-header-dark">
                        </div>
                        <div class="form-check switch-select me-3">
                            <input class="form-check-input color-input color-primary" type="radio" name="header-colors" id="switcher-header-primary">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-block canvas-footer flex-wrap">
            <a href="javascript:void(0);" id="reset-all" class="btn btn-danger m-1 w-100">Reset</a>
        </div>
    </div>
</div>
<!-- End Switcher -->

<!-- Loader -->
<div id="loader">
    <img src="{{ asset('assets/images/media/loader.svg') }}" alt="">
</div>
<!-- Loader -->

