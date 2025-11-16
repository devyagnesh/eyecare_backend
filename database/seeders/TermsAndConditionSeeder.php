<?php

namespace Database\Seeders;

use App\Models\TermsAndCondition;
use App\Models\User;
use Illuminate\Database\Seeder;

class TermsAndConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create a system user
        $adminUser = User::whereHas('role', function ($query) {
            $query->where('slug', 'admin');
        })->first();

        if (!$adminUser) {
            $adminUser = User::first();
        }

        $userId = $adminUser ? $adminUser->id : null;

        // Deactivate any existing active terms
        TermsAndCondition::where('is_active', true)->update(['is_active' => false]);

        $currentDate = date('F d, Y');
        
        $termsContent = <<<HTML
<h2>Terms and Conditions of Service</h2>
<p><strong>Last Updated:</strong> {$currentDate}</p>
<p>Welcome to the Eyecare ERP System. By accessing or using our services, you agree to be bound by these Terms and Conditions. Please read them carefully.</p>

<h3>1. Acceptance of Terms</h3>
<p>By registering an account, creating a store, or using any of our services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you must not use our services.</p>

<h3>2. Account Registration and Verification</h3>
<h4>2.1 Account Creation</h4>
<ul>
    <li>You must provide accurate, current, and complete information during registration.</li>
    <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
    <li>You must verify your email address within 7 days of registration to maintain account access.</li>
    <li>You are responsible for all activities that occur under your account.</li>
</ul>

<h4>2.2 Email Verification</h4>
<ul>
    <li>Email verification is mandatory for account activation.</li>
    <li>Unverified accounts may be subject to restrictions or automatic suspension after 7 days.</li>
    <li>You must use a valid, accessible email address for account verification.</li>
</ul>

<h3>3. Store Management</h3>
<h4>3.1 Store Creation</h4>
<ul>
    <li>Users may create and manage their own store(s) through the platform.</li>
    <li>You must provide accurate store information including name, email, phone number, and address.</li>
    <li>Stores must be created within 14 days of account registration to maintain active status.</li>
    <li>Each user account may be associated with one or more stores.</li>
</ul>

<h4>3.2 Store Status</h4>
<ul>
    <li>Stores may be activated or deactivated by administrators at their discretion.</li>
    <li>Inactive stores may not be accessible for customer management or order processing.</li>
    <li>You are responsible for maintaining accurate store information.</li>
</ul>

<h3>4. Customer Data Management</h3>
<h4>4.1 Customer Information</h4>
<ul>
    <li>You are responsible for collecting, storing, and managing customer data in compliance with applicable data protection laws.</li>
    <li>You must obtain proper consent from customers before storing their personal information.</li>
    <li>Customer data must be kept accurate and up-to-date.</li>
    <li>You are responsible for maintaining the confidentiality and security of customer data.</li>
</ul>

<h4>4.2 Data Protection</h4>
<ul>
    <li>You must comply with all applicable data protection regulations, including but not limited to GDPR, and local data protection laws.</li>
    <li>You must implement appropriate security measures to protect customer data.</li>
    <li>You must notify customers and the platform administrators of any data breaches affecting their information.</li>
</ul>

<h3>5. Eye Examination Records</h3>
<h4>5.1 Medical Data</h4>
<ul>
    <li>Eye examination records contain sensitive medical information and must be handled with utmost care.</li>
    <li>You are responsible for the accuracy and completeness of all examination data entered into the system.</li>
    <li>Examination records include but are not limited to: visual acuity, prescription details (sphere, cylinder, axis), pupillary distance, intraocular pressure, and diagnostic information.</li>
</ul>

<h4>5.2 Professional Responsibility</h4>
<ul>
    <li>Only qualified eye care professionals should enter and manage eye examination records.</li>
    <li>You must ensure that all examination data complies with medical record-keeping standards.</li>
    <li>Examination records may be used to generate prescriptions and reports for patients.</li>
    <li>You are responsible for maintaining patient confidentiality in accordance with medical ethics and legal requirements.</li>
</ul>

<h4>5.3 PDF Generation</h4>
<ul>
    <li>The system may generate PDF documents containing examination results and prescriptions.</li>
    <li>You are responsible for verifying the accuracy of generated documents before distribution to patients.</li>
    <li>PDF documents are generated for informational purposes and should be reviewed by qualified professionals.</li>
</ul>

<h3>6. Orders and Payments</h3>
<h4>6.1 Order Processing</h4>
<ul>
    <li>All orders are processed in Indian Rupees (₹).</li>
    <li>Order prices, taxes, and shipping charges are displayed in Indian Rupees.</li>
    <li>You are responsible for accurate pricing and order management.</li>
</ul>

<h4>6.2 Payment Terms</h4>
<ul>
    <li>Payment terms and conditions are subject to the specific agreements between you and your customers.</li>
    <li>The platform facilitates order management but is not responsible for payment processing disputes.</li>
    <li>All financial transactions must comply with applicable financial regulations.</li>
</ul>

<h3>7. Spam Account Policy</h3>
<h4>7.1 Spam Detection</h4>
<ul>
    <li>The system employs automated spam detection mechanisms to identify and flag suspicious accounts.</li>
    <li>Accounts may be marked as spam based on various factors including but not limited to:
        <ul>
            <li>Email not verified after 7 days</li>
            <li>No store created after 14 days</li>
            <li>Suspicious name patterns (test, demo, fake, etc.)</li>
            <li>Suspicious email domains (tempmail, guerrillamail, etc.)</li>
            <li>Name too short (less than 3 characters)</li>
            <li>No login activity for 30 days</li>
            <li>No device registered after 3 days</li>
            <li>Multiple accounts from same IP address (more than 3 in 24 hours)</li>
        </ul>
    </li>
    <li>Spam accounts may be automatically flagged or suspended by the system.</li>
</ul>

<h4>7.2 Account Suspension</h4>
<ul>
    <li>Spam accounts may be suspended or deleted without prior notice.</li>
    <li>Administrators reserve the right to review and manually mark accounts as spam.</li>
    <li>You may appeal spam account designation by contacting platform administrators.</li>
</ul>

<h3>8. Account Blocking and Suspension</h3>
<h4>8.1 Blocking Policy</h4>
<ul>
    <li>Administrators may block or suspend user accounts at their discretion for violations of these terms.</li>
    <li>Blocked accounts will be unable to access the platform or perform any actions.</li>
    <li>Common reasons for account blocking include:
        <ul>
            <li>Violation of terms and conditions</li>
            <li>Fraudulent or illegal activities</li>
            <li>Abuse of platform resources</li>
            <li>Security concerns</li>
            <li>Non-compliance with data protection regulations</li>
        </ul>
    </li>
</ul>

<h4>8.2 Suspension Process</h4>
<ul>
    <li>Account blocking may occur immediately without prior notice in cases of serious violations.</li>
    <li>You will be notified of account blocking via email when possible.</li>
    <li>Blocked accounts may be restored at the discretion of administrators upon resolution of the issue.</li>
</ul>

<h3>9. API Access and Usage</h3>
<h4>9.1 API Authentication</h4>
<ul>
    <li>API access requires valid authentication tokens (Sanctum tokens).</li>
    <li>You are responsible for maintaining the security of your API tokens.</li>
    <li>API tokens must not be shared with unauthorized parties.</li>
</ul>

<h4>9.2 API Usage Limits</h4>
<ul>
    <li>API usage is subject to rate limiting to ensure system stability.</li>
    <li>Excessive API usage may result in temporary or permanent access restrictions.</li>
    <li>You must use the API in accordance with its intended purpose and documentation.</li>
</ul>

<h4>9.3 Mobile Application</h4>
<ul>
    <li>Mobile applications using the API must comply with these Terms and Conditions.</li>
    <li>You are responsible for ensuring that mobile applications properly handle user data and authentication.</li>
    <li>Mobile applications must display these Terms and Conditions and obtain user acceptance.</li>
</ul>

<h3>10. Privacy and Data Protection</h3>
<h4>10.1 Data Collection</h4>
<ul>
    <li>We collect and process personal data necessary for providing our services.</li>
    <li>Data collection includes but is not limited to: user account information, store data, customer records, and eye examination data.</li>
    <li>We implement appropriate security measures to protect your data.</li>
</ul>

<h4>10.2 Data Usage</h4>
<ul>
    <li>Your data is used to provide, maintain, and improve our services.</li>
    <li>We do not sell your personal data to third parties.</li>
    <li>Data may be used for analytics, system improvements, and compliance purposes.</li>
</ul>

<h4>10.3 Data Retention</h4>
<ul>
    <li>Data is retained as long as necessary to provide services and comply with legal obligations.</li>
    <li>You may request deletion of your data subject to legal and operational requirements.</li>
    <li>Some data may be retained for audit and compliance purposes even after account deletion.</li>
</ul>

<h3>11. Intellectual Property</h3>
<h4>11.1 Platform Ownership</h4>
<ul>
    <li>All platform software, design, content, and intellectual property are owned by the platform operators.</li>
    <li>You are granted a limited, non-exclusive license to use the platform for its intended purpose.</li>
</ul>

<h4>11.2 User Content</h4>
<ul>
    <li>You retain ownership of data and content you create and upload to the platform.</li>
    <li>By using the platform, you grant us a license to store, process, and display your content as necessary to provide services.</li>
    <li>You are responsible for ensuring you have the right to use and store any content you upload.</li>
</ul>

<h3>12. Prohibited Activities</h3>
<p>You agree not to:</p>
<ul>
    <li>Use the platform for any illegal or unauthorized purpose</li>
    <li>Violate any applicable laws or regulations</li>
    <li>Infringe upon the rights of others</li>
    <li>Transmit any viruses, malware, or harmful code</li>
    <li>Attempt to gain unauthorized access to the platform or other users' accounts</li>
    <li>Interfere with or disrupt the platform's operation</li>
    <li>Use automated systems to access the platform without authorization</li>
    <li>Create multiple accounts to circumvent restrictions or policies</li>
    <li>Share your account credentials with others</li>
    <li>Use the platform to store or process data in violation of applicable laws</li>
</ul>

<h3>13. Service Availability</h3>
<h4>13.1 Uptime</h4>
<ul>
    <li>We strive to maintain high service availability but do not guarantee uninterrupted access.</li>
    <li>The platform may be temporarily unavailable for maintenance, updates, or due to unforeseen circumstances.</li>
</ul>

<h4>13.2 Modifications</h4>
<ul>
    <li>We reserve the right to modify, suspend, or discontinue any part of the service at any time.</li>
    <li>We will provide reasonable notice of significant changes when possible.</li>
    <li>Continued use of the service after modifications constitutes acceptance of changes.</li>
</ul>

<h3>14. Limitation of Liability</h3>
<h4>14.1 Medical Disclaimer</h4>
<ul>
    <li>The platform is a management tool and does not provide medical advice, diagnosis, or treatment.</li>
    <li>Eye examination data and prescriptions generated through the platform should be reviewed and verified by qualified eye care professionals.</li>
    <li>We are not responsible for medical decisions made based on data stored in the platform.</li>
</ul>

<h4>14.2 General Liability</h4>
<ul>
    <li>To the maximum extent permitted by law, we are not liable for any indirect, incidental, special, or consequential damages.</li>
    <li>Our total liability is limited to the amount you have paid for services in the past 12 months.</li>
    <li>We are not responsible for data loss, service interruptions, or unauthorized access beyond our reasonable control.</li>
</ul>

<h3>15. Indemnification</h3>
<p>You agree to indemnify and hold harmless the platform operators, their affiliates, and employees from any claims, damages, losses, or expenses arising from:</p>
<ul>
    <li>Your use of the platform</li>
    <li>Your violation of these Terms and Conditions</li>
    <li>Your violation of any applicable laws or regulations</li>
    <li>Your infringement of any third-party rights</li>
    <li>Any content or data you submit to the platform</li>
</ul>

<h3>16. Termination</h3>
<h4>16.1 Termination by You</h4>
<ul>
    <li>You may terminate your account at any time by contacting platform administrators.</li>
    <li>Upon termination, your access to the platform will be immediately suspended.</li>
    <li>Data retention policies apply after account termination.</li>
</ul>

<h4>16.2 Termination by Us</h4>
<ul>
    <li>We may terminate or suspend your account immediately for violations of these terms.</li>
    <li>We may terminate accounts that are inactive for extended periods.</li>
    <li>Termination may occur without prior notice in cases of serious violations.</li>
</ul>

<h3>17. Changes to Terms</h3>
<ul>
    <li>We reserve the right to modify these Terms and Conditions at any time.</li>
    <li>Material changes will be communicated via email or platform notifications.</li>
    <li>Continued use of the service after changes constitutes acceptance of new terms.</li>
    <li>You may be required to accept updated terms before continuing to use certain features.</li>
    <li>Previous versions of terms will be archived for reference.</li>
</ul>

<h3>18. Governing Law and Dispute Resolution</h3>
<ul>
    <li>These Terms and Conditions are governed by the laws of India.</li>
    <li>Any disputes arising from these terms will be subject to the exclusive jurisdiction of Indian courts.</li>
    <li>We encourage resolution of disputes through direct communication before pursuing legal action.</li>
</ul>

<h3>19. Contact Information</h3>
<p>For questions, concerns, or to report violations of these Terms and Conditions, please contact the platform administrators through the provided contact channels.</p>

<h3>20. Acceptance</h3>
<p>By clicking "Accept" or using our services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree, you must discontinue use of the platform immediately.</p>

<p><strong>Version:</strong> 1.0<br>
<strong>Effective Date:</strong> {$currentDate}</p>
HTML;

        TermsAndCondition::create([
            'title' => 'Terms and Conditions of Service - Eyecare ERP System',
            'content' => $termsContent,
            'version' => '1.0',
            'is_active' => true,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        $this->command->info('Terms and Conditions seeded successfully!');
    }
}
