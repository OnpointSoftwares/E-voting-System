<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div class="container">
        <div class="heading"><h1>Online Voting System</h1></div>
        <form action="register_data.php" method="POST" enctype="multipart/form-data">
            <div class="form">
                <h4>Voter Registraton</h4>
                <label class="label"><sup class="req_symbol">*</sup>Firstname:</label>
                <input type="text" name="fname" id="firstname" class="input" placeholder=" Enter First Name" required>

                <label class="label"><sup class="req_symbol">*</sup>Lastname:</label>
                <input type="text" name="lname" id="" class="input" placeholder="Enter Last Name" required>

                <label class="label"><sup class="req_symbol">*</sup>Choose ID Proof:</label>
                <select name="idname" id="myselect" class="input" onchange="idproof()">
                    
                    <option value="Voter Card">Voter Card</option>
                    <option value="Passport">Passport</option>
                    <option value="Other ID Card">Other ID Card</option>
                </select>
                <label class="label" id="myid1"><sup class="req_symbol">*</sup>ID NUMBER:</label>
<input type="text" name="idnum" id="idnum" placeholder="Enter ID Number" class="input"
       pattern="^[a-zA-Z0-9]{8,9}$"
       required
       oninput="validateIDNumber(this)">
<span id="idNumberError" style="color: red;"></span>

<script>
function validateIDNumber(input) {
    const idNumberPattern = /^[a-zA-Z0-9]{8,9}$/; // Allows letters and numbers, 8 or 9 chars
    // If ID numbers are strictly numeric, use: const idNumberPattern = /^\d{8,9}$/;
    const idNumberError = document.getElementById('idNumberError');

    if (idNumberPattern.test(input.value)) {
        idNumberError.textContent = ''; // Clear error message
        input.setCustomValidity(''); // Clear custom validation message, indicating valid
    } else {
        idNumberError.textContent = 'ID Number must be 8 or 9 characters long.';
        input.setCustomValidity('Invalid ID Number format'); // Set custom validation message for browser
    }
}
</script>

                <label class="label" id="myid"><sup class="req_symbol">*</sup>Aadhar:</label>
                <input type="file" accept="image/*" name="idcard" id="myfile" class="input" required>

                <label class="label" id="myid1"><sup class="req_symbol">*</sup>Institute Id No:</label>
                <input type="text" name="instidnum" placeholder="Enter Institute id Number" class="input" required>

                <label class="label"><sup class="req_symbol">*</sup>Date of Birth:</label>
                <input type="date" name="dob" class="input" required>

                <label class="label"><sup class="req_symbol">*</sup>Gender:</label>
                <input type="radio" value="male" name="gender" id="" class="radio" required>Male
                <input type="radio" value="female" name="gender" id="" class="radio">Female
                <input type="radio" value="other" name="gender" id="" class="radio">Other

                <label class="label"><sup class="req_symbol">*</sup>Phone Number:</label>
<input type="tel" name="phone" id="phone" class="input" placeholder="e.g., 0712345678 or +254712345678"
       pattern="^(\+?\d{1,3}[- ]?)?\d{10}$"
       required
       oninput="validatePhoneNumber(this)">
<span id="phoneError" style="color: red;"></span>

<script>
function validatePhoneNumber(input) {
    const phonePattern = /^(\+?\d{1,3}[- ]?)?\d{9,15}$/; // A bit more flexible for international
    const kenyanMobilePattern = /^(0|(\+254))?7\d{8}$/; // Specific for Kenyan mobile numbers (e.g., 07xx or +2547xx)
    const phoneError = document.getElementById('phoneError');

    // Option 1: Using the pattern from the input field (basic check)
    // if (input.checkValidity()) {
    //     phoneError.textContent = '';
    // } else {
    //     phoneError.textContent = 'Please enter a valid phone number (e.g., +123-1234567890 or 1234567890).';
    // }

    // Option 2: Using a more specific JavaScript regex
    if (kenyanMobilePattern.test(input.value)) { // Or use phonePattern.test(input.value)
        phoneError.textContent = ''; // Clear error message if valid
        input.setCustomValidity(''); // Clear custom validation message
    } else {
        phoneError.textContent = 'Please enter a valid Kenyan mobile number (e.g., 07XXXXXXXX or +2547XXXXXXXX).';
        input.setCustomValidity('Invalid phone number format'); // Set custom validation message
    }
}
</script>
                <label class="label"><sup class="req_symbol">*</sup>Email:</label>
<input type="email" name="email" id="email" class="input" placeholder="Enter Email" required oninput="validateEmail(this)">
<span id="emailError" style="color: red;"></span>

<script>
function validateEmail(input) {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const emailError = document.getElementById('emailError');

    if (emailPattern.test(input.value)) {
        emailError.textContent = ''; // Clear error message if valid
        input.setCustomValidity(''); // Clear custom validation message
    } else {
        emailError.textContent = 'Please enter a valid email address.';
        input.setCustomValidity('Invalid email format'); // Set custom validation message
    }
}
</script>

                
                <button class="button" name="register">Register</button>
                <div class="link1">Already have account ? <a href="index.php">Login here</a></div>
            </div>
        </form>
   </div> 
   <p class="msg"></p>
   <script>
       function idproof()
       {
            var x=document.getElementById("myselect").value;
            document.getElementById("myid").innerHTML=x+":";
            document.getElementById("myid1").innerHTML=x+" No:";
       }
   </script>
</body>
</html>