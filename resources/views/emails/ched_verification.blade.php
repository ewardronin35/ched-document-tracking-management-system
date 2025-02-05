<!-- resources/views/emails/ched_verification.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CHED Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Your existing styles */
        /* Basic Reset */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }
        /* Responsive Styles */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            .btn {
                width: 100% !important;
                display: block !important;
                text-align: center !important;
            }
            .logos-table {
                width: 100% !important;
                display: block !important;
            }
            .logos-table td {
                display: block !important;
                text-align: center !important;
                margin-bottom: 10px !important;
            }
        }
        /* Button Styling */
        .btn {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            display: inline-block;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <!-- Main Container -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Email Content Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="container" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header Section with CHED Logos -->
                    <tr>
                        <td align="center" style="padding: 40px 30px 20px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="logos-table">
                                <tr>
                                    <!-- First Logo -->
                                    <td align="center" width="50%">
                                        <img src="{{ $message->embed(public_path('images/Logo.png')) }}" alt="CHED Logo" width="150" style="display: block; border: 0;"/>
                                    </td>
                                    <!-- Second Logo -->
                                    <td align="center" width="50%">
                                        <img src="{{ $message->embed(public_path('images/Logo2.png')) }}" alt="CHED Logo 2" width="150" style="display: block; border: 0;"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Greeting Section -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            @if (!empty($greeting))
                                <h1 style="font-family: Arial, sans-serif; color: #333333; text-align: center; margin: 0; font-size: 24px;">{{ $greeting }}</h1>
                            @else
                                <h1 style="font-family: Arial, sans-serif; color: #333333; text-align: center; margin: 0; font-size: 24px;">Hello!</h1>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Introductory Text -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <p style="font-family: Arial, sans-serif; color: #555555; font-size: 16px; line-height: 1.5; margin: 0 0 15px 0;">
                                Thank you for registering in the <strong>CHED Document Tracking Management System (CDTMS)</strong>. To complete your registration, please verify your email address by clicking the button below.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Action Button -->
                    @isset($actionText)
                        <tr>
                            <td align="center" style="padding: 0 30px 30px 30px;">
                                <a href="{{ $actionUrl }}" class="btn">
                                    {{ $actionText }}
                                </a>
                            </td>
                        </tr>
                    @endisset
                    
                    <!-- Outro Lines -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <p style="font-family: Arial, sans-serif; color: #555555; font-size: 16px; line-height: 1.5; margin: 0 0 15px 0;">
                                If you did not create an account, no further action is required.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Salutation -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <p style="font-family: Arial, sans-serif; color: #555555; font-size: 16px; line-height: 1.5; margin: 0;">
                                Regards,<br>
                                <strong>Commission on Higher Education (CHED)</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px; border-top: 1px solid #eeeeee;">
                            <p style="font-family: Arial, sans-serif; color: #999999; font-size: 12px; line-height: 1.5; margin: 10px 0 0 0;">
                                Commission on Higher Education (CHED)<br>
                                Address: Baliwasan Chico Road, Zamboanga, Zamboanga del Sur<br>
                                Contact: (02) 8946-5000 | <a href="mailto:info@ched.gov.ph" style="color: #007bff; text-decoration: none;">info@ched.gov.ph</a>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Subcopy -->
                
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
