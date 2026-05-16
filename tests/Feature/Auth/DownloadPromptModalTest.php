<?php

namespace Tests\Feature\Auth;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Bug Condition Exploration Test
 * 
 * Property 1: Bug Condition - Modal Displays in Native Android App
 * 
 * CRITICAL: This test MUST FAIL on unfixed code - failure confirms the bug exists
 * DO NOT attempt to fix the test or the code when it fails
 * 
 * Goal: Surface counterexamples that demonstrate the bug exists
 * 
 * Bug Condition: When running in Capacitor native platform (Android app),
 * the download prompt modal should NOT display, but currently it does.
 * 
 * Expected Behavior (after fix): 
 * - For context where isCapacitorNativePlatform = true, modal SHALL NOT be displayed
 * - Modal script should check Capacitor.isNativePlatform() and return early
 * 
 * Requirements: 1.1, 1.2
 */
class DownloadPromptModalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that login page contains download prompt modal script
     * 
     * This test verifies the modal script is present in the rendered HTML.
     * On UNFIXED code, this script does NOT check for native platform,
     * causing the modal to display in the Android app.
     * 
     * EXPECTED OUTCOME ON UNFIXED CODE: PASS (script exists but lacks platform check)
     * EXPECTED OUTCOME AFTER FIX: PASS (script exists with platform check)
     */
    public function test_login_page_contains_download_prompt_modal_script(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('downloadPromptModal', false);
        $response->assertSee('DOMContentLoaded', false);
    }

    /**
     * Test that modal script NOW HAS Capacitor platform detection (FIX VERIFICATION)
     * 
     * This test was originally checking that the bug exists (no platform check).
     * After the fix, this test is inverted to verify the platform check is present.
     * 
     * EXPECTED OUTCOME ON UNFIXED CODE: PASS (confirmed bug exists - no platform check)
     * EXPECTED OUTCOME AFTER FIX: This test is now inverted to check FOR the platform check
     * 
     * Requirements: 2.1, 2.2
     */
    public function test_modal_script_now_has_capacitor_platform_detection(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // After fix, the script SHOULD contain Capacitor platform check
        // This confirms the fix: modal will NOT display in native app
        $response->assertSee('Capacitor', false);
        $response->assertSee('isNativePlatform', false);
        
        // Verify the early return logic
        $content = $response->getContent();
        $this->assertStringContainsString('if (window.Capacitor && window.Capacitor.isNativePlatform())', $content);
    }

    /**
     * Test that modal script checks Capacitor platform (EXPECTED BEHAVIOR)
     * 
     * This test encodes the expected behavior after the fix is implemented.
     * The modal script should check if running in Capacitor native platform
     * and skip modal display logic if true.
     * 
     * EXPECTED OUTCOME ON UNFIXED CODE: FAIL (confirms bug - no platform check)
     * EXPECTED OUTCOME AFTER FIX: PASS (confirms fix - platform check exists)
     * 
     * Requirements: 2.1, 2.2
     */
    public function test_modal_script_checks_capacitor_platform_after_fix(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // After fix, the script SHOULD contain Capacitor platform check
        // This verifies the fix: modal will NOT display in native app
        $response->assertSee('Capacitor', false);
        $response->assertSee('isNativePlatform', false);
        
        // Verify the check happens before modal display logic
        $content = $response->getContent();
        $this->assertStringContainsString('window.Capacitor', $content);
        $this->assertStringContainsString('isNativePlatform()', $content);
    }

    /**
     * Test modal displays when download prompt is enabled (WEB BROWSER)
     * 
     * This test verifies the modal HTML is present when the setting is enabled.
     * This is the CORRECT behavior for web browser users.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     */
    public function test_modal_displays_when_download_prompt_enabled(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        Setting::set('app.download_url', 'https://example.com/download');
        Setting::set('app.download_prompt_title', 'Download Aplikasi RigelCoin');
        
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('downloadPromptModal', false);
        $response->assertSee('Download Aplikasi RigelCoin', false);
        $response->assertSee('Download aplikasi', false);
        $response->assertSee('Lanjutkan di browser', false);
    }

    /**
     * Test modal does not display when download prompt is disabled
     * 
     * This test verifies the modal script returns early when the setting is disabled.
     * This is the CORRECT behavior for all users when the feature is disabled.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     */
    public function test_modal_does_not_display_when_download_prompt_disabled(): void
    {
        Setting::set('app.download_prompt_enabled', false);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Modal HTML should still be present in the page
        $response->assertSee('downloadPromptModal', false);
        
        // But the script should check the enabled flag and return early
        $content = $response->getContent();
        $this->assertStringContainsString('const enabled = false', $content);
    }

    /**
     * PRESERVATION TEST: Modal displays with correct content for web users
     * 
     * Property 2: Preservation - Web Browser Modal Display Unchanged
     * 
     * This test verifies that web browser users continue to see the modal
     * with all the correct content from settings.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     * Requirements: 3.1, 3.2
     */
    public function test_preservation_modal_displays_with_correct_content_for_web_users(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        Setting::set('app.download_url', 'https://play.google.com/store/apps/details?id=com.rigelcoin.app');
        Setting::set('app.download_prompt_title', 'Download Aplikasi RigelCoin');
        Setting::set('app.download_prompt_body', 'Beli & jual coin lebih cepat, pantau transaksi, dan klaim bonus/komisi langsung dari aplikasi.');
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Verify modal HTML structure is present
        $response->assertSee('downloadPromptModal', false);
        $response->assertSee('downloadPromptSheet', false);
        $response->assertSee('downloadPromptClose', false);
        $response->assertSee('downloadPromptContinue', false);
        
        // Verify modal content from settings
        $response->assertSee('Download Aplikasi RigelCoin', false);
        $response->assertSee('Beli & jual coin lebih cepat, pantau transaksi, dan klaim bonus/komisi langsung dari aplikasi.', false);
        $response->assertSee('Download aplikasi', false);
        $response->assertSee('Lanjutkan di browser', false);
        
        // Verify download URL is present
        $response->assertSee('https://play.google.com/store/apps/details?id=com.rigelcoin.app', false);
    }

    /**
     * PRESERVATION TEST: Modal script contains localStorage tracking logic
     * 
     * This test verifies that the modal script includes localStorage tracking
     * to prevent showing the modal multiple times on the same day.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     * Requirements: 3.3
     */
    public function test_preservation_modal_script_contains_localstorage_tracking(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Verify localStorage key is defined
        $response->assertSee('rigel_download_prompt_last_closed_date', false);
        
        // Verify localStorage get logic
        $response->assertSee('localStorage.getItem', false);
        
        // Verify localStorage set logic
        $response->assertSee('localStorage.setItem', false);
        
        // Verify date comparison logic
        $response->assertSee('toISOString', false);
    }

    /**
     * PRESERVATION TEST: Modal has close button functionality
     * 
     * This test verifies that the modal includes close button event listeners.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     * Requirements: 3.5
     */
    public function test_preservation_modal_has_close_button_functionality(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Verify close button exists
        $response->assertSee('downloadPromptClose', false);
        
        // Verify close button event listener
        $response->assertSee('closeBtn.addEventListener', false);
        
        // Verify continue button event listener
        $response->assertSee('continueBtn.addEventListener', false);
        
        // Verify close function
        $response->assertSee('function close()', false);
    }

    /**
     * PRESERVATION TEST: Modal has escape key and click-outside functionality
     * 
     * This test verifies that the modal can be closed with Escape key
     * and by clicking outside the modal.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     * Requirements: 3.5
     */
    public function test_preservation_modal_has_escape_and_click_outside_functionality(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Verify escape key listener
        $response->assertSee('keydown', false);
        $response->assertSee('Escape', false);
        
        // Verify click outside listener
        $response->assertSee('modal.addEventListener', false);
        $response->assertSee('e.target === modal', false);
    }

    /**
     * PRESERVATION TEST: Download button is disabled when URL is empty
     * 
     * This test verifies that when download URL is empty, the download button
     * is rendered in a disabled state.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     * Requirements: 3.4
     */
    public function test_preservation_download_button_disabled_when_url_empty(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        Setting::set('app.download_url', ''); // Empty URL
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Verify download button has disabled styling
        $response->assertSee('bg-neutral-200 text-neutral-500 cursor-not-allowed', false);
        $response->assertSee('aria-disabled=true', false);
        
        // Verify href is javascript:void(0) when URL is empty
        $response->assertSee('javascript:void(0)', false);
    }

    /**
     * PRESERVATION TEST: Download button is enabled when URL is provided
     * 
     * This test verifies that when download URL is provided, the download button
     * is rendered in an enabled state with the correct link.
     * 
     * EXPECTED OUTCOME: PASS (on both unfixed and fixed code)
     * Requirements: 3.4
     */
    public function test_preservation_download_button_enabled_when_url_provided(): void
    {
        Setting::set('app.download_prompt_enabled', true);
        Setting::set('app.download_url', 'https://example.com/download');
        
        $response = $this->get(route('login'));

        $response->assertOk();
        
        // Verify download button has enabled styling
        $response->assertSee('bg-neutral-900 text-white hover:bg-neutral-800', false);
        
        // Verify href contains the download URL
        $response->assertSee('https://example.com/download', false);
    }

    /**
     * Manual Test Instructions for Native Android App
     * 
     * Since automated browser testing with Capacitor is complex, this test
     * documents the manual testing procedure to verify the bug and the fix.
     * 
     * MANUAL TEST PROCEDURE (UNFIXED CODE):
     * 1. Build and install the Android app on a physical device or emulator
     * 2. Open the app and navigate to the login page
     * 3. Observe that the "Download Aplikasi RigelCoin" modal appears
     * 4. EXPECTED: Modal should NOT appear (BUG - this confirms the bug exists)
     * 5. Document counterexample: "Modal displays in native Android app when it should not"
     * 
     * MANUAL TEST PROCEDURE (FIXED CODE):
     * 1. Build and install the Android app with the fix
     * 2. Open the app and navigate to the login page
     * 3. Observe that the "Download Aplikasi RigelCoin" modal does NOT appear
     * 4. EXPECTED: Modal should NOT appear (CORRECT - this confirms the fix works)
     * 5. Verify in browser console (if accessible): Capacitor.isNativePlatform() returns true
     * 
     * MANUAL TEST PROCEDURE (WEB BROWSER - PRESERVATION):
     * 1. Open the app in Chrome browser (desktop or mobile)
     * 2. Navigate to the login page
     * 3. Observe that the "Download Aplikasi RigelCoin" modal appears
     * 4. EXPECTED: Modal SHOULD appear (CORRECT - preservation of web behavior)
     * 5. Close the modal and verify localStorage is set
     * 6. Refresh the page and verify modal does not appear again (same day)
     */
    public function test_manual_testing_instructions_for_native_app(): void
    {
        $this->markTestIncomplete(
            'This test requires manual verification in the native Android app. ' .
            'See the docblock above for detailed manual testing instructions. ' .
            'After manual testing, update this test with the results.'
        );
    }
}

