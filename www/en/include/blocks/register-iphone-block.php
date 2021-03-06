<section class="register-iphone-block">
    <div class="wrapper">
        <div class="register-iphone-block-left">
            <h3 class="register-iphone-block-title">
                Register on BBCG website
            </h3>
            <div class="register-iphone-block-subtitle">
                Registration gives access to exclusive news and presentations, the opportunity to ask a question to the speaker, as well as discounts for participation in the summits
            </div>
            <? if (! $USER->IsAuthorized()): ?>
                <div class="register-iphone-block-button">
                    <a href="/registration/" data-side-modal data-side-modal-prevent-mobile data-side-modal-url="/include/blocks/modal-registration.php" data-side-modal-class="registration-modal" data-side-modal-prevent-overlay-close data-side-modal-prevent-esc-close class="button button-light-burgundy">
                        Registration
                    </a>
                </div>
            <? endif ?>
        </div>
        <div class="register-iphone-block-right">
            <div class="register-iphone-block-screen"></div>
            <div class="register-iphone-block-app">
                <div class="register-iphone-block-app-title">
                    Mobile application <br>
                    BBCG
                </div>
                <a href="<?=IOS_APP_LINK?>" target="_blank" class="register-iphone-block-app-icon">
                    <img src="/assets/images/icons/icon-appstore-white.svg" alt="App Store">
                </a>
                <br>
                <a href="<?=ANDROID_APP_LINK?>" target="_blank" class="register-iphone-block-app-icon">
                    <img src="/assets/images/icons/icon-google-play-white.svg" alt="Google Play">
                </a>
            </div>
        </div>
    </div>
</section>