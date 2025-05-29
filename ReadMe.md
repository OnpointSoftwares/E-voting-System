**Voting System**

## Introduction

This is a web-based online voting system that allows voters to vote for their preferred candidates in an election. The system is designed to be user-friendly and easy to use, with a simple and intuitive interface. It also provides a secure and reliable way for voters to cast their votes, with features such as password protection and OTP verification.

##Features

    1. User Registration
    2. OTP Verification
    3. Candidate Registration
    4. Candidate Login
    5. Candidate Dashboard
    6. Candidate Profile
    7. Candidate Logout
    8. Candidate Voting
    9. Candidate Results
    10. Candidate Logout
    11. OTP send to user phone
    
## Technologies

    1. HTML
    2. CSS
    3. JavaScript
    4. PHP
    5. MySQL
    6. Bootstrap
    7. jQuery
    8. AJAX
    9. JSON
    10. XML
    11. XML

## Setup

    1. Download the latest version of XAMPP from the official website.
    2. Install XAMPP on your computer.
    3. Start the Apache and MySQL services.
    4. Open your web browser and navigate to http://localhost/phpmyadmin.
    5. Create a new database named "voting".
    6. Import the SQL file into the "voting" database.
    7. Open the "config.php" file and update the database connection details.
    8. Open the "index.php" file and update the database connection details.
    9. Open the "voting-system.php" file and update the database connection details.
    10. Open the "otpform.php" file and update the database connection details.
    11. Open the "voter_login_data.php" file and update the database connection details.
    12. Open the "voter_login_data.php" file and update the database connection details.
    13. Open the "voter_login_data.php" file and update the database connection details.
    14. Open the "voter_login_data.php" file and update the database connection details.
    15. Open the "voter_login_data.php" file and update the database connection details.
    16. Open the "voter_login_data.php" file and update the database connection details.
    17. Open the "voter_login_data.php" file and update the database connection details.
    18. Open the "voter_login_data.php" file and update the database connection details.
    19. Open the "voter_login_data.php" file and update the database connection details.
    20. Open the "voter_login_data.php" file and update the database connection details.

## Usage

    1. Open your web browser and navigate to http://localhost/voting.
    2. Click on the "Register" button to register a new user.
    3. Fill in the required fields and click on the "Register" button.
    4. Click on the "Login" button to log in to the system.
    5. Fill in the required fields and click on the "Login" button.
    6. Click on the "Dashboard" button to access the dashboard.
    7. Click on the "Profile" button to access the profile.
    8. Click on the "Logout" button to log out of the system.
    9. Click on the "Vote" button to vote for a candidate.
    10. Click on the "Results" button to view the results.
    11. Click on the "Logout" button to log out of the system.
    
## Contact

    1. Email: vincentbettoh@gmail.com
    2. Phone: +254702502952
## cloning

    1. git clone https://github.com/vincentbettoh/E-Voting-System.git
    2. cd E-Voting-System
    3. cd voting
    4. cd includes
    5. cd africastalking-php-master
    6. composer install
    7. cd ..
## Setting Up Africa's Talking SMS Service

1. **Create an Africa's Talking Account**
   - Go to [Africa's Talking](https://account.africastalking.com/auth/register)
   - Sign up for a new account (sandbox account is free for testing)
   - Verify your email address

2. **Create an App**
   - Log in to your Africa's Talking account
   - Go to the dashboard and click on "Create App"
   - Name your app (e.g., "VotingSystem")
   - Select "SMS" as the service
   - Click "Create App"

3. **Get Your API Key**
   - Go to the "Settings" section
   - Under "API Keys", find your API key
   - Copy both the API Key and your Username (usually starts with 'sandbox' for test accounts)

4. **Update Configuration**
   In the following files, update the Africa's Talking credentials:
   - `voting/register_data.php`
   - `voting/otpform.php`
   
   Look for these lines and replace with your credentials:
   ```php
   $username = "voting_2025";  // Replace with your Africa's Talking username
   $apiKey = "your_api_key_here";  // Replace with your API key
   ```

5. **Phone Number Format**
   - The system expects phone numbers in international format (e.g., +2547XXXXXXXX)
   - For testing in sandbox, you must use approved test numbers
   - To add test numbers, go to your Africa's Talking dashboard → SMS → Settings → Test Credentials

6. **Testing**
   - Test the registration process with a verified test number
   - Check the Africa's Talking dashboard for SMS delivery status
   - Monitor your application's error logs for any issues

7. **Going Live**
   - Once testing is complete, contact Africa's Talking to upgrade to a production account
   - You'll need to provide business details and use case information
   - Production accounts require a minimum credit purchase

8. **Troubleshooting**
   - **401 Unauthorized**: Check your API key and username
   - **Invalid Phone Number**: Ensure numbers are in international format
   - **SMS Not Delivering**: Check account balance and number verification status
   - **Sandbox Restrictions**: Remember sandbox has daily limits and works only with approved numbers

For more information, visit [Africa's Talking Documentation](https://developers.africastalking.com/)
    